<x-app-layout :title="'Data Setoran Program Motor'">
    <div class="container mt-4">
        <h3 class="text-center">Data Setoran Program Mobil <b>{{ $programMobil->member->id }}</b></h3>
        <div class="d-flex justify-content-between">
            <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
        </div>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program Motor Id</th>
                    <th>T Date</th>
                    <th>Member</th>
                    <th>Admin</th>
                    <th>T Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programMobil->setors as $program)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $program->program_mobil_id }}</td>
                        <td>{{ $program->transaction_date }}</td>
                        <td>{{ $program->member->name }}</td>
                        <td>{{ $program->admin->name }}</td>
                        <td>{{ $program->transaction_value }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <x-button route="{{ route('program_mobil.lunas', $programMobil->member_id) }}" color="primary mt-4" buttonText="Back" />

    </div>


</x-app-layout>
