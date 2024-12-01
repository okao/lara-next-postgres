'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { cn } from '@/lib/utils';
import {
	LayoutDashboard,
	Settings,
	User,
	CreditCard,
	HelpCircle,
} from 'lucide-react';

const items = [
	{
		title: 'Dashboard',
		href: '/dashboard',
		icon: LayoutDashboard,
	},
	{
		title: 'Profile',
		href: '/profile',
		icon: User,
	},
	{
		title: 'Billing',
		href: '/billing',
		icon: CreditCard,
	},
	{
		title: 'Settings',
		href: '/settings',
		icon: Settings,
	},
	{
		title: 'Help',
		href: '/help',
		icon: HelpCircle,
	},
];

export function DashboardNav() {
	const pathname = usePathname();

	return (
		<nav className="grid items-start gap-2 p-4">
			{items.map((item) => {
				const Icon = item.icon;
				return (
					<Link
						key={item.href}
						href={item.href}
						className={cn(
							'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground',
							pathname === item.href ? 'bg-accent' : 'transparent'
						)}
					>
						<Icon className="h-4 w-4" />
						<span>{item.title}</span>
					</Link>
				);
			})}
		</nav>
	);
}
