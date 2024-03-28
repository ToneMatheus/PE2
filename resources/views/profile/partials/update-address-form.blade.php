<!-- LOOK iManage page her zetten gedeelte van de addressen -->
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Address information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your address information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- LOOK validatie en opslagen controleren. -->
    <form method="post" action="{{ route('profile.update.address') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="street" :value="__('Street')" />
            <x-text-input id="street" name="street" type="text" class="block w-full mt-1" :value="old('street', $address->street)"
                required autofocus autocomplete="street" />
            <x-input-error class="mt-2" :messages="$errors->get('street')" />
        </div>

        <div>
            <x-input-label for="number" :value="__('Number')" />
            <x-text-input id="number" name="number" type="text" class="block w-full mt-1" :value="old('number', $address->number)"
                required autofocus autocomplete="number" />
            <x-input-error class="mt-2" :messages="$errors->get('number')" />
        </div>

        <div>
            <x-input-label for="type" :value="__('Type')" />
                <!-- CH pas de colom van de database aan voor de calling -->
            <lable for='house'>House</label>
            <input type="radio" name="type" id="house" value="house" @if($address->type == 'house') checked @elseif($address->type != 'appartment' && $address->type != 'business') checked @endif>
            <lable for='appartment'>Appartment</label>
            <input type="radio" name="type" id="appartment" value="appartment" @if($address->type == 'appartment') checked @endif>
            <lable for='business'>Business</label>
            <input type="radio" name="type" id="business" value="business" @if($address->type == 'business') checked @endif>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />
        </div>



        <div>
            <x-input-label for="box" :value="__('Box')" />
            <x-text-input id="box" name="box" type="text" class="block w-full mt-1" :value="old('box', $address->box)"
                required autocomplete="box" />
            <x-input-error class="mt-2" :messages="$errors->get('box')" />
        </div>

        <div>
            <x-input-label for="postal_code" :value="__('Postal Code')" />
            <x-text-input id="postal_code" name="postal_code" type="text" class="block w-full mt-1"
                :value="old('postal_code', $address->postal_code)" required autofocus autocomplete="postal_code" />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="block w-full mt-1" :value="old('city', $address->city)"
                required autofocus autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <x-input-label for="province" :value="__('Province')" />
            <x-text-input id="province" name="province" type="text" class="block w-full mt-1" :value="old('province', $address->province)"
                required autofocus autocomplete="province" />
            <x-input-error class="mt-2" :messages="$errors->get('province')" />
        </div>

        <div>
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" name="country" type="text" class="block w-full mt-1" :value="old('country', $address->country)"
                required autofocus autocomplete="country" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div>
            <x-input-label for="is_billing_address" :value="__('Is billing address')" />
            <x-text-input id="is_billing_address" name="is_billing_address" type="text" class="block w-full mt-1" 
            :value="old('is_billing_address', $address->is_billing_address)" required autofocus autocomplete="is_billing_address" />
            <x-input-error class="mt-2" :messages="$errors->get('is_billing_address')" />
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
