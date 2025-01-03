@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header title2">Make a Donation</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <form action="{{ route('donation.post') }}" method="POST" 
                          class="require-validation" 
                          data-cc-on-file="false" 
                          data-stripe-publishable-key="{{ config('services.stripe.key') }}" 
                          id="payment-form">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="words">Donation Amount (RM)</label>
                            <input type="number" name="amount" class="form-control" required min="1" step="0.01" placeholder="E.g. 10">
                        </div>

                        <div class="form-group mb-3">
                            <label class="words">Email</label>
                            <input type="email" name="donor_email" class="form-control" required 
                                    value="{{ Auth::user()->email ?? '' }}" placeholder="E.g. example@gmail.com">
                        </div>

                        <div class="form-group mb-3">
                            <label class="words">Name (Optional)</label>
                            @if(Auth::user())
                                <input type="text" name="donor_name" class="form-control" 
                                    value="{{ Auth::user()->firstName . ' ' . Auth::user()->lastName ?? '' }}" placeholder="E.g. John Doe">
                            @else
                                <input type="text" name="donor_name" class="form-control" placeholder="E.g. John Doe">
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label class="words">Message (Optional)</label>
                            <textarea name="message" class="form-control" rows="3" placeholder="You can write a message to the pet you are donating to."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label class="words">Name on Card</label>
                                            <input type="text" class="form-control" required placeholder="E.g. John Doe">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="words">Card Number</label>
                                            <input type="text" class="form-control card-number" required placeholder="E.g. 1234 5678 9012 3456">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="words">CVC</label>
                                                    <input type="text" class="form-control card-cvc" required placeholder="E.g. 123">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="words">Expiration Month</label>
                                                    <input type="text" class="form-control card-expiry-month" placeholder="MM (E.g. 01)" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label class="words">Expiration Year</label>
                                                    <input type="text" class="form-control card-expiry-year" placeholder="YYYY (E.g. 2025)" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Donate Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {
    var $form = $(".require-validation");
    
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]', 'input[type=file]', 'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        
        $errorMessage.addClass('hide');
        $('.has-error').removeClass('has-error');
        
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });
        
        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }
    });
    
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('payment-form');
    var button = document.querySelector('button[type="submit"]');
    var isProcessing = false;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (isProcessing) {
            console.log('Payment is already processing...');
            return false;
        }

        try {
            isProcessing = true;
            button.disabled = true;
            
            // 保存原始按钮文本
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            // 使用原有的 Stripe token 创建逻辑
            if (!$form.data('cc-on-file')) {
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, function(status, response) {
                    if (response.error) {
                        $('.error')
                            .removeClass('hide')
                            .find('.alert')
                            .text(response.error.message);
                        
                        // 重置按钮状态
                        resetButton(originalText);
                    } else {
                        var token = response['id'];
                        $form.find('input[type=text]').empty();
                        $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                        $form.get(0).submit();
                    }
                });
            }

        } catch (error) {
            console.error('Error:', error);
            resetButton(originalText);
        }
    });

    function resetButton(originalText) {
        isProcessing = false;
        button.disabled = false;
        button.innerHTML = originalText;
    }

    // 防止用户刷新或离开页面
    window.addEventListener('beforeunload', function(e) {
        if (isProcessing) {
            e.returnValue = 'Your donation is still processing. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // 如果用户离开页面，重置按钮状态
    window.addEventListener('pagehide', function() {
        if (button.disabled) {
            resetButton('Donate Now');
        }
    });
});
</script>
@endsection
