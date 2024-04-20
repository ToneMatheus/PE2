<!-- LOOK iManage page her zetten gedeelte van de addressen -->

<!-- CH enkel billing address aan passen. en niet alles.-->
<!-- CH adres niet kunnen aanpassen -->
<section>
    @php
        $aantal = 0;
    @endphp

    @foreach ($addresses as $key => $address)
    <div class="{{ $key > 0 ? 'mt-20' : '' }}">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{$key + 1 }}) {{ __('Address information') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                <!-- CH Pas de tekst aan -->
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
                <x-input-label for="street{{$key}}" :value="__('Street')" />
                <p>{{ $address->street }}</p>
            </div>


            <div>
                <x-input-label for="number" :value="__('Number')" />
                <p>{{ $address->number }}</p>
            </div>

            <div>
                <x-input-label for="type" :value="__('Type')" />
                <p>{{ $address->type }}</p>
            </div>



            <div>
                <x-input-label for="box" :value="__('Box')" />
                <p>{{ $address->box }}</p>
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal Code')" />
                <p>{{ $address->postal_code }}</p>
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <p>{{ $address->city }}</p>
            </div>

            <div>
                <x-input-label for="province" :value="__('Province')" />
                <p>{{ $address->province }}</p>
            </div>

            <div>
                <x-input-label for="country" :value="__('Country')" />
                <p>{{ $address->country }}</p>
            </div>

            <div>
                <x-input-label for="is_billing_address" :value="__('Is billing address')" />
                <input type="checkbox" id="is_billing_address" name="is_billing_address" disabled = true @if($address->is_billing_address == 1) checked @endif>
            </div>
        </div>

        <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="{{ $address->id }}">
        @php
            $aantal ++;
        @endphp
        
        @endforeach

        <input type="hidden" id="Aantal" name="Aantal" value="{{ $aantal }}">
    </form>
</section>
