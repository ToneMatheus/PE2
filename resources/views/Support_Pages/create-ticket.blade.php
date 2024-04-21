<x-app-layout :title="$title">
  <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-lg shadow-md">
      <h1 class="text-2xl font-bold text-center dark:text-white underline mb-6">Create Ticket</h1>
      <form method="POST" action="{{ route('submitted-ticket') }}" class="mt-8 space-y-6">
        @csrf
        <label for="name">Name</label>
        <input type=text name="name" id="name"
          value="{{ old('Name') }}" required>

        <label for="email">Email</label>
        <input type=email name="email" id="email"
          value="{{ old('Email') }}" required>

        <label for="issue">Issue</label>
        <input type=text name="issue" id="issue"
          value="{{ old('Issue') }}" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required>{{ old('Description') }}</textarea>

        <button type=submit>Submit</button>
      </form>
    </div>
</x-app-layout>