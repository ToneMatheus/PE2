<!-- LOOK Manage page hier zetten -->
<!-- TODO als de type huis op appartement staat moet je de huisbaas ook in zetten. dit is met een checkbox. -->
<!-- TODO deze checkbox kan ook aangepast worden-->
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Address information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Choose your invoice address.") }}
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

    <!-- CH de route veranderen -->
    <form method="post" action="{{ route('profile.update.address.billing') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="is_billing_address" :value="__('Billing address')" />
            <select name="is_billing_address" id="is_billing_address">
            @foreach ($addresses as $key => $address)
                <option value="{{ $address }}">Address {{$key+1}}</option>
            @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
