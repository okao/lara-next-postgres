'use client';

import { useState } from 'react';
import { Card } from '@/components/ui/card';

interface DashboardStats {
	totalUsers: number;
	activeUsers: number;
	revenue: number;
}

export default function DashboardPage() {
	const [stats] = useState<DashboardStats>({
		totalUsers: 0,
		activeUsers: 0,
		revenue: 0,
	});

	return (
		<div className="p-6">
			<h1 className="text-2xl font-bold mb-6">Dashboard</h1>
			<div className="grid grid-cols-1 md:grid-cols-3 gap-6">
				<Card>
					<div className="p-6">
						<h3 className="text-sm font-medium text-gray-500">
							Total Users
						</h3>
						<p className="text-2xl font-bold">{stats.totalUsers}</p>
					</div>
				</Card>
				{/* More cards */}
			</div>
		</div>
	);
}
