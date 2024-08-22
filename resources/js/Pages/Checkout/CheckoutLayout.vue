<template>  
<AppHead :title="title" />
<div class="container page-container">
   <PageTitle :title="title">
   <Link v-if="is_payment_gateway_page" :href="getUrl()" class="btn btn-light btn-sm"><i class="fas fa-chevron-left"></i> {{ __('Back to payment options') }} </Link>
   </PageTitle>
   <div class="row">     
      <div class="col-md-5 d-none d-lg-block">
        <div class="p-5">
           <img :src="asset('images/payment.svg')" class="img-fluid mx-auto">
        </div>
     </div>
      <div class="offset-md-1 col-md-6">     
         <div class="card">
            <div class="card-body">
               <div class="d-flex justify-content-between">
                  <h4 class="h4">{{ __('Total') }}</h4>
                  <div class="h4">{{ formatMoney(data.total) }}</div>
               </div>               
              <slot/>
            </div>
         </div>         
      </div>
   </div>
</div>
</template>

<script>

export default {
   props: ['data', 'title', 'is_payment_gateway_page'],
   methods: {
      getUrl(){     
         let query_param = (new URLSearchParams(window.location.search)).toString();
         return route('choose_payment_method') + '?' + query_param;
      }
   }
};
</script>