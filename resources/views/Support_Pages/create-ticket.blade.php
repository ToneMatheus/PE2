{{-- -*-html-*- --}}
{{-- <x-app-layout> --}}
  <title>Ticket Support</title>
    <div>

      <form method="POST" action="{{ route('submitted-ticket') }}">
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
{{-- </x-app-layout> --}}