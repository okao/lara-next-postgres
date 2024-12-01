export default function MarketingPage() {
	return (
		<div className="flex flex-col gap-8 pb-8">
			<section className="space-y-6 pb-8 pt-6 md:pb-12 md:pt-10 lg:py-32">
				<div className="container flex max-w-[64rem] flex-col items-center gap-4 text-center">
					<h1 className="font-heading text-3xl sm:text-5xl md:text-6xl lg:text-7xl">
						Your Amazing Product
					</h1>
					<p className="max-w-[42rem] leading-normal text-muted-foreground sm:text-xl sm:leading-8">
						A brief description of your product and its main value
						proposition.
					</p>
					<div className="space-x-4">
						<a
							href="/auth/register"
							className="inline-flex items-center justify-center rounded-md bg-primary px-6 py-3 text-sm font-medium text-primary-foreground shadow hover:bg-primary/90"
						>
							Get Started
						</a>
						<a
							href="/auth/login"
							className="inline-flex items-center justify-center rounded-md border border-input bg-background px-6 py-3 text-sm font-medium shadow-sm hover:bg-accent hover:text-accent-foreground"
						>
							Sign In
						</a>
					</div>
				</div>
			</section>
		</div>
	);
}
