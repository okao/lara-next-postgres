'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { usePathname } from 'next/navigation';
import { AuthService } from '@/services/auth';

export function AuthCheck({
	children,
}: {
	children: React.ReactNode;
}) {
	const router = useRouter();
	const pathname = usePathname();

	useEffect(() => {
		const checkAuth = async () => {
			const token = AuthService.getAccessToken();

			if (!token) {
				// Store the attempted URL to redirect back after login
				sessionStorage.setItem('redirectUrl', pathname);
				router.push('/auth/login');
				return;
			}

			try {
				// Verify token is valid by making a request to /me endpoint
				const response = await fetch(
					`${process.env.NEXT_PUBLIC_API_URL}/me`,
					{
						headers: {
							Authorization: `Bearer ${token}`,
						},
						credentials: 'include',
					}
				);

				if (!response.ok) {
					// If token is invalid, try to refresh
					const refreshed = await AuthService.refreshToken();
					if (!refreshed) {
						throw new Error('Failed to refresh token');
					}
				}
			} catch (error) {
				console.error('Auth check failed:', error);
				AuthService.clearTokens();
				router.push('/auth/login');
			}
		};

		checkAuth();
	}, [router, pathname]);

	return <>{children}</>;
}
