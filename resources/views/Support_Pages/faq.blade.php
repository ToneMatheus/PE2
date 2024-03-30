<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 gap-4">
                    <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">What services do you offer?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">We offer a range of electricity supply services including residential, commercial, and industrial plans tailored to meet your energy needs.</div>
                </div>
            
                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">How can I sign up for your services?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">Signing up for our services is easy! Simply visit our website and follow the prompts to choose the plan that best fits your needs. You can also contact our customer service team for assistance.</div>
                </div>
            
                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">Are there any contracts or commitments?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">We offer both contract and non-contract options depending on your preference. Our customer service team can help you choose the best option for your situation.</div>
                </div>

                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">How can I pay my electricity bill?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">We offer various payment methods including online payments, direct debit, and in-person payments at authorized locations. You can choose the method that is most convenient for you.</div>
                </div>
                

                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">What happens if there's a power outage?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">In the event of a power outage, please contact your local utility provider as they are responsible for maintaining the power grid and restoring service.</div>
                </div>

                <div class="flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="question text-blue-500  dark:text-white mb-2">Can I switch to your service from another provider?</div>
                    <div class="answer text-gray-600 dark:text-gray-400 text-sm">Yes, you can switch to our service from another provider. We offer a seamless switching process and our customer service team is available to assist you every step of the way.</div>
                </div>

                </div>
                <div class="text-center flex flex-col items-center justify-center pb-4">
                    <p class="text-center text-gray-600 dark:text-white text-lg">If you have any other questions, feel free to create a ticket.</p>
                    <a href="{{ route('create-ticket') }}" class="inline-block items-center px-4 py-2 border border-transparent text-lg leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 focus:outline-none transition ease-in-out duration-150 mt-2">
                        Create a Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>