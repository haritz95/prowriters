@extends('layouts.app')
@section('title', __('Pay with') . ' ' . $data['gateway_name'])
@section('content')
    <div class="container page-container">
        <div class="row">
            <div class="col-md-12">
                <h3>{{ __('Pay with') }} {{ $data['gateway_name'] }}</h3>
                <hr>
            </div>
            <div class="offset-md-3 col-md-6">
                @include('checkout.back_to_payment_options')
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="h4">{{ __('Total') }}</h4>
                            <div class="h4">{{ format_money($data['total']) }}</div>
                        </div>
                        <hr>
                        <form id="payment-form" method="POST" action="{{ route('nowpayments_process') }}">
                            @csrf
                            <div class="form-group">
                                <label for="crypto_currency">{{ __('Choose Cryptocurrency') }}</label>
                                <select name="crypto_currency" id="crypto_currency" class="form-control">
                                    @foreach ($data['cryptocurrencies'] as $crypto)
                                        <option value="{{ $crypto['currency'] }}">{{ $crypto['name'] }}
                                            ({{ $crypto['currency'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="order_id" name="order_id" value="{{ $data['order_id'] }}" />
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Pay Now') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            let form = document.querySelector('#payment-form');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                let selectedCurrency = form.querySelector('#crypto_currency').value;
                let orderId = form.querySelector('#order_id').value;

                console.log('Selected Currency:', selectedCurrency);
                console.log('Order ID:', orderId);

                if (selectedCurrency) {
                    $('#loading').show();
                    axios.post("{{ route('nowpayments_process') }}", {
                            order_id: orderId,
                            crypto_currency: selectedCurrency
                        })
                        .then(function(response) {
                            console.log('Response:', response.data);
                            if (response.data && response.data.invoice_url) {
                                window.location.href = response.data.invoice_url;
                            } else {
                                showError("Failed to create payment. Please try again.");
                                $('#loading').hide();
                            }
                        })
                        .catch(function(error) {
                            console.error('Error:', error);
                            showError("Error processing payment. Please try again.");
                            $('#loading').hide();
                        });
                } else {
                    showError("No cryptocurrency selected.");
                }

                function showError(message) {
                    alert(message);
                }
            });
        });
    </script>
@endpush
