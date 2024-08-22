<template>
  <SettingsLayout :title="data.title">
    <form @submit.prevent="form.post(data.urls.submit_form)">
      <ActionToolBar :disable_save_button="form.processing" :toolbar="toolbar" />
      <div class="mb-3">
        <label class="form-label"
          >{{ __("Business Operation Type") }} <span class="required">*</span></label
        >
        <v-select
          :reduce="(option) => option.id"
          v-model="form.business_operation_type"
          :options="data.dropdowns.business_operation_types"
          label="name"
          :clearable="false"
          :searchable="false"
        >
          <template #option="{ name, description }">
            <div>{{ name }}</div>
            <small class="text-muted">{{ description }}</small>
          </template>
        </v-select>
        <ValidationError name="business_operation_type" />
      </div>

      <Input
        v-model="form.payout_amount_threshold"
        name="payout_amount_threshold"
        :label="__('Author payout amount threshold')"
        :required="true"
        @keypress="onlyNumber($event, form.payout_amount_threshold)"
      />

      <div class="mb-3">
        <label class="form-label"
          >{{ __("Time to add to the deadline for each revision request") }}
          <span class="required">*</span></label
        >
        <div class="input-group">
          <input
            v-model="form.dead_line_extension_by_value"
            type="text"
            class="form-control form-control-sm w-75"
            @keypress="onlyNumber($event, form.dead_line_extension_by_value)"
          />
          <select
            class="form-select form-select-sm w-25"
            v-model="form.dead_line_extension_by_type"
          >
            <option
              v-for="(option, index) in data.dropdowns.urgency_types"
              v-bind:value="option.id"
              :key="index"
            >
              {{ option.name }}
            </option>
          </select>
        </div>
        <ValidationError name="value" />
      </div>

      <Select
        :options="data.dropdowns.quality_control_availability"
        v-model="form.disable_quality_control"
        :label="__('Disable Quality Control')"
        :required="true"
        name="disable_quality_control"
      />

      <Select
        :options="data.dropdowns.sales_tax_availability"
        v-model="form.enable_sales_tax"
        :label="__('Enable Sales Tax')"
        name="enable_sales_tax"
      />

      <Input
        v-if="form.enable_sales_tax"
        v-model="form.sales_tax_rate"
        name="sales_tax_rate"
        :label="__('Sales Tax Rate')"
        :required="true"
        @keypress="onlyNumber($event, form.sales_tax_rate)"
        :note="__('In Percentage')"
      />

      <Select
        v-if="form.business_operation_type != 'bidding'"
        :options="data.dropdowns.find_work_for_authors_availability"
        v-model="form.enable_self_assigning_tasks"
        :label="
          __('Allow authors to assign themselves tasks when direct ordering is enabled')
        "
        :required="true"
        name="enable_self_assigning_tasks"
      />

      <SelectUser
        :options="data.dropdowns.admin_users"
        v-model="form.default_receipt_id_for_incoming_messages"
        :label="__('Default receipt for incoming messages from authors')"
        :required="true"
        name="default_receipt_id_for_incoming_messages"
      />
    </form>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "./Partials/SettingsLayout.vue";
import ActionToolBar from "./Partials/ActionToolBar.vue";
import { Input, Select, SelectUser } from "../../../components/Form/Index.js";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Input,
    Select,
    SelectUser,
  },
  props: ["data", "records"],
  data() {
    return {
      form: this.$inertia.form(this.records),
      toolbar: {
        title: this.data.title,
      },
    };
  },
};
</script>
