<template>
    <SettingsLayout :title="data.title">
        <ActionToolBar :toolbar="toolbar" />

        <ul class="nav nav-tabs">
            <li v-for="(gateway, index) in data.gateways" :key="index" class="nav-item">
                <Link :href="route('admin.settings.payment.gateways', { gateway: gateway.slug })
                    " class="nav-link" :class="{ active: data.current_gateway == index }">{{ __(gateway.name) }}</Link>
            </li>
        </ul>

        <div class="p-4">
            <PayPal v-if="data.current_gateway == 'paypal_checkout'" />
            <Stripe v-if="data.current_gateway == 'stripe'" />
            <Braintree v-if="data.current_gateway == 'braintree'" />
            <PayStack v-if="data.current_gateway == 'paystack'" />
            <PayU v-if="data.current_gateway == 'payu'" />
            <TwoCheckout v-if="data.current_gateway == 'twocheckout'" />
            <NOWPayments v-if="data.current_gateway == 'nowpayments'" />
        </div>
    </SettingsLayout>
</template>

<script>
import SettingsLayout from "../Partials/SettingsLayout.vue";
import ActionToolBar from "../Partials/ActionToolBar.vue";

import PayPal from "./Providers/PayPal.vue";
import Stripe from "./Providers/Stripe.vue";
import Braintree from "./Providers/Braintree.vue";
import PayStack from "./Providers/PayStack.vue";
import PayU from "./Providers/PayU.vue";
import TwoCheckout from "./Providers/TwoCheckout.vue";
import NOWPayments from "./Providers/NOWPayments.vue";


export default {
    components: {
        SettingsLayout,
        ActionToolBar,
        PayPal,
        Stripe,
        Braintree,
        PayStack,
        PayU,
        TwoCheckout,
        NOWPayments,
    },
    props: ["data"],
    provide() {
        return {
            data: this.data,
        };
    },
    data() {
        return {
            toolbar: {
                title: this.data.title,
                hide_save_button: true,
            },
        };
    },
};
</script>
