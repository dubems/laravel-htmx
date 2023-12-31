<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST"
              hx-post="{{route('chirps.store')}}"
              hx-swap="afterbegin"
              hx-target="#chirps"
              hx-on="htmx:afterRequest: if(event.detail.successful) this.reset();">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2"/>
            <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
        </form>

        <div id="chirps" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($chirps as $chirp)

                <x-chirps.single :chirp="$chirp"/>
            @endforeach

            @if($chirps->nextPageUrl())
                <div
                    hx-get="{{ $chirps->nextPageUrl() }}"
                    hx-select="#chirps>div"
                    hx-swap="outerHTML"
                    hx-trigger="intersect"
                >
                    Loading more...
                </div>
            @endif
        </div>

        <noscrip>
            <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
                {{ $chirps->links() }}
            </div>
        </noscrip>

    </div>

</x-app-layout>
