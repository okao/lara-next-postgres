export function Pricing() {
	const plans = [
		{
			name: 'Basic',
			price: '$9',
			features: ['Feature 1', 'Feature 2', 'Feature 3'],
		},
		{
			name: 'Pro',
			price: '$29',
			features: [
				'Everything in Basic',
				'Feature 4',
				'Feature 5',
				'Feature 6',
			],
		},
		{
			name: 'Enterprise',
			price: '$99',
			features: [
				'Everything in Pro',
				'Feature 7',
				'Feature 8',
				'Feature 9',
			],
		},
	];

	return (
		<div className="bg-gray-50 py-24 sm:py-32">
			<div className="mx-auto max-w-7xl px-6 lg:px-8">
				<div className="mx-auto max-w-2xl text-center">
					<h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
						Simple, transparent pricing
					</h2>
				</div>
				<div className="mx-auto mt-16 grid max-w-lg grid-cols-1 gap-8 lg:max-w-none lg:grid-cols-3">
					{plans.map((plan, index) => (
						<div
							key={index}
							className="flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-gray-200 xl:p-10"
						>
							<div>
								<h3 className="text-lg font-semibold leading-8 text-gray-900">
									{plan.name}
								</h3>
								<p className="mt-4 text-3xl font-bold tracking-tight text-gray-900">
									{plan.price}
									<span className="text-sm font-semibold leading-6 text-gray-600">
										/month
									</span>
								</p>
								<ul role="list" className="mt-8 space-y-3">
									{plan.features.map((feature, featureIndex) => (
										<li
											key={featureIndex}
											className="flex gap-x-3 text-sm leading-6 text-gray-600"
										>
											<span>✓</span>
											{feature}
										</li>
									))}
								</ul>
							</div>
							<a
								href="/auth/register"
								className="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
							>
								Get started
							</a>
						</div>
					))}
				</div>
			</div>
		</div>
	);
}
