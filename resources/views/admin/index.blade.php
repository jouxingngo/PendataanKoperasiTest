<x-app-layout :title="'Admin Data'">
    <div class="container mt-4">
        <h3 class="text-center">Admin Management</h3>
        <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
        <br>

        {{-- @if (Auth::user()->role == 'sp') --}}
            <button data-toggle="modal" data-target="#create" class="btn btn-primary mb-3">Create New Admin</button>
        {{-- @endif --}}
        <x-flash-message />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->username }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>
                            {{-- @if (Auth::user()->role == 'sp') --}}
                                <button class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#EditModal-{{ $admin->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#DeleteModal-{{ $admin->id }}">Delete</button>
                            {{-- @else --}}
                                -
                            {{-- @endif --}}

                        </td>
                    </tr>


                   
                    <x-delete-modal
                    id="{{ $admin->id }}"
                    title="Delete Admin {{ $admin->name }}"
                    name="{{ $admin->name }}"
                    action="{{ route('admin.destroy', $admin->id) }}"
                    />
                    
                    <x-edit-modal
                    id="{{ $admin->id }}"
                    title="Edit Admin {{ $admin->name }}"
                    action="{{ route('admin.update', $admin->id) }}"
                    buttonText="Update Admin"
                    >
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" value="{{ $admin->name }}" id="name"
                            name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" value="{{ $admin->username }}" id="username"
                            name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" value="{{ $admin->password }}" id="password"
                            name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option @selected($admin->role == 'a') value="a">Admin</option>
                            <option @selected($admin->role == 'sp') value="sp">Super Admin</option>
                        </select>
                    </div>

                    </x-edit-modal>

                
                @endforeach
                <x-create-modal id="create" title="Create New Admin" action="{{ route('admin.store') }}"
                buttonText="Create Admin">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option selected value="a">Admin</option>
                        <option value="sp">Super Admin</option>
                    </select>
                </div>
            </x-create-modal>
            </tbody>
        </table>
    </div>


</x-app-layout>
