<template>
    <form @submit.prevent="form.patch(data.urls.submit_form)">
        <!-- Environment Field -->
        <Select :options="data.dropdowns.environments" v-model="form.keys.environment" name="keys.environment"
            :label="__('Environment')" :required="true" />

        <!-- Display Name Field -->
        <Input v-model="form.name" name="name" :label="__('Display Name')" :required="true" />

        <!-- API Key Field -->
        <Input v-model="form.keys.api_key" name="keys.api_key" :label="__('API Key')" :required="true" />

        <!-- Public Key Field -->
        <Input v-model="form.keys.public_key" name="keys.public_key" :label="__('Public Key')" :required="true" />

        <!-- Inactive Checkbox -->
        <CheckBox v-model="form.inactive" name="inactive" :label="__('Inactive')" />

        <!-- Submit Button -->
        <div class="mb-3">
            <SubmitButton :disabled="form.processing" />
        </div>
    </form>
</template>

<script>
import {
    Select,
    Input,
    CheckBox,
    SubmitButton,
} from "../../../../../components/Form/Index.js";

export default {
    components: {
        Select,
        Input,
        CheckBox,
        SubmitButton,
    },
    inject: ["data"],
    data() {
        return {
            form: this.$inertia.form(this.prepareForm()),
        };
    },
    methods: {
        prepareForm() {
            let inputs = {
                name: null,
                keys: {
                    environment: null,
                    api_key: null,       // Add API Key field
                    public_key: null,    // Add Public Key field
                },
                inactive: null,
            };
            if (this.data.settings) {
                inputs = { ...inputs, ...this.data.settings };
            }
            return inputs;
        },
    },
};
</script>
