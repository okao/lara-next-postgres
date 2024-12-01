export function Features() {
	const features = [
		{
			title: 'Feature 1',
			description: 'Description of feature 1 and its benefits.',
		},
		{
			title: 'Feature 2',
			description: 'Description of feature 2 and its benefits.',
		},
		{
			title: 'Feature 3',
			description: 'Description of feature 3 and its benefits.',
		},
	];

	return (
		<div className="bg-white py-24 sm:py-32" id="features">
			<div className="mx-auto max-w-7xl px-6 lg:px-8">
				<div className="mx-auto max-w-2xl lg:text-center">
					<h2 className="text-base font-semibold leading-7 text-indigo-600">
						Features
					</h2>
					<p className="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
						Everything you need
					</p>
				</div>
				<div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
					<dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
						{features.map((feature, index) => (
							<div key={index} className="flex flex-col">
								<dt className="text-lg font-semibold leading-7 text-gray-900">
									{feature.title}
								</dt>
								<dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
									<p className="flex-auto">{feature.description}</p>
								</dd>
							</div>
						))}
					</dl>
				</div>
			</div>
		</div>
	);
}
