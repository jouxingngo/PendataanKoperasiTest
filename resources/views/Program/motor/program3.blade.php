@extends('Program.motor.index')

@section('program_motor_content')
    <h4>Program 3</h4>
    <button data-toggle="modal" data-target="#create" class="btn btn-primary mb-3">Create Motor</button>
    {{-- Modal Tambah Program Motor --}}
    <x-create-modal id="create" title="Create New Motor" action="{{ route('program_motor.stored', $member->id) }}"
        buttonText="Create Motor">
        <div class="form-group">
            <label for="nomor_polisi">No Polisi</label>
            <input type="text" class="form-control" id="nomor_polisi" name="nomor_polisi" required>
        </div>
        <div class="form-group">
            <label for="member_id">Member</label>
            <select readonly class="form-control" name="member_id" id="member_id">
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="program_type">Program Type</label>
            <input type="text" readonly class="form-control" value="program_3" id="program_type" name="program_type"
                required>
        </div>

        <div class="form-group">
            <label for="total_installment">Total Installment</label>
            <input type="number" class="form-control" id="total_installment" name="total_installment" required>
        </div>
        <div class="form-group">
            <label for="cicilan">Cicilan</label>
            <input type="number" class="form-control" id="cicilan" name="cicilan" required>
        </div>
        <div class="form-group">
            <label for="transaction_date">Transaction Date</label>
            <input type="date" class="form-control" readonly value="{{ date('Y-m-d') }}" id="transaction_date"
                name="transaction_date" required>
        </div>
        <div class="form-group">
            <label for="admin_id">Added by Admin</label>
            <input type="text" class="form-control" readonly value="{{ Auth::user()->id }}" id="admin_id"
                name="admin_id" required>
        </div>
    </x-create-modal>

    {{-- Modal Tambah Program Motor --}}

    <x-flash-message />
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nomor Polisi</th>
                <th>Member</th>
                <th>Admin</th>
                <th>Status</th>
                <th>T Date</th>
                <th>Cicilan</th>
                <th>Total Biaya</th>
                <th>Paid Off</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($memberPrograms as $program)
                <tr>
                    <td>{{ $program->nomor_polisi }}</td>
                    <td>{{ $program->member->name }}</td>
                    <td>{{ $program->admin->name }}</td>
                    <td>{{ $program->status }}</td>
                    <td>{{ $program->transaction_date }}</td>
                    <td>{{ $program->cicilan }}</td>
                    <td>{{ $program->total_installment }}</td>
                    <td>{{ $program->paid_off ? 'Lunas' : 'Belum Lunas' }}</td>
                    <td>
                        <a href=""></a>
                        <a href="" class="btn btn-sm btn-info">Detail</a>
                        <x-button-modal color="danger" target="#DeleteModal-{{ $program->id }}" buttonText="Delete" />

                    </td>
                </tr>
                <x-delete-modal id="{{ $program->id }}" title="Delete Program {{ $program->member->name }}"
                    name="{{ $program->name }}" action="{{ route('program_motor.destroy', $program->id) }}" />
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        <strong>Belum ada program motor yang aktif untuk member ini.</strong><br>
                        <small>Silakan tambahkan program motor baru</small>
                    </td>
                </tr>
            @endforelse

            </tr>


        </tbody>
    </table>
    <h2 class="text-center">Setoran</h2>

    <table class="table table-bordered">
        <tbody>
            @forelse ($memberPrograms as $program)
                <tr>
                    <td colspan="5">
                        <h5>Setoran untuk Program Motor: {{ $program->nomor_polisi }}</h5>
                        {{-- Tombol Tambah Setoran --}}
                        <button data-toggle="modal" data-target="#AddSetorModal-{{ $program->id }}"
                            class="btn btn-primary my-3 ">Add Setoran</button>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Biaya Setoran</th>
                                    <th>Tanggal Setor</th>
                                    <th>Catatan</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($program->setors as $setor)
                                    <tr>
                                        <td>{{ $setor->transaction_value }}</td>
                                        <td>{{ $setor->transaction_date }}</td>
                                        <td>{{ $setor->note }}</td>
                                        <td>{{ $setor->admin->name }}</td>
                                        <td>
                                            <x-button-modal color="danger" target="#DeleteModal-setor{{ $setor->id }}" buttonText="Delete" />
                                        </td>
                                    </tr>
                                    <x-delete-modal id="setor{{ $setor->id }}"
                                        title="Delete Setoran {{ $setor->id }}"
                                        name="Setoran Nominal: {{ $setor->transaction_value }}"
                                        action="{{ route('setor_program_motor.destroy', $setor->id) }}" />
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <strong>Belum ada setoran untruk program motor
                                                {{ $program->nomor_polisi }}.</strong><br>
                                            <small>Silakan tambahkan setoran awal</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>



                        {{-- Modal Tambah Setoran --}}
                        <x-create-modal id="AddSetorModal-{{ $program->id }}"
                            title="Tambah Setoran untuk {{ $program->nomor_polisi }}"
                            action="{{ route('setor_program_motor.store', $program->id) }}" buttonText="Tambah Setoran">
                            <div class="form-group">
                                <label for="member_id">Member</label>
                                <select readonly class="form-control" name="member_id" id="member_id">
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="admin_id">Added By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->id }}" readonly
                                    id="admin_id" name="admin_id" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="transaction_date">Transaction Date</label>
                                <input type="date" class="form-control" readonly value="{{ date('Y-m-d') }}"
                                    id="transaction_date" name="transaction_date" required>
                            </div>
                            <div class="form-group">
                                <label for="transaction_value">Transaction Value</label>
                                <input type="number" class="form-control" id="transaction_value"
                                    name="transaction_value" required>
                            </div>
                            <div class="form-group">
                                <label for="note">Catatan (Opsional)</label>
                                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            </div>
                        </x-create-modal>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <strong>Belum ada program motor untuk member ini.</strong><br>
                        <small>Silakan tambahkan program motor untuk memulai setoran</small>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
