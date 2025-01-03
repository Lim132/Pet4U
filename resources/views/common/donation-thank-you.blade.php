@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header title1">Thank You for Your Donation!</div>
                <div class="card-body">
                    <div class="donation-details">
                        <h4 class="title1">Donation Details:</h4>
                        <p class="words2"><strong>Amount:</strong> RM {{ $donation->amount }}</p>
                        <p class="words2"><strong>Donor Name:</strong> {{ $donation->donor_name }}</p>
                        <p class="words2"><strong>Email:</strong> {{ $donation->donor_email }}</p>
                        @if($donation->message)
                            <p class="words2"><strong>Message:</strong> {{ $donation->message }}</p>
                        @endif
                        <p class="words2"><strong>Date:</strong> {{ $donation->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    @auth
                        <div class="text-center mt-4">
                            <p>Redirecting to your donation history in <span id="countdown">8</span> seconds...</p>
                        </div>
                    @else
                        <div class="text-center mt-4">
                            <p>Redirecting to homepage in <span id="countdown">5</span> seconds...</p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = {{ Auth::check() ? 8 : 5 }};
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(function() {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = '{{ Auth::check() ? route("donations.records") : route("showAdp") }}';
            }
        }, 1000);
    });
</script>
@endsection 