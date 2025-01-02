<x-app-layout :title="'Transaction Data Setoran'">
    <div class="container mt-4">
        <h3 class="text-center">Transaction Data Setoran</h3>
        <div class="d-flex justify-content-between">
            <h4>Welcome {{ Auth::user()->name }}, role: {{ Auth::user()->role == 'sp' ? 'Super Admin' : 'Admin' }}</h4>
            <x-button route="{{ route('transaction.index')  }}" color="warning" buttonText="Pinjaman" />
        </div>
        <br>
        <x-flash-message />
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program Name</th>
                    <th>Member</th>
                    <th>Admin</th>
                    <th>T Date</th>
                    <th>T Type</th>
                    <th>T Value</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->program_name }}</td>
                        <td>{{ $transaction->program->member->name }}</td>
                        <td>{{ $transaction->program->admin->name }}</td>
                        <td>{{ $transaction->program->transaction_date }}</td>
                        <td>{{ $transaction->program->transaction_type }}</td>
                        <td>{{ $transaction->program->transaction_value }}</td>
                        <td>{{ $transaction->program->note }}</td>
                        <td>
                            <a href="{{ route('transaction.show', $transaction->id) }}"
                                class="btn btn-sm btn-info">Detail</a>

                        </td>
                    </tr>
                    {{-- Acc Modal --}}
                    <div class="modal fade" id="acc-{{ $transaction->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="acc-{{ $transaction->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="acc-{{ $transaction->id }}">
                                        ACC {{ $transaction->program_name }}?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Acc Pinjaman senilai <strong>Rp.{{ number_format($transaction->program->transaction_value, 2, ',', '.') }}
                                        oleh {{ $transaction->program->member->name }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Cancel</button>
                                    <form action="{{ route('transaction.acc', $transaction->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-info">Yes, Acc</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Acc Modal --}}

                @endforeach

            </tbody>
        </table>
    </div>


</x-app-layout>
