<template>
   <SettingsLayout :title="data.title">      
      <form @submit.prevent="form.post(route('admin.settings.general.update'))">
        <ActionToolBar :disable_save_button="form.processing" :toolbar="toolbar"/>
        <Input v-model="form.company_name" name="company_name" :label="__('Company Name')" :required="true"/>
        <div class="row">
            <div class="col">
                <Input v-model="form.company_phone" name="company_phone" :label="__('Phone')" :required="true"/>
            </div>
            <div class="col">
                <Input v-model="form.company_email" name="company_email" :type="'email'" :label="__('Email')" :required="true"/>
            </div>
        </div>
        <TextArea v-model="form.company_address" name="company_address" :label="__('Address')" :required="true"/>
        <Input v-model="form.company_notification_email" name="company_notification_email" :label="__('Email address for receiving notifications')" 
         :required="true" :tooltip="__('All system notification emails will be sent to this address')"/>
 
        <div class="mt-4"></div>
        
         <CheckBox v-model="form.hide_website" :label="__('Hide Website')" name="hide_website"/>
         <CheckBox v-model="form.hide_blog" :label="__('Hide Blog')" name="hide_blog"/>         
         
         <CheckBox v-model="form.hide_author_application_link_from_website" :label="__('Hide Author Application Link from Website')" name="hide_author_application_link_from_website"/>
      </form>
   </SettingsLayout>
</template>

<script>
import SettingsLayout from './Partials/SettingsLayout.vue';
import ActionToolBar from './Partials/ActionToolBar.vue';
import Input from '../../../components/Form/Input.vue';
import TextArea from '../../../components/Form/TextArea.vue';
import CheckBox from '../../../components/Form/CheckBox.vue';

export default {
    components: {
        SettingsLayout,
        ActionToolBar,
        Input,
        TextArea,
        CheckBox,
    },
    props: ['data', 'records'],
    data() {
        return {
            form : this.$inertia.form(this.records),
            toolbar: {
                title: 'General',
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