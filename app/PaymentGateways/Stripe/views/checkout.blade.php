@extends(config('paymentgateways.front_view_layout'))
@section('payment_process_content')
<form id='payment-form' style="display: none;">
  <div class="mb-3">
    <label for="card-element">{{ __('Credit or Debit Card') }}</label>
    <div id="card-element"></div>
    <div id="card-errors" class="invalid-feedback d-block"></div>
  </div>
  <button type="submit" class="btn btn-success btn-lg btn-block confirm-button" disabled><i class="fas fa-shopping-cart"></i> {{ __('Confirm Payment') }}</button>
</form>
<div class="text-center" id="loading">{{ __('Please wait ') }}...</div>
<div id="publishable_key" data-publishablekey="{{ $data['publishable_key'] }}"></div>
<div id="process_url" data-processurl="{{ route('stripe_process') }}"></div>
@endsection

@section('payment_process_content_js')

<script src="https://js.stripe.com/v3/"></script>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {

    var publishableKey = document.getElementById('publishable_key').dataset.publishablekey;
    var process_url = document.getElementById('process_url').dataset.processurl;

    document.getElementById('payment-form').style.display = "block";
    document.getElementById('loading').style.display = "none";

    var stripe = Stripe(publishableKey);

    var elements = stripe.elements();

    // Set up Stripe.js and Elements to use in checkout form
    var style = {
      base: {
        color: "#32325d",
        fontFamily: '"Nunito", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4"
        }
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a"
      },
    };

    var cardElement = elements.create('card', {
      hidePostalCode: true,
      style: style
    });
    cardElement.mount('#card-element');

    disableConfirmButton();

    displayError = document.getElementById('card-errors');

    // Handle real-time validation errors from the card Element.
    cardElement.on('change', function(event) {

      if (event.complete) {
        // enable payment button
        enableConfirmButton();
      } else if (event.error) {
        // show validation to customer
        displayError.textContent = event.error.message;
        disableConfirmButton();
      } else {
        displayError.textContent = '';
        enableConfirmButton();
      }

    });

    // Step: 1 Handle the Button Click
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();
      // Disable the confirm button    
      disableConfirmButton();

      stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
      }).then(stripePaymentMethodHandler);
    });

    // Step: 1 attempt to send paymentMethod.id to server
    function stripePaymentMethodHandler(result) {
     
      if (result.error) {
        if(result.error.hasOwnProperty('message'))
        {
          showError(result.error.message);
        }
        else
        {
          showError(result.error);
        }  
        
        enableConfirmButton();
      } else {
        // Otherwise send paymentMethod.id to your server
        $("#loading").show();
        axios.post(process_url, {
            payment_method_id: result.paymentMethod.id,
          })
          .then(function(response) {
            $("#loading").hide();
            handleServerResponse(response.data);
          })
          .catch(function(error) {
            $("#loading").hide();
            enableConfirmButton();
          });


      }
    }


    function handleServerResponse(response) {
      if (response.error) {
        // Show error from server on payment form
        showError(response.error);
        enableConfirmButton();

      } else if (response.requires_action) {
        // Use Stripe.js to handle required card action
        disableConfirmButton();
        stripe.handleCardAction(
          response.payment_intent_client_secret
        ).then(handleStripeJsResult);
      } else {
        // Show success message
          if(response.success)
          {
            window.location.href = response.redirect_url;
          }
      }
    }

    function handleStripeJsResult(result) {
      if (result.error) {
        // Show error in payment form
        showError(result.error.message);
        enableConfirmButton();
      } else {
        // The card action has been handled
        // The PaymentIntent can be confirmed again on the server
        disableConfirmButton();
        axios.post(process_url, {
            payment_intent_id: result.paymentIntent.id
          })
          .then(function(response) {
            handleServerResponse(response.data);
          })
          .catch(function(error) {          
            showError('Could not process your payment. Please try again.');
            enableConfirmButton();
          });



      }
    }

    function disableConfirmButton() {
      document.getElementsByClassName('confirm-button')[0].disabled = true;
    }

    function enableConfirmButton() {
      document.getElementsByClassName('confirm-button')[0].disabled = false;
    }

    function showError(message) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      });

      swalWithBootstrapButtons.fire({
        text: message
      });
    }


  }); // End of script
</script>
@endsection