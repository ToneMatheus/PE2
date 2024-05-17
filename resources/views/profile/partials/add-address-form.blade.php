<script>
    document.addEventListener('DOMContentLoaded', function() {
        var isCompany = {{ Auth::user()->is_company ?? 0 }};

        var businessRadioDiv = document.getElementById("business_div");
        var businessRadio = document.getElementById("business");
        var houseRadio = document.getElementById("house");
        var appartmentRadio = document.getElementById("appartment");
        var landlordDiv = document.getElementById("is_landlord_div_add");

        function toggleFields() {
            if (isCompany) {
                businessRadioDiv.style.display = "inline";
            } else {
                businessRadioDiv.style.display = "none";
            }
        }

        function toggleLandlord() {

            if(appartmentRadio.checked || businessRadio.checked){
                landlordDiv.style.display = "block";
            }else{
                landlordDiv.style.display = "none";
            }
        }


        toggleFields();
        toggleLandlord();

        houseRadio.addEventListener('change', toggleLandlord);
        appartmentRadio.addEventListener('change', toggleLandlord);
        businessRadio.addEventListener('change', toggleLandlord);
    });
</script>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('new Address') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Add a new address here.") }}
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
    <form method="post" action="{{ route('profile.add.address') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!--Street -->
    <div class="mt-4">
                <x-input-label for="street" :value="__('Street')" />
                <x-text-input id="street" name="street" type="text" class="block w-full mt-1" :value="old('street')"
                    required autofocus autocomplete="street" />
                <x-input-error class="mt-2" :messages="$errors->get('street')" />
        </div>

        <!--Number -->
        <div class="mt-4">
            <x-input-label for="number" :value="__('Number')" />
            <x-text-input id="number" name="number" type="text" class="block w-full mt-1" :value="old('number')"
                required autofocus autocomplete="number" />
            <x-input-error class="mt-2" :messages="$errors->get('number')" />
        </div>

        <!--Box -->
        <div class="mt-4">
            <x-input-label for="box" :value="__('Box')" />
            <x-text-input id="box" name="box" type="text" class="block w-full mt-1" :value="old('box')"
                required autocomplete="box" />
            <x-input-error class="mt-2" :messages="$errors->get('box')" />
        </div>

        <!--Province -->
        <div class="mt-4">
            <x-input-label for="province" :value="__('Province')" />
            <x-text-input id="province" name="province" type="text" class="block w-full mt-1" :value="old('province')"
                required autofocus autocomplete="province" />
            <x-input-error class="mt-2" :messages="$errors->get('province')" />   
        </div>

        <!--City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="block w-full mt-1" :value="old('city')"
                required autofocus autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <!--Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" name="country" type="text" class="block w-full mt-1" :value="old('country')"
                required autofocus autocomplete="country" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <!--Postal code -->
        <div class="mt-4">
            <x-input-label for="postal_code" :value="__('Postal Code')" />
            <x-text-input id="postal_code" name="postal_code" type="text" class="block w-full mt-1"
                :value="old('postal_code')" required autofocus autocomplete="postal_code" />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <!--House -->
        <div class="mt-4">
            <x-input-label for="type" :value="__('Type')" />
            <label for='house' class="mt-1 text-sm dark:text-gray-100">House</label>
            <input type="radio" name="type" id="house" value="house" checked>
            <label for='appartment' class="mt-1 text-sm dark:text-gray-100">Appartment</label>
            <input type="radio" name="type" id="appartment" value="appartment">
            <div id="business_div" style="display: inline;">
                <label for='business' class="mt-1 text-sm dark:text-gray-100">Business</label>
                <input type="radio" name="type" id="business" value="business">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />
        </div>

        <!--for landlord -->
        <div class="mt-4" id="is_landlord_div_add" style="display: none;">
            <x-input-label for="is_landlord" :value="__('Is Landlord')" />
            <input type="checkbox" id="is_landlord" name="is_landlord" @if(old('is_landlord')) checked @endif>
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