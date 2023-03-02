<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col mt-8">
                        @can('user create')
                            <div class="d-print-none with-border mb-8">
                                <a href="{{ route('teams.create') }}"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    {{ __('Create Team') }}
                                </a>
                            </div>
                        @endcan
                        <div class="py-2">
                            @if (session()->has('message'))
                                <div class="mb-8 text-green-400 font-bold">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                            <div class="min-w-full border-b border-gray-200 shadow">
                                <form method="GET" action="{{ route('teams.index') }}">
                                    <div class="py-2 flex">
                                        <div class="overflow-hidden flex pl-4">
                                            <input type="search" name="search"
                                                value="{{ request()->input('search') }}"
                                                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                placeholder="Search">
                                            <button type='submit'
                                                class='ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150'>
                                                {{ __('Search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <table class="border-collapse table-auto w-full text-sm">
                                    <thead>
                                        <tr>
                                            {{-- class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b"> --}}
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                {{ __('Name') }}
                                            </th>
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                {{ __('Role') }}
                                            </th>
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                {{ __('Status') }}
                                            </th>
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                {{ __('Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y">
                                        @foreach ($teams as $team)
                                            <tr class="text-gray-700">
                                                <td class="px-4 py-3 text-sm">{{ $team->name }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    @if (auth()->user()->isOwnerOfTeam($team))
                                                        <span
                                                            class="px-2 py-1 text-green-700 bg-green-200 rounded-xl">Owner</span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-indigo-700 bg-indigo-200 rounded-xl">Member</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    @if (is_null(auth()->user()->currentTeam) ||
                                                            auth()->user()->currentTeam->getKey() !== $team->getKey())
                                                        <a href="{{ route('teams.switch', $team) }}"
                                                            class="px-2 py-1 mr-2 text-indigo-700 bg-indigo-200 rounded-xl hover:bg-indigo-300 hover:text-indigo-800">
                                                            Switch
                                                        </a>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-green-700 bg-green-200 rounded-xl">Current
                                                            team</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('teams.members.index', $team->id) }}"
                                                        class="inline-flex px-2 py-1 text-sm font-medium text-white bg-purple-600 rounded-lg border border-transparent active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring">
                                                        Members
                                                    </a>

                                                    @if (auth()->user()->isOwnerOfTeam($team))
                                                        <a href="{{ route('teams.edit', $team) }}"
                                                            class="inline-flex px-2 py-1 text-sm font-medium text-gray-600 bg-gray-200 rounded-lg border border-transparent active:bg-gray-600 hover:bg-gray-300 focus:outline-none focus:ring">
                                                            Edit
                                                        </a>

                                                        <form class="inline-block"
                                                            action="{{ route('teams.destroy', $team) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="inline-flex px-2 py-1 text-sm font-medium text-red-600 bg-red-200 rounded-lg border border-transparent active:bg-red-600 hover:bg-red-300 focus:outline-none focus:ring">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="py-8">
                                {{ $teams->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
