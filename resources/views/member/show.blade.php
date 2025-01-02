<!-- resources/views/members/show.blade.php -->
<x-app-layout :title="'Show Member - ' . $member->name">
    <div class="container mt-4">
        <h3 class="text-center">Remaining Debt</h3>

        <!-- Nama Member -->
        <h4 class="text-center">Name: {{ $member->name }}</h4>

        <!-- Tabel Data Remaining Debt -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Member Id</th>
                    <th>Member Name</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                   
                </tr>
            </tbody>
        </table>
        
        <!-- Buttons for Program BOS and BOP -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title">Program BOS</h5>
                        <p class="card-text">Remaining: Rp.{{ number_format($member->Debt->program_bos, 0, ',', '.') }}</p>
                        <a href="{{ route('member.program_bos',$member->id) }}" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title">Program BOP</h5>
                        <p class="card-text">Remaining: Rp.{{ number_format($member->Debt->program_bop, 0, ',', '.') }}</p>
                        <a href="{{ route('member.program_bop', $member->id ) }}" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title">Program Motor</h5>
                        <p class="card-text">Remaining: Rp.{{ number_format($member->Debt->program_motor, 0, ',', '.') }}</p>
                        <a href="{{ route('member.program_motor', $member->id ) }}" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title">Program Mobil</h5>
                        <p class="card-text">Remaining: Rp.{{ number_format($member->Debt->program_mobil, 0, ',', '.') }}</p>
                        <a href="{{ route('member.program_mobil', $member->id ) }}" class="btn btn-info">View Details</a>
                    </div>
                </div>
            </div>
      
        </div>
        <x-button route="{{ route('member.index') }}" color="primary mt-4" buttonText="Back" />
    </div>
</x-app-layout>
