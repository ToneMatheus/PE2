<x-app-layout>
  <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-center dark:text-white underline mb-6">Ticket Submitted Successfully</h1>

      <div class="mt-8 space-y-6">
        <p class="text-gray-700 dark:text-white">Thank you for submitting your ticket! We will try and resolve it as quickly as possible.</p>
        
        <ul class=" list-inside text-gray-700 dark:text-white space-y-2 list-none">
          <li><strong>Issue: </strong>{{ $ticket->issue }}</li>
          <li><strong>Description: </strong>{{ $ticket->description }}</li>
        </ul>
      </div>
    </div>
  </div>
</x-app-layout>