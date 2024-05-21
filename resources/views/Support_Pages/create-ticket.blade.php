<x-app-layout :title="$title">
  <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-center dark:text-white underline mb-6">Create Ticket</h1>
      <form method="POST" action="{{ route('submitted-ticket') }}" class="mt-8 space-y-6">
        @csrf
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Name</label>
            <input id="name" name="name" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-black focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Name">
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
            <input id="email" name="email" type="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-black focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Email">
          </div>
          <div>
            <label for="issue" class="block text-sm font-medium text-gray-700 dark:text-white">Issue</label>
            <select id="issue" name="issue" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-black focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
              <option value="" disabled>Select an issue</option>
              <option value="login_issue">Login Issue</option>
              <option value="account_verification">Account Verification</option>
              <option value="payment_issue">Payment Issue</option>
              <option value="technical_issue">Technical Issue</option>
              <option value="product_inquiry">Product Inquiry</option>
              <option value="shipping_problem">Shipping Problem</option>
              <option value="refund_request">Refund Request</option>
              <option value="end_contract">End Contract</option>
          </select>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Description</label>
            <textarea id="description" name="description" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 placeholder-gray-500 text-gray-900 dark:text-black focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Description">{{ old('Description') }}</textarea>
          </div>
        </div>

        <div>
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
  @include('chatbot.chatbot')
</x-app-layout>