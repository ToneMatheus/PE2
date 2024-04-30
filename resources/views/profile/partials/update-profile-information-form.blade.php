<!-- LOOK Manage page hier zetten -->
<!-- TODO index_method bij zetten-->
<!-- TODO nationality bij zetten-->
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    @if (session('verify_email_message'))
        <div class="alert alert-info" style="color: red;">
            {{ session('verify_email_message') }}
        </div>
    @endif          

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- <form method="post" action="{{-- route('profile.update') --}}" class="mt-6 space-y-6"> -->
    <form method="post" action="{{ route('profile.update.profile') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="block w-full mt-1" :value="old('first_name', $user->first_name)"
                required autofocus autocomplete="first_name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" name="last_name" type="text" class="block w-full mt-1" :value="old('last_name', $user->last_name)"
                required autofocus autocomplete="last_name" />
            <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
        </div>

        <div>
            <x-input-label for="title" :value="__('Title')" />
            <label for='Mr' class="mt-1 text-sm dark:text-gray-100">Mr</label>
            <input type="radio" name="title" id="mr" value="Mr" @if($user->title == 'Mr') checked @endif>
            <label for='Ms' class="mt-1 text-sm dark:text-gray-100">Ms</label>
            <input type="radio" name="title" id="Ms" value="Ms" @if($user->title == 'Ms') checked @endif>
            <label for='X' class="mt-1 text-sm dark:text-gray-100">X</label>
            <input type="radio" name="title" id="X" value="X" @if($user->title == 'X') checked @endif>
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>


        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)"
                required autocomplete="Email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone_nbr" :value="__('Phone number')" />
            <x-text-input id="phone_nbr" name="phone_nbr" type="tel" class="block w-full mt-1"
                :value="old('phone_nbr', $user->phone_nbr)" required autofocus autocomplete="phone_nbr"
                pattern="0[0-9]{3} [0-9]{3} [0-9]{3}" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_nbr')" />
        </div>

        <div>
            <x-input-label for="birth_date" :value="__('Birth date')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="block w-full mt-1" :value="old('birth_date', $user->birth_date)"
                required autofocus autocomplete="birth_date" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="block w-full mt-1" :value="old('username', $user->username)"
                required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div class="mt-4">
            <x-input-label for="nationality" :value="__('Nationality')" />
            <x-text-input id="nationality" class="block mt-1 w-full" type="text" name="nationality" :value="old('nationality', $user->nationality)" autofocus autocomplete="nationality" />
            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="index_method" :value="__('Meter reading')" />
            <select name="index_method" id="index_method">
                <option value="website" {{ old('index_method', $user->index_method) == 'website' ? 'selected' : '' }}>Website</option>
                <option value="paper" {{ old('index_method', $user->index_method) == 'paper' ? 'selected' : '' }}>Paper</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        @if($user->is_company == 1)
            <div>
                <x-input-label for="company_name" :value="__('Company name')" />
                <x-text-input id="company_name" name="company_name" type="text" class="block w-full mt-1" :value="old('company_name', $user->company_name)"
                    required autofocus autocomplete="company_name" />
                <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
