<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title" />
    <div class="row" v-if="tasks.length > 0">
      <div class="col-md-7">
        <fieldset class="border rounded-3 p-3" v-if="data.is_billable">
          <div>
            <form
              @submit.prevent="
                form.post(route('author.paymentRequests.store'), formConfig)
              "
            >
              <Input
                v-model="form.name"
                :label="__('Your Name')"
                name="name"
                :required="true"
              />
              <TextArea
                v-model="form.address"
                :label="__('Your Address')"
                name="address"
                :required="true"
              />
              <TextArea
                v-model="form.note"
                :label="__('Note')"
                name="note"
                :required="true"
              />
              <Input
                v-model="form.invoice_number"
                :label="__('Your Invoice Number')"
                name="invoice_number"
                :note="__('Optional')"
              />

              <SubmitButton class="mt-3" :disabled="form.disabled" />
            </form>
          </div>
        </fieldset>
        <div class="border p-3" v-else>
          {{ data.minimum_threshold_message }}
        </div>
      </div>

      <div class="col-md-5">
        <small class="mb-4">{{
          __("List of completed tasks that are billable")
        }}</small>
        <Table
          :options="tableOptions"
          :total="tasks.length"
          :noPagination="true"
        >
          <template v-slot>
            <tr v-for="(task, index) in tasks" :key="index">
              <td class="col-md-1">{{ index + 1 }}</td>
              <td class="col-md-5">
                <Link :href="route('author.tasks.show', task.uuid)">{{ task.number }}</Link>
              </td>
              <td class="col-md-2 text-end">
                {{ formatMoney(task.author_payment_amount) }}
              </td>
            </tr>
            <tr>
              <td></td>
              <td class="fw-bolder">{{ __("Total billable amount") }}</td>
              <td class="text-end fw-bolder">{{ formatMoney(data.total) }}</td>
            </tr>
          </template>
        </Table>
      </div>
    </div>

    <div class="row justify-content-center" v-if="tasks.length == 0">
      <div class="col-md-4">
        <img :src="asset('images/sad.svg')" class="img-fluid" />
        <h5 class="text-center">{{ data.no_billable_work_message }}</h5>
      </div>
    </div>
  </div>
</template>

<script>
import Table from "../../../components/Table.vue";
import {
  Input,
  TextArea,
  SubmitButton,
} from "../../../components/Form/Index.js";

export default {
  components: {
    Table,
    Input,
    TextArea,
    SubmitButton,
  },
  props: ["data", "tasks"],

  methods: {
    getAddress() {
      let profile = this.data.author.profile;
      let address = profile.address;
      if (profile.city) {
        address = address + "\n" + profile.city;
      }
      if (profile.state) {
        address = address + "\n" + profile.state;
      }
      if (this.data.author.country) {
        address = address + "\n" + this.data.author.country.name;
      }
      return address;
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.data.author.full_name,
        address: this.getAddress(),
        note: null,
        invoice_number: null,
      }),
      formConfig: {
        preserveScroll: false,
        onSuccess: () => this.form.reset(),
      },
      tableOptions: {
        titles: [
          {
            name: "#",
            className: "col-md-1",
          },
          {
            name: this.__("Item"),
            className: "col-md-5",
          },
          {
            name: this.__("Earning"),
            className: "col-md-2 text-end",
          },
        ],
      },
    };
  },
};
</script>
