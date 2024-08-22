<template>
  <AppHead :title="data.title" />
  <div class="container mt-4 page-container">
    <PageTitle :title="data.title">
      <div class="float-end">
        <Link :href="data.urls.new_item" class="btn btn-sm btn-primary">
          <i class="fa-solid fa-plus"></i>
          {{ __("Add new") }}
        </Link>
      </div>
    </PageTitle>

    <div class="row">
      <div class="col-md-12">
        <SearchBar :url="data.urls.search" :filters="filters.filters" />

        <Table
          :options="tableOptions"
          :links="coupons.links"
          :total="coupons.total"
        >
          <template v-slot>
            <tr v-for="(coupon, index) in coupons.data" :key="index">
              <td>
                <Link :href="route('admin.coupons.edit', coupon.id)">{{
                  coupon.code
                }}</Link>
              </td>
              <td class="">
                {{ coupon.active_date }}
              </td>
              <td class="">
                {{ coupon.expiry_date }}
              </td>
              <td class="">
                {{ coupon.usage }}
              </td>
              <td class="text-end">
                <DestroyButton
                  :delete_url="route('admin.coupons.destroy', coupon.id)"
                />
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import DestroyButton from "../../../components/Form/DestroyButton.vue";
import SearchBar from "../../../Shared/SearchBar.vue";

export default {
  props: ["data", "coupons", "filters"],
  components: {
    Table,
    DestroyButton,
    SearchBar,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: this.__("Code"),
            className: "",
          },
          {
            name: this.__("Active Date"),
            className: "",
          },
          {
            name: this.__("Expiry Date"),
            className: "",
          },
          {
            name: this.__("Usage"),
            className: "",
          },
          {
            name: this.__("Action"),
            className: "text-end",
          },
        ],
      },
    };
  },
};
</script>