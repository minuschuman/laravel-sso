<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 flex justify-between items-center">
                    <h2
                        class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-slate-200 py-4 sm:inline-block flex">
                        Edit team <span class="italic">{{ $team->name }}</span>
                    </h2>
                    <a href="{{ route('teams.index') }}"
                        class="px-4 py-2 text-white mr-4 bg-blue-600">{{ __('Back to Teams') }}</a>
                    @if ($errors->any())
                        <ul class="mt-3 list-none list-inside text-sm text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="w-full px-6 py-4 bg-white overflow-hidden">
                    <form method="post" action="{{ route('teams.update', $team) }}">
                        @csrf
                        @method('PUT')

                        <div class="py-2">
                            <x-label for="name" :value="__('Name')" />
                            <x-input type="text" id="name" name="name" class="block w-full"
                                value="{{ old('name', $team->name) }}" required />
                            @error('name')
                                <span class="text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type='submit'
                                class='inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150'>
                                {{ __('Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
</x-app-layout>>
