<!-- LOOK de REGISTRATION page hier zetten. -->
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiseer de checkbox en het company_name veld
            var checkbox = document.getElementById("is_company");
            var companyField = document.getElementById("company_name_div");
            var businessRadioDiv = document.getElementById("business_div");
            var businessRadio = document.getElementById("business");
            var houseRadio = document.getElementById("house");

            //CH als is company is aangevinkt en je refreshed dan is business radio button weg

            function toggleFields() {
            if (checkbox.checked) {
                companyField.style.display = "block";
                businessRadioDiv.style.visibility = "visible";
                businessRadio.checked = true;
                houseRadio.checked = false;
            } else {
                companyField.style.display = "none";
                businessRadioDiv.style.visibility = "hidden";
                businessRadio.checked = false;
                houseRadio.checked = true;
            }
        }

        toggleFields();

        checkbox.addEventListener('change', toggleFields);
        });
    </script>

<!-- CH een extra pagina maken om een mail opnieuw te sturen en op gestuurd te worden als dit moet -->
    @if (session('verify_email_message'))
        <div class="alert alert-info" style="color: red;">
            {{ session('verify_email_message') }}
        </div>
    @endif 


        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- First name -->
        <div>
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last name -->
        <div>
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Calling -->
        <div class="mt-4">
            <x-input-label for="title" :value="__('Title')" />
                <!-- CH pas de colom van de database aan voor de calling -->
            <lable for='Mr'>Mr</label>
            <input type="radio" name="title" id="mr" value="Mr"  @if(old('title') == 'Mr') checked @else checked @endif>
            <lable for='Ms'>Ms</label>
            <input type="radio" name="title" id="Ms" value="Ms" @if(old('title') == 'Ms') checked @endif>
            <lable for='X'>X</label>
            <input type="radio" name="title" id="X" value="X"@if(old('title') == 'X') checked @endif>
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <!-- Phone number-->
        <div class="mt-4">
            <x-input-label for="phone_nbr" :value="__('Phone number')" />
            <x-text-input id="phone_nbr" class="block mt-1 w-full" type="text" name="phone_nbr" :value="old('phone_nbr')" required autocomplete="username" 
            placeholder=" 0123 453 210" pattern="0[0-9]{3} [0-9]{3} [0-9]{3}"/>
            <x-input-error :messages="$errors->get('phone_nbr')" class="mt-2" />
        </div>

        <!--birth date -->
        <div class="mt-4">
            <x-input-label for="birth_date" :value="__('Birth date')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="block w-full mt-1" :value="old('birth_date')"
                required autofocus autocomplete="birth_date" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            :value="old('password')"
                            required autocomplete="new-password" />
            <!-- TODO zet de vereisten zichtbaar -->

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            :value="old('password_confirmation')"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!--for company -->
        <div class="mt-4">
            <x-input-label for="is_company" :value="__('Is Company')" />
            <input type="checkbox" id="is_company" name="is_company" @if(old('isCompany')) checked @endif>
            <x-input-error class="mt-2" :messages="$errors->get('is_company')" />
        </div>


        <!--company name -->
        <div class="mt-4" id="company_name_div">
            <x-input-label for="company_name" :value="__('Company name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="block w-full mt-1" :value="old('company_name')"
                autofocus autocomplete="company_name" />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>


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
        <!-- CH veranderen naar nationality -->
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
            <label for='house'>House</label>
            <input type="radio" name="type" id="house" value="house" checked>
            <label for='appartment'>Appartment</label>
            <input type="radio" name="type" id="appartment" value="appartment">
            <div id="business_div" style="display: inline;">
                <label for='business'>Business</label>
                <input type="radio" name="type" id="business" value="business">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
