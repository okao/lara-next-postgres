import * as React from 'react';
import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import { ThemeProvider } from '@/components/theme-provider';

export const metadata: Metadata = {
	title: 'Your App',
	description: 'Your app description',
};

interface AuthLayoutProps {
	children: React.ReactNode;
}

const inter = Inter({ subsets: ['latin'] });

export default function AuthLayout({ children }: AuthLayoutProps) {
	return (
		<html lang="en" suppressHydrationWarning>
			<head>
				<link rel="icon" href="/favicon.ico" />
			</head>
			<body className={inter.className}>
				<ThemeProvider
					attribute="class"
					defaultTheme="system"
					enableSystem
					disableTransitionOnChange
				>
					<div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
						<div className="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow">
							{children}
						</div>
					</div>
				</ThemeProvider>
			</body>
		</html>
	);
}
