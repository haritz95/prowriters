<template>
  <div
    class="mt-4"
    v-if="
      $store.state.data.service_levels &&
      Object.values($store.state.data.service_levels).length
    "
  >
    <h5 class="card-title order-form-section-title">
      {{ __("Customer Service") }}
    </h5>

    <ul class="list-group list-group-horizontal-lg text-center">
      <template
        v-for="row in $store.state.data.service_levels"
        v-bind:key="row.id"
      >
      
        <a @click.prevent="handleCustomerServiceLevel(row)" class="list-group-item list-group-item-action"
        v-bind:class="{
              active: $store.state.form.service_level_id == row.id,
            }" href="#">
          <div>
            <div>{{ getPrice(row) }}</div>
            <div><strong>{{ row.name }}</strong></div>
          </div>
          <small style="font-size: 12px">{{ row.description }}</small>
        </a>
      </template>
    </ul>
  </div>
</template>

<script>
export default {
   inject: ["$store"],
  data() {
    return {
      priceTypes: {
        fixed: "fixed",
        percentage: "percentage",
      },
    };
  },
  methods: {

    getPrice(row) {
      if (row.price == 0) {
        return this.$store.state.data.texts.Free;
      }
      if (row.type == this.priceTypes.fixed) {
        return "+" + this.formatMoney(row.price);
      } else {
        return "+" + parseFloat(row.price).toFixed(2) + "%";
      }
    },
    handleCustomerServiceLevel(serviceLevel) {
        this.$store.state.form.service_level_id = serviceLevel.id;
    },

 
  },
};
</script>
