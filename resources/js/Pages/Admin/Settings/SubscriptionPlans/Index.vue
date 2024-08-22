<template>
  <SettingsLayout :title="data.title">
    <ActionToolBar :toolbar="toolbar" />

    <Table
      :options="tableOptions"
      :links="subscription_plans.links"
      :total="subscription_plans.total"
    >
      <template v-slot>
        <tr v-for="(subscription_plan, index) in subscription_plans.data" :key="index">
          <td>
            <Link
              :href="
                route('admin.settings.subscriptionPlans.edit', subscription_plan.uuid)
              "
              >{{ subscription_plan.title }}</Link
            >
          </td>
          <td class="text-end">{{ (subscription_plan.price) ? formatMoney(subscription_plan.price) : __('Free') }}</td>
          <!-- <td>{{ subscription_plan.country_code }}</td> 
          <td>{{ subscription_plan.layout_direction }}</td>  
          <td class="text-center">
            <span v-if="subscription_plan.is_default"><i class="fa-solid fa-circle-check text-success"></i></span>
          </td>                     -->
          <td class="col-md-2 text-end">
            <DestroyButton
              :delete_url="
                route('admin.settings.subscriptionPlans.destroy', subscription_plan.id)
              "
            />
          </td>
        </tr>
      </template>
    </Table>
  </SettingsLayout>
</template>

<script>
import SettingsLayout from "../Partials/SettingsLayout.vue";
// import SearchBar from "../../../../../Shared/SearchBar.vue";

import DestroyButton from "../../../../components/Form/DestroyButton.vue";
import ActionToolBar from "../Partials/ActionToolBar.vue";

import Table from "../../../../components/Table.vue";

export default {
  components: {
    SettingsLayout,
    ActionToolBar,
    Table,
    DestroyButton,
  },
  props: ["data", "subscription_plans", "filters"],
  data() {
    return {
      toolbar: {
        title: this.data.title,
        hide_save_button: true,
        links: {
          create: {
            title: this.__("Add new"),
            url: route("admin.settings.subscriptionPlans.create"),
          },
        },
      },
      tableOptions: {
        titles: [
          {
            name: this.__("Name"),
            className: "col-md-2",
          },
          {
            name: this.__("Price"),
            className: "col-md-2 text-end",
          },
          {
            name: this.__("Action"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
