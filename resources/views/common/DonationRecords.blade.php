@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"><span class="text-orange">Your Donation History</span></div>
                <div class="card-body">
                    @if($donations->isEmpty())
                        <p class="text-center">You haven't made any donations yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><p>Date</p></th>
                                        <th><p>Amount (RM)</p></th>
                                        <th><p>Donor Name</p></th>
                                        <th><p>Message</p></th>
                                        <th><p>Receipt</p></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ number_format($donation->amount, 2) }}</td>
                                            <td>{{ $donation->donor_name }}</td>
                                            <td>{{ $donation->message ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('donation.receipt', $donation->id) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   target="_blank">
                                                    <i class="fas fa-download"></i> Receipt
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $donations->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection