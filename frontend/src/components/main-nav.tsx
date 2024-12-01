'use client';

import Link from 'next/link';

export function MainNav() {
	return (
		<nav className="border-b bg-background">
			<div className="container flex h-16 items-center">
				<Link href="/" className="flex items-center space-x-2">
					<span className="font-bold">Your App</span>
				</Link>
				<div className="ml-auto flex gap-4">
					<Link
						href="/auth/login"
						className="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:text-primary"
					>
						Sign In
					</Link>
					<Link
						href="/auth/register"
						className="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
					>
						Get Started
					</Link>
				</div>
			</div>
		</nav>
	);
}
