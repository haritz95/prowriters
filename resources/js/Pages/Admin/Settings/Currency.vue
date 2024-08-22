<template>
   <SettingsLayout :title="data.title">      
      <form @submit.prevent="form.post(data.urls.submit_form)">
        <ActionToolBar :disable_save_button="form.processing" :toolbar="toolbar" @submit="submitForm"/>
        <Input v-model="form.currency_symbol" name="currency_symbol" :label="__('Currency Symbol')" :required="true"/>
        <Input v-model="form.currency_code" name="currency_code" :label="__('Currency Code')" :required="true"/>         
        <Select v-model="form.digit_grouping_method" :options="data.dropdowns.digit_grouping_methods" name="digit_grouping_method" :label="__('Digit Grouping')" :required="true"/>
        <Select v-model="form.decimal_symbol" :options="data.dropdowns.decimal_symbols" name="decimal_symbol" :label="__('Decimal Symbols')" :required="true"/>
        <Select v-model="form.thousand_separator" :options="data.dropdowns.thousand_separators" name="thousand_separator" :label="__('Thousand Separator')" :required="true"/>
        <Select v-model="form.currency_position" :options="data.dropdowns.currency_positions" name="currency_position" :label="__('Currency Position')" :required="true"/>
        <Select v-model="form.currency_precision" :options="data.dropdowns.currency_precisions" name="currency_precision" :label="__('Currency Precision')" :required="true"/>        
      </form>
   </SettingsLayout>
</template>

<script>
import SettingsLayout from './Partials/SettingsLayout.vue';
import ActionToolBar from './Partials/ActionToolBar.vue';
import Input from '../../../components/Form/Input.vue';
import Select from '../../../components/Form/Select.vue';

export default {
    components: {
        SettingsLayout,
        ActionToolBar,
        Input, 
        Select,
    },
    props: ['data', 'records'],
    data() {
        return {
            form : this.$inertia.form(this.records),
            toolbar: {
                title: this.data.title,
                // links: {
                //     create: {
                //         title: 'Create',
                //         url: '#',
                //     },
                //     previous_page: {
                //         title: 'Back',
                //         url: '#',
                //     },
                // },
                hide_save_button: false,
            },
        };
    },   
}
</script>