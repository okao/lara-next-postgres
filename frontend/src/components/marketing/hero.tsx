export function Hero() {
	return (
		<div className="relative isolate overflow-hidden bg-white">
			<div className="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
				<div className="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0 lg:pt-8">
					<h1 className="mt-10 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
						Your Amazing Product
					</h1>
					<p className="mt-6 text-lg leading-8 text-gray-600">
						A brief description of your product and its main value
						proposition.
					</p>
					<div className="mt-10 flex items-center gap-x-6">
						<a
							href="/auth/register"
							className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
						>
							Get started
						</a>
						<a
							href="#features"
							className="text-sm font-semibold leading-6 text-gray-900"
						>
							Learn more <span aria-hidden="true">→</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	);
}
