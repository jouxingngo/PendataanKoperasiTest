<x-app-layout :title="'Transaction Detail'">
    <div class="containe p-4">

        <div class="card border-info">
            <div class="card-header text-white bg-info">
                <strong>Transaction #{{ $transaction->id }}</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <h4 class="text-center">Transaction Receipt</h4>
                    </div>

                    <div class="col-12">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Program Name:</strong></td>
                                    <td>{{ $transaction->program_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Program ID:</strong></td>
                                    <td>{{ $transaction->program_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Member:</strong></td>
                                    <td>{{ $transaction->program->member->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Admin:</strong></td>
                                    <td>{{ $transaction->program->admin->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction Date:</strong></td>
                                    <td>{{ $transaction->program->transaction_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction Type:</strong></td>
                                    <td>{{ $transaction->program->transaction_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction Value:</strong></td>
                                    <td>Rp{{ number_format($transaction->program->transaction_value, 0, ',', '.') }}</td>

                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($transaction->program->status == 'belum_acc')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-success">Approved</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Note:</strong></td>
                                    <td>{{ $transaction->program->note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center mt-4">
        <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Back to Transactions</a>
    </div>
</x-app-layout>
