'use client';

import Link from 'next/link';
import { UserNav } from './user-nav';
import { MobileNav } from './mobile-nav';

export function DashboardHeader() {
	return (
		<header className="sticky top-0 z-40 border-b bg-background">
			<div className="container flex h-16 items-center justify-between py-4">
				<div className="flex gap-6 md:gap-10">
					<Link href="/" className="hidden md:block">
						<span className="font-bold">Your App</span>
					</Link>
					<MobileNav />
				</div>
				<UserNav />
			</div>
		</header>
	);
}
