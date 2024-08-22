<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.invoices.index')">
        <i class="fa-solid fa-arrow-left-long"></i> {{ __("Back to invoices") }}
      </Link>
    </PageTitle>
    <form @submit.prevent="form.post(route('admin.invoices.store'), formConfig)">
      <div class="row">
        <div class="col-md-6">
          <SearchCustomer
            :options="data.dropdowns.customers"
            v-model="form.customer_id"
            :label="__('Customer')"
            name="customer_id"
          />
          <TextArea
            v-model="form.billing_address"
            rows="2"
            :label="__('Billing Address')"
            name="billing_address"
          />
        </div>

        <div class="col-md-6">
          <DatePicker
            format="yyyy-MM-dd"
            v-model="form.invoice_date"
            :label="__('Invoice Date')"
            name="invoice_date"
          />
          <DatePicker
            format="yyyy-MM-dd"
            v-model="form.due_date"
            :label="__('Due Date')"
            name="due_date"
          />
        </div>

        <div class="col-md-12">
          <table class="table table-sm">
            <thead class="table-secondary">
              <tr>
                <th class="col-md-2">{{ __("Task") }}</th>
                <th class="col-md-2">{{ __("Item") }}</th>
                <th class="col-md-2">{{ __("Description") }}</th>
                <th class="col-md-1">{{ __("Quantity") }}</th>
                <th class="col-md-2">{{ __("Rate") }}</th>
                <th class="col-md-2 text-end">{{ __("Amount") }}</th>
                <th class="col-md-1"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in form.invoice_items" :key="index">
                <td>
                  {{ item.linked_task_number }}
                </td>
                <td>
                  <textarea
                    class="form-control form-control-sm"
                    v-model="form.invoice_items[index].name"
                  />
                  <ValidationError :name="getErrorFieldName('name', index)" />
                </td>
                <td>
                  <textarea
                    class="form-control form-control-sm"
                    v-model="form.invoice_items[index].description"
                  />
                  <ValidationError :name="getErrorFieldName('description', index)" />
                </td>
                <td>
                  <input
                    class="form-control form-control-sm"
                    type="text"
                    v-model="form.invoice_items[index].quantity"
                    @keypress="onlyNumber($event, form.invoice_items[index].quantity)"
                  />
                  <ValidationError :name="getErrorFieldName('quantity', index)" />
                </td>
                <td>
                  <input
                    class="form-control form-control-sm"
                    type="text"
                    v-model="form.invoice_items[index].price"
                    @keypress="onlyNumber($event, form.invoice_items[index].price)"
                  />
                  <ValidationError :name="getErrorFieldName('price', index)" />
                </td>

                <td class="text-end">
                  {{ form.invoice_items[index].sub_total }}
                  <ValidationError :name="getErrorFieldName('sub_total', index)" />
                </td>
                <td class="text-end">
                  <button
                    type="button"
                    @click="removeLine(index)"
                    class="btn btn-sm btn-danger"
                  >
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td colspan="7">
                  <button
                    type="button"
                    @click="addNewLine"
                    class="btn btn-sm btn-success"
                  >
                    {{ __("Add new line") }}
                  </button>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <th class="text-end" colspan="6">{{ __("Sub Total") }}</th>
                <th class="text-end">{{ formatMoney(sub_total) }}</th>
              </tr>
              <tr>
                <th class="text-end" colspan="6">{{ __("Discount") }}</th>
                <th class="text-end">
                  <Input
                    v-model="form.discount"
                    name="discount"
                    @keypress="onlyNumber($event, form.discount)"
                    customStyle="text-end"
                  />
                </th>
              </tr>
              <tr>
                <th class="text-end" colspan="6">
                  <div>
                    {{ __("Sales Tax") }}
                    <small class="text-muted" v-if="data.enable_sales_tax"
                      >({{ Math.round(data.sales_tax_rate, 2) }}%)</small
                    >
                  </div>
                </th>
                <th class="text-end">{{ formatMoney(sales_tax_amount) }}</th>
              </tr>
              <tr>
                <th class="text-end" colspan="6">{{ __("Total") }}</th>
                <th class="text-end">{{ calculateTotal }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <TextArea
            v-model="form.admin_note"
            name="admin_note"
            :label="__('Admin Note')"
          />
        </div>
        <div class="col-md-6">
          <TextArea
            v-model="form.customer_note"
            name="customer_note"
            :label="__('Customer Note')"
          />
        </div>
        <div class="col-md-12">
          <TextArea
            v-model="form.terms_and_conditions"
            name="terms_and_conditions"
            :label="__('Terms & Condition')"
          />
        </div>
      </div>
      <SubmitButton :disabled="form.processing" />
    </form>
  </div>
</template>

<script>
import {
  SearchCustomer,
  TextArea,
  Input,
  DatePicker,
  SubmitButton,
} from "../../../components/Form/Index.js";
export default {
  components: {
    SearchCustomer,
    TextArea,
    Input,
    DatePicker,
    SubmitButton,
  },
  props: ["data", "existing_record"],
  computed: {
    calculateTotal() {
      let sub_total = 0;
      let discount = 0;
      if (this.form.discount) {
        discount = parseFloat(this.form.discount);
      }

      let sales_tax_amount = 0;

      for (let index = 0; index < this.form.invoice_items.length; index++) {
        const element = this.form.invoice_items[index];
        let quantity = parseFloat(element.quantity);
        let price = parseFloat(element.price);

        const item_price = quantity * price;

        this.form.invoice_items[index].sub_total = this.formatMoney(item_price);
        sub_total = sub_total + item_price;
      }
      if (this.data.enable_sales_tax) {
        sales_tax_amount = this.calculateSalesTax(sub_total);
      }
      if (discount > sub_total) {
        discount = 0;
        this.form.discount = discount;
      }
      let total = sub_total - discount + sales_tax_amount;

      this.sub_total = sub_total;
      this.sales_tax_amount = sales_tax_amount;

      return this.formatMoney(total);
    },
  },
  watch: {
    "form.customer_id": {
      handler(customer_id) {
        this.getUnInvoicedTasks(customer_id);
      },
      deep: true,
    },
  },
  created() {},
  data() {
    return {
      form: this.$inertia.form(this.prepareForm()),
      sales_tax_amount: 0,
      sub_total: 0,
    };
  },
  methods: {
    prepareForm() {
      let inputs = {
        customer_id: null,
        invoice_date: new Date(),
        due_date: null,
        invoice_items: [],
        billing_address: null,
        admin_note: null,
        customer_note: null,
        terms_and_conditions: null,
        discount: 0,
      };
      if (this.existing_record) {
        inputs = { ...inputs, ...this.existing_record };
      }

      if (this.existing_record && this.existing_record.customer_id) {
        this.getUnInvoicedTasks(this.existing_record.customer_id);
      }

      return inputs;
    },
    calculateSalesTax(total_before_tax_and_discount) {
      if (this.data.enable_sales_tax && this.data.sales_tax_rate) {
        return Math.round(
          (total_before_tax_and_discount * this.data.sales_tax_rate) / 100,
          2
        );
      }
      return 0;
    },
    prepareInvoiceLineFromTasks(tasks) {
      for (let index = 0; index < tasks.length; index++) {
        let task = tasks[index];
        this.form.invoice_items.push({
          linked_task_id: task.id,
          linked_task_number: task.number,
          name: task.service.name,
          description: task.title,
          price: task.total,
          quantity: 1,
          sub_total: task.total,
        });
      }
    },
    getUnInvoicedTasks(customer_id) {
      axios
        .post(route("admin.invoices.tasks.not_invoiced"), {
          customer_id: customer_id,
        })
        .then((response) => {
          this.prepareInvoiceLineFromTasks(response.data);
        });
    },
    addNewLine() {
      this.form.invoice_items.push({
        linked_task_id: "",
        linked_task_number: "",
        name: "",
        description: "",
        price: 0,
        quantity: 1,
        sub_total: 0,
      });
    },
    removeLine(index) {
      this.form.invoice_items.splice(index, 1);
    },
    getErrorFieldName(field, index) {
      return "invoice_items." + [index] + "." + field;
    },
    restrictEmpty(field, index) {
      const value = this.form.invoice_items[index][field];
      if (!value || value == "") {
        this.form.invoice_items[index][field] = 0;
      }
    },
  },
};
</script>
