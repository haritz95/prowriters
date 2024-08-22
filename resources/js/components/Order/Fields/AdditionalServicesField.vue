<template>
  <div
    class="mt-4"
    v-if="
      list_of_additional_services && Object.values(list_of_additional_services).length
    "
  >
    
    <h5 class="order-form-section-title">
      {{ __("Additional Services") }}
    </h5>

    <div
      class="mb-3 mt-2 p-2"
      v-for="(row, index) in list_of_additional_services"
      :key="index"
    >
     <div class="border p-2">
      <div class="row no-gutters">
        <div class="col-md-8">
          <div class="card-body">
            <div class="card-title">{{ row.name }}</div>
            <small class="text-muted">{{ row.description }}</small>

            <div class="mt-3" v-if="row.type == priceTypes.per_entered_quantity">
              <label>{{ __(row.per_entered_quantity_label) }}</label>
              <div :class="constants.style.formGroup">
                <div :class="constants.style.formElement">
                  <InputSpinner
                    @updated="updateAdditionalService(row)"
                    v-model="row.quantity"
                    :step="1"
                    :min="1"
                  ></InputSpinner>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="d-flex justify-content-center align-items-center h-100">
            <a href="#" v-on:click.prevent="additionalServiceChanged(row.id, row)">
              <div
                class="btn btn-sm btn-block"
                v-bind:class="getServiceContainerClass(row.id)"
              >
                <span v-if="addedServiceList(row.id)">
                  <i class="fas fa-check-circle"></i> {{ __("Added") }}
                </span>
                <span v-else> <i class="fas fa-plus"></i> {{ __("Add") }} </span>
                {{ formatMoneyFromNumber(getPrice(row)) }}
              </div>
            </a>
          </div>
        </div>
      </div>
     </div>
    </div>
  </div>
</template>

<script>
import InputSpinner from "../../Form/InputSpinner.vue";

export default {
  props: ["modelValue", "additional_services", "basic_price"],
  components: {
    InputSpinner,
  },
  created() {
    let scope = this;

    this.additional_services.map((additional_service, index) => {
      var isAlreadyInList = this.addedServiceList(additional_service.id);
      if (isAlreadyInList) {
        additional_service.quantity = isAlreadyInList.value.quantity;

        additional_service.total = this.getPrice(additional_service);

        this.list_of_additional_services[index] = additional_service;

        this.modelValue[isAlreadyInList.index] = additional_service;
        this.$emit("update:modelValue", this.modelValue);
      } else {
        this.list_of_additional_services[index] = additional_service;
      }
    });
  },
  data() {
    return {
      list_of_additional_services: [],
      priceTypes: {
        fixed: "fixed",
        percentage: "percentage",
        per_unit: "per_unit",
        per_entered_quantity: "per_entered_quantity",
      },
      constants: {
        style: {
          formGroup: "mb-3",
          formLabel: "form-label",
          formElement: "col-md-8",
        },
        priceType: {
          fixed: 1,
          perWord: 2,
          perPage: 3,
        },
      },
    };
  },
  methods: {
    formatMoneyFromNumber($amount) {
      return this.formatMoney($amount, currencyConfig.currency);
    },
    getPrice(row) {
      if (row.type == this.priceTypes.fixed) {
        return row.price;
      } else if (row.type == this.priceTypes.percentage) {
        let basic_price = parseFloat(this.basic_price);
        if (!basic_price) {
          basic_price = 0;
        }        
        return (row.price / 100) * basic_price;
      } else if (row.type == this.priceTypes.per_unit) {
        return 0;
        //return row.price * this.$store.getters.getQuantityInLargestUnitOfMeasurement();
      } else if (row.type == this.priceTypes.per_entered_quantity) {
        return row.price * row.quantity;
      }
    },
    additionalServiceChanged(id, additionalService) {
      var isAlreadyInList = this.addedServiceList(id);
      if (isAlreadyInList) {
        this.modelValue.splice(isAlreadyInList.index, 1);
      } else {
        additionalService.total = this.getPrice(additionalService);
        this.modelValue.push(additionalService);
      }
      this.$emit("update:modelValue", this.modelValue);
    },
    updateAdditionalService(additionalService) {
      var isAlreadyInList = this.addedServiceList(additionalService.id);
      if (isAlreadyInList) {
        additionalService.total = this.getPrice(additionalService);
        this.modelValue[isAlreadyInList.index] = additionalService;
      }
      this.$emit("update:modelValue", this.modelValue);
    },
    addedServiceList(id) {
      var status = false;
      if (this.modelValue && this.modelValue.length > 0) {
        this.modelValue.map((item, index) => {
          if (item.id == id) {
            status = {
              index: index,
              value: item,
            };
          }
        });
      }

      return status;
    },
    getServiceContainerClass(additionalServiceId) {
      return {
        "btn-secondary": this.addedServiceList(additionalServiceId),
        "btn-outline-secondary": !this.addedServiceList(additionalServiceId),
      };
    },
  },
};
</script>
