<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="post" action="{{ route('teams.members.store', $team->id) }}" id="add-users-form">
                    @csrf
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach ($users as $user)
                                <tr class="border-b border-gray-200">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->id == $team->owner_id)
                                            <span class="px-2 py-1 text-green-700 bg-green-200 rounded-xl">Owner</span>
                                        @elseif($user->role != null)
                                            <span
                                                class="px-2 py-1 text-indigo-700 bg-indigo-200 rounded-xl">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($user->id != $team->owner_id)
                                            @if ($user->role != 'member')
                                                <label class="mr-4">
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                                        class="user-checkbox mr-2">{{ _('member') }}
                                                </label>
                                            @endif
                                            @if ($user->role != 'admin')
                                                <label>
                                                    <input type="checkbox" name="member_ids[]"
                                                        value="{{ $user->id }}"
                                                        class="member-checkbox mr-2">{{ _('admin') }}
                                                </label>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="">
                        <input type="submit" value="Add Member" class="px-4 py-2 bg-blue-500 text-white rounded mt-4">
                    </div>
                </form>

                <div class="py-8">
                    {{ $users->links() }}
                </div>
                <script>
                    document.getElementById('add-users-form').addEventListener('submit', function(event) {
                        event.preventDefault();
                        var userCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                        var userIds = [];
                        userCheckboxes.forEach(function(checkbox) {
                            userIds.push(checkbox.value);
                        });

                        var memberCheckboxes = document.querySelectorAll('.member-checkbox:checked');
                        var memberIds = [];
                        memberCheckboxes.forEach(function(checkbox) {
                            memberIds.push(checkbox.value);
                        });

                        var formData = new FormData();
                        formData.append('user_ids', JSON.stringify(userIds));
                        formData.append('member_ids', JSON.stringify(memberIds));

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', this.action);
                        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));
                        xhr.onload = function() {
                            // console.log(xhr.responseText);
                            if (xhr.status === 200) {
                                alert('Users have been added to the team successfully.');
                                window.location.href = "{{ route('teams.members.index', $team->id) }}";
                            } else {
                                alert('An error occurred while adding users to the team.');
                            }
                        };
                        xhr.onerror = function() {
                            alert('An error occurred while adding users to the team.');
                        };
                        xhr.send(formData);
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
