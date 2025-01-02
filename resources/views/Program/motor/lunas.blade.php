<x-app-layout :title="'Data Program Motor Lunas'">
    <div class="container mt-4">
        <h3 class="text-center">Program Motor Data "LUNAS"</h3>
        <div class="d-flex justify-content-between">
            <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
            <x-button route="{{ route('transaction.setor') }}" color="success" buttonText="Setoran" />
        </div>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomor Polisi</th>
                    <th>Member</th>
                    <th>Admin</th>
                    <th>Program Type</th>
                    <th>T Date</th>
                    <th>Total Installment</th>
                    <th>Paid Off</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($member->LunasProgramMotors as $program)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $program->nomor_polisi }}</td>
                        <td>{{ $program->member->name }}</td>
                        <td>{{ $program->admin->name }}</td>
                        <td>{{ $program->program_type }}</td>
                        <td>{{ $program->transaction_date }}</td>
                        <td>Rp.{{ number_format($program->total_installment, 2, ',', '.') }}</td>
                        <td>{{ $program->paid_off ? 'Lunas' : 'Belum Lunas' }}</td>
                        <td>
                            <a href="{{ route('program_motor_lunas.show', ['member' => $member->id, 'program' => $program->id]) }}"
                                class="btn btn-sm btn-info">Detail</a>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <x-button route="{{ route('member.show',$member->id) }}" color="primary mt-4" buttonText="Back" />
    </div>


</x-app-layout>
