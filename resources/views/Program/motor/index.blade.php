<x-app-layout :title="'Program Motor - ' . $member->name">
    <div class="container mt-4">
        <h3 class="text-center ">Program Motor - {{ $member->name }}</h3>

        <!-- Button untuk memilih program motor -->
        <div class="d-flex justify-content-around my-4">
            <x-button route="{{ route('program_motor.show', ['member' => $member->id, 'program' => 1]) }}" color="primary" buttonText="Program 1" />
            <x-button route="{{ route('program_motor.show', ['member' => $member->id, 'program' => 2]) }}" color="primary" buttonText="Program 2" />
            <x-button route="{{ route('program_motor.show', ['member' => $member->id, 'program' => 3]) }}" color="primary" buttonText="Program 3" />
            <x-button route="{{ route('program_motor.lunas', $member->id) }}" color="primary" buttonText="History Program Motor" />
        </div>


        <!-- Bagian Konten Program Motor -->
        @yield('program_motor_content')
        <x-button route="{{ route('member.program_motor',$member->id) }}" color="primary mt-4" buttonText="Back" />
    </div>
    
   

</x-app-layout>
