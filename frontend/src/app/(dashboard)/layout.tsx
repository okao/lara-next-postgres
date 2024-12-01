import * as React from 'react';
import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import { ThemeProvider } from '@/components/theme-provider';
import '@/styles/globals.css';
const inter = Inter({ subsets: ['latin'] });

export const metadata: Metadata = {
	title: 'Your App',
	description: 'Your app description',
	keywords: ['Next.js', 'React', 'Laravel'],
};

interface DashboardLayoutProps {
	children: React.ReactNode;
}

import { DashboardNav } from '@/components/dashboard/nav';
import { DashboardHeader } from '@/components/dashboard/header';
import { AuthCheck } from '@/components/auth-check';

export default function DashboardLayout({
	children,
}: DashboardLayoutProps) {
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
					<AuthCheck>
						<div className="flex min-h-screen flex-col">
							<DashboardHeader />
							<div className="container flex-1 items-start md:grid md:grid-cols-[220px_minmax(0,1fr)] md:gap-6 lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-10">
								<aside className="fixed top-14 z-30 -ml-2 hidden h-[calc(100vh-3.5rem)] w-full shrink-0 overflow-y-auto border-r md:sticky md:block">
									<DashboardNav />
								</aside>
								<main className="flex w-full flex-col overflow-hidden">
									{children}
								</main>
							</div>
						</div>
					</AuthCheck>
				</ThemeProvider>
			</body>
		</html>
	);
}
