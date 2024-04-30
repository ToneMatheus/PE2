<!-- LOOK Manage page hier zetten -->

<script>
    // Check if at least one address type is "appartement" or "business" and show the is_landlord_div accordingly
    document.addEventListener("DOMContentLoaded", function() {
        var isLandlordDiv = document.getElementById("is_landlord_div");
        var addresses = {!! json_encode($addresses) !!};
        var showLandlordDiv = addresses.some(function(address) {
            return address.type === "appartement" || address.type === "business";
        });

        if (showLandlordDiv) {
            isLandlordDiv.style.display = "block";
        } else {
            isLandlordDiv.style.display = "none";
        }
    });
</script>

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
                <option value="{{ $address }}" {{ old('is_billing_address', $address->is_billing_address) == 1 ? 'selected' : '' }}>Address {{$key+1}}</option>
            @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div class="mt-4" id="is_landlord_div">
            <x-input-label for="is_landlord" :value="__('Is Landlord')" />
            <input type="checkbox" id="is_landlord" name="is_landlord" {{ old('is_landlord') == 1 || App\Models\User::find(Auth::id())->is_landlord ==1 ? 'checked' : '' }}>
            <x-input-error class="mt-2" :messages="$errors->get('is_landlord')" />
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
