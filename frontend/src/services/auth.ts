// interface TokenResponse {
// 	access_token: string;
// 	refresh_token: string;
// 	token_type: string;
// 	expires_in: number;
// }

export class AuthService {
	static getAccessToken(): string | null {
		const cookies = document.cookie.split(';');
		const tokenCookie = cookies.find((c) =>
			c.trim().startsWith('access_token=')
		);
		return tokenCookie ? tokenCookie.split('=')[1] : null;
	}

	static getRefreshToken(): string | null {
		const cookies = document.cookie.split(';');
		const tokenCookie = cookies.find((c) =>
			c.trim().startsWith('refresh_token=')
		);
		return tokenCookie ? tokenCookie.split('=')[1] : null;
	}

	static clearTokens(): void {
		document.cookie =
			'access_token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
		document.cookie =
			'refresh_token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
	}

	static async refreshToken(): Promise<boolean> {
		const refreshToken = this.getRefreshToken();

		if (!refreshToken) {
			return false;
		}

		try {
			const response = await fetch(
				`${process.env.NEXT_PUBLIC_API_URL}/refresh`,
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({ refresh_token: refreshToken }),
					credentials: 'include',
				}
			);

			if (!response.ok) {
				this.clearTokens();
				return false;
			}

			return true;
		} catch {
			this.clearTokens();
			return false;
		}
	}

	static parseToken(token: string): { exp: number } | null {
		try {
			const base64Url = token.split('.')[1];
			const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
			const jsonPayload = decodeURIComponent(
				atob(base64)
					.split('')
					.map(
						(c) =>
							'%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)
					)
					.join('')
			);
			return JSON.parse(jsonPayload);
		} catch {
			return null;
		}
	}
}
