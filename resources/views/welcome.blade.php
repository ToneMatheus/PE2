<x-app-layout :title="'Welcome'">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-blue-500 dark:text-white text-3xl mb-4">Welcome to Our Electricity Supplier Company</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">We are a leading provider of electricity in the region. Our mission is to provide reliable and affordable electricity to our customers while maintaining our commitment to sustainability and environmental responsibility.</p>
                    <div class="flex justify-center mt-4">
                        <img src="{{ asset('images/echoice_diff_utility_provider.png') }}" alt="Company Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>