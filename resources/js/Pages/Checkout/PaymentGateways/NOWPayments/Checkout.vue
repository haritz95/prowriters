<template>
    <CheckoutLayout :data="data" :title="__('Pay with') + ' ' + data.gateway_name" :is_payment_gateway_page="true">
        <form id="payment-form" @submit.prevent="onSubmit">
            <!-- Monto del pago -->
            <div class="mb-3">
                <label class="form-label" for="payment-amount">{{ __("Amount") }}</label>
                <input type="number" class="form-control" id="payment-amount" v-model="paymentAmount"
                    :disabled="loading" />
            </div>

            <!-- Descripción del pago -->
            <div class="mb-3">
                <label class="form-label" for="payment-description">{{ __("Description") }}</label>
                <input type="text" class="form-control" id="payment-description" v-model="paymentDescription"
                    :disabled="loading" />
            </div>

            <!-- Selección de criptomoneda -->
            <div class="mb-3">
                <label class="form-label" for="crypto-currency">{{ __("Choose Cryptocurrency") }}</label>
                <select class="form-control" v-model="selectedCurrency" :disabled="loading">
                    <option v-for="crypto in cryptocurrencies" :key="crypto.currency" :value="crypto.currency">
                        {{ crypto.name }} ({{ crypto.currency }})
                    </option>
                </select>
            </div>

            <!-- Botón de confirmación -->
            <button type="submit" class="btn btn-success btn-block" :disabled="isButtonDisabled">
                <i class="fas fa-shopping-cart"></i> {{ __("Confirm Payment") }}
            </button>
        </form>
        <div class="text-center" v-if="loading">{{ __("Please wait ") }}...</div>
    </CheckoutLayout>
</template>

<script>
import CheckoutLayout from "../../CheckoutLayout.vue";

export default {
    props: ["data"],
    components: {
        CheckoutLayout,
    },
    data() {
        return {
            paymentAmount: this.data.total,
            paymentDescription: `Payment for Order #${this.data.order_id}`,
            selectedCurrency: this.data.selected_currency || '',  // Variable para almacenar la criptomoneda seleccionada
            cryptocurrencies: this.data.cryptocurrencies || [],  // Asegurarse que es un array
            isButtonDisabled: false,
            loading: false,
        };
    },
    methods: {
        onSubmit() {
            this.isButtonDisabled = true;
            this.loading = true;
            axios.post(this.data.urls.nowpayments_process, {
                amount: this.paymentAmount,
                currency: this.selectedCurrency,
                order_id: this.data.order_id,
                order_description: this.paymentDescription,
            })
                .then(response => {
                    console.log('Full Response:', response);
                    if (response.data && response.data.invoice_url) {
                        window.location.href = response.data.invoice_url;
                    } else {
                        this.showError("Unexpected response format. Please try again.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = "Error processing payment. Please try again.";
                    if (error.response) {
                        if (error.response.data && typeof error.response.data === 'string') {
                            // If the response is in HTML or plain text
                            errorMessage = `Unexpected response format: ${error.response.status}`;
                        } else {
                            errorMessage = `Error ${error.response.status}: ${error.response.statusText}`;
                        }
                    }
                    this.showError(errorMessage);
                })
                .finally(() => {
                    this.isButtonDisabled = false;
                    this.loading = false;
                });
        },
        showError(message) {
            alert(message);
        },
    },
};
</script>
