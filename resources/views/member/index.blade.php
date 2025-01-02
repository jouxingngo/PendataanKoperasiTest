<x-app-layout :title="'Member Data'">
    <div class="container mt-4">
        <h3 class="text-center">Member Management</h3>
        <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
        <br>

        <button data-toggle="modal" data-target="#create" class="btn btn-primary mb-3">Create New Member</button>

        <x-flash-message />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->admin->name }}</td>
                        <td>
                            <a href=""></a>
                                <a href="{{ route('member.show', $member->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <x-button-modal color="warning" target="#EditModal-{{ $member->id }}" buttonText="Edit" />
                                <x-button-modal color="danger" target="#DeleteModal-{{ $member->id }}" buttonText="Delete" />

                        </td>
                    </tr>



                    <x-delete-modal id="{{ $member->id }}" title="Delete member {{ $member->name }}"
                        name="{{ $member->name }}" action="{{ route('member.destroy', $member->id) }}" />

                    <x-edit-modal id="{{ $member->id }}" title="Edit member {{ $member->name }}"
                        action="{{ route('member.update', $member->id) }}" buttonText="Update member">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" value="{{ $member->name }}" id="name"
                                name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="admin_id">Added by Admin</label>
                            <input type="text" class="form-control" disabled value="{{ $member->admin->name }}" id="admin_id"
                                name="admin_id" required>
                        </div>
                    </x-edit-modal>

         
                @endforeach
                <x-create-modal id="create" title="Create New Member" action="{{ route('member.store') }}"
                buttonText="Create Member">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Added by Admin</label>
                    <input type="text" class="form-control"  value="{{  Auth::user()->id }}" readonly id="admin_id" name="admin_id" required>
                </div>
            </x-create-modal>
            </tbody>
        </table>
    </div>


</x-app-layout>
