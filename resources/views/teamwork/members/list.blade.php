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
                    <div class="px-6 flex justify-between items-center">
                        <h2
                            class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight dark:text-slate-200 py-4 sm:inline-block flex">
                            Members of team <span class="tracking-wide">{{ $team->name }}</span></h2>
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
                    <div class="flex flex-col mt-8">
                        <div class="py-2">
                            @if (session()->has('message'))
                                <div class="mb-8 text-green-400 font-bold">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                            <div class="min-w-full border-b border-gray-200 shadow">
                                <div class="flex justify-between">
                                    <div>
                                        @can('user create')
                                            <div class="py-2 flex">
                                                <div class="overflow-hidden flex pl-4">
                                                    <a href="{{ route('teams.user.add', ['team' => $team->id]) }}"
                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Add Member') }}</a>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                    <form method="GET" class="flex-end"
                                        action="{{ route('teams.members.index', ['team' => $team->id]) }}">
                                        <div class="py-2 flex">
                                            <div class="overflow-hidden flex pr-4">
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
                                </div>

                                <table class="border-collapse table-auto w-full text-sm">
                                    <thead>
                                        <tr
                                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                Name</th>
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                Role</th>
                                            <th
                                                class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y">
                                        @foreach ($users as $user)
                                            <tr class="text-gray-700">
                                                <td
                                                    class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                                    {{ $user->name }}
                                                </td>
                                                <td
                                                    class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                                    {{ $user->role }}
                                                </td>
                                                <td
                                                    class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                                    @if (auth()->user()->isOwnerOfTeam($team))
                                                        @if (auth()->user()->getKey() !== $user->getKey())
                                                            <form class="inline-block"
                                                                action="{{ route('teams.members.destroy', [$team, $user]) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')

                                                                <x-button>
                                                                    Remove
                                                                </x-button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="py-8">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="w-full px-6 py-4 bg-white overflow-hidden">
                @if (auth()->user()->isOwnerOfTeam($team))

                    <h3 class="mb-3 text-lg font-semibold tracking-wide">Pending invitations</h3>

                    <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
                        <div class="overflow-x-auto w-full">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                                        <th
                                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                            E-Mail</th>
                                        <th
                                            class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm text-grey-dark border-b border-grey-light text-left">
                                            Action</th>
                                    </tr>
                                </thead>
                                @foreach ($team->invites as $invite)
                                    <tr class="text-gray-700">
                                        <td
                                            class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            {{ $invite->email }}</td>
                                        <td
                                            class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            <a href="{{ route('teams.members.resend_invite', $invite) }}"
                                                class="inline-flex px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg border border-transparent active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring">
                                                Resend invite
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <h3 class="mb-3 text-lg font-semibold tracking-wide">Invite to team
                        "{{ $team->name }}"
                    </h3>

                    <form class="form-horizontal" method="post" action="{{ route('teams.members.invite', $team) }}">
                        @csrf

                        <div>
                            <x-label for="email" :value="__('Email')" />
                            <x-input type="text" id="email" name="email" class="block w-full"
                                value="{{ old('email') }}" required />
                            @error('email')
                                <span class="text-xs text-red-600 dark:text-red-400">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <x-button class="block">
                                Invite to Team
                            </x-button>
                        </div>
                    </form>

                @endif
            </div>
        </div>
    </div> --}}
</x-app-layout>
