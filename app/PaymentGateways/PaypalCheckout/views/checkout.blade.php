@extends(config('paymentgateways.front_view_layout'))
@section('payment_process_content')
<div id="paypal-button-container"></div>
<div class="text-center" id="loading">{{ __('Please wait ') }} ...</div>   
<form id="payment-form" method="POST" action="{{ route('paypal_checkout_process') }}">
  @csrf
  <input id="order_id" name="order_id" type="hidden" />
</form>
@endsection

@section('payment_process_content_js')
<script src="https://www.paypal.com/sdk/js?client-id={{ $data['client_id'] }}&currency={{ $data['currency'] }}"></script>

<script>
 document.addEventListener("DOMContentLoaded", function(event) {

if(window.hasOwnProperty("paypal")){
  paypal.Buttons({
    createOrder: function (data, actions) {

       return axios.post("{{ route('paypal_checkout_generate_token') }}")
            .then(function(response) {
              if(response.data.status == 'success')
              {
                return response.data.id;
              }            
            return null;
          });
    },
    onApprove: function(data, actions) {
      if(data.orderID)
      {  
         $('#paypal-button-container').hide();
         $('#loading').show();
         var form = document.querySelector('#payment-form');
         document.querySelector('#order_id').value = data.orderID;
         form.submit();
      }       

    },
    onDisplay:function(){
      $("#loading").hide();
    },  
    onError: function (err) {      
      $('#paypal-button-container').show();
      $('#loading').hide();
      showError("Something went wrong, please try again later, or use a different payment method");
    }
  }).render('#paypal-button-container');
  
} else {

   showError("Something went wrong, please try again later, or use a different payment method");
   $('#loading').hide();
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