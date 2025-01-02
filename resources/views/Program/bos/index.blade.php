<x-app-layout :title="'BOS Data'">
    <div class="container mt-4">
        <h3 class="text-center">Program BOS</h3>
        <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
        <br>

        <button data-toggle="modal" data-target="#create" class="btn btn-primary mb-3">Create New Data</button>

        <x-flash-message />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Member</th>
                    <th>T Date</th>
                    <th>T Type</th>
                    <th>T Value</th>
                    <th>Status</th>
                    <th>Added</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($member->Program_bos_datas as $program)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $program->member->name }}</td>
                        <td>{{ $program->transaction_date }}</td>
                        <td>{{ $program->transaction_type }}</td>
                        <td>{{ $program->transaction_value }}</td>
                        <td>{{ $program->status }}</td>
                        <td>{{ $program->admin->name }}</td>
                        <td>{{ $program->note }}</td>
                        <td>
                            <a href=""></a>
                            <a href="{{ route('program_bos.show', $program->id) }}"
                                class="btn btn-sm btn-info">Detail</a>
                            <x-button-modal color="danger" target="#DeleteModal-{{ $program->id }}"
                                buttonText="Delete" />

                        </td>
                    </tr>



                    <x-delete-modal id="{{ $program->id }}" title="Delete Program {{ $program->member->name }}"
                        name="{{ $program->name }}" action="{{ route('program_bos.destroy', $program->id) }}" />
                @endforeach
                <x-create-modal id="create" title="Create New BOS"
                    action="{{ route('program_bos.stored', $member->id) }}" buttonText="Create BOS">
                    <div class="form-group">
                        <label for="member_id">Member</label>
                        <select class="form-control" name="member_id" id="member_id">
                            <option value="{{ $member->id }}">{{ $member->name }}</option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="transaction_type">Transaction Type</label>
                        <select class="form-control" name="transaction_type" id="transaction_type">
                            <option value="pinjam">Pinjam</option>
                            <option value="setor">Setor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transaction_value">Transaction Value</label>
                        <input type="text" class="form-control" id="transaction_value" name="transaction_value"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="transaction_date">Transaction Date</label>
                        <input type="date" class="form-control" readonly value="{{ date('Y-m-d') }}"
                            id="transaction_date" name="transaction_date" required>
                    </div>
                    <div class="form-group">
                        <label for="admin_id">Added by Admin</label>
                        <input type="text" class="form-control" readonly value="{{ Auth::user()->id }}"
                            id="admin_id" name="admin_id" required>
                    </div>
                    <div class="form-group">
                        <label for="note">Note</label>
                        <input type="text" class="form-control" id="note" name="note" required>
                    </div>
                </x-create-modal>
            </tbody>
        </table>
        <x-button route="{{ route('member.show', $member->id) }}" color="primary mt-4" buttonText="Back" />

    </div>


</x-app-layout>
