<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <Link class="btn btn-sm btn-light" :href="route('admin.applicants.index')">
        {{ __("back to") }} {{ __("List of Applicants") }}
      </Link>
    </PageTitle>
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h5>{{ __("Career Summary") }}</h5>
            <div class="pre-formatted" v-html="applicant.bio"></div>
            <hr />

            <h5>{{ __("General Information") }}</h5>
            <table class="table table-bordered">
              <tr v-for="(row, index) in general" :key="index">
                <td class="text-muted text-left">{{ row.label }}</td>
                <td class="text-left">{{ row.value }}</td>
              </tr>
            </table>

            <h5 class="mt-4">{{ __("Service") }}</h5>
            {{ applicant.service.name }}

            <hr />
            <h5 class="mt-4">{{ __("Subjects") }}</h5>
            <template v-for="(subject, index) in data.subjects" :key="index">
              <span v-if="subject" class="me-2 mb-2">{{ subject.name }}</span>
            </template>
            <hr />

            <h5 class="mt-4">{{ __("Links") }}</h5>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>{{ __("Name") }}</th>
                  <th>{{ __("URL") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(link, index) in links" :key="index">
                  <td class="text-muted">
                    {{ link.label }}
                  </td>
                  <td>
                    <a v-if="link.value" target="_blank" :href="link.value"
                      >{{ __("Visit") }} <i class="fa-solid fa-up-right-from-square"></i
                    ></a>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- <h5 class="mt-4">{{ __("Resume") }}</h5> -->
            <div class="d-grid gap-2" v-if="data.attachment_uuid">
              <a
                class="btn btn-light"
                :href="route('attachments.download', data.attachment_uuid)"
              >
                <i class="fa-regular fa-file"></i> {{ __("Download") }}
                {{ __("Resume") }}</a
              >
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <form
              @submit.prevent="
                form.patch(route('admin.applicants.update', applicant.uuid))
              "
            >
              <Select
                :label="__('Status')"
                v-model="form.applicant_status_id"
                :options="data.dropdowns.statuses"
                name="applicant_status_id"
              />
              <TextArea v-model="form.note" :label="__('Note')" name="note" />
              <SubmitButton :disabled="form.processing" />
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            {{ __("Enrollment") }}
          </div>
          <div class="card-body">
            <form
              @submit.prevent="
                enrollForm.post(route('admin.applicants.enroll', applicant.uuid))
              "
            >
              <Select
                :label="__('Author Level')"
                v-model="enrollForm.author_level_id"
                :options="data.dropdowns.author_levels"
                name="author_level_id"
              />

              <SubmitButton
                :button_text="__('Enroll as Author')"
                :disabled="enrollForm.processing"
              />

              <small>{{
                __("Please note that applicant record will be removed after enrollment")
              }}</small>
            </form>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button @click="destroy" class="btn btn-outline-danger">
            <i class="fa-solid fa-trash-can"></i> {{ __("Delete") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Select, TextArea, SubmitButton } from "../../../components/Form/Index.js";
export default {
  props: ["data", "applicant"],
  components: {
    Select,
    TextArea,
    SubmitButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        applicant_status_id: this.applicant.applicant_status_id,
        note: this.applicant.note,
      }),
      enrollForm: this.$inertia.form({
        author_level_id: null,
      }),
      formConfig: { preserveScroll: true },
      subjects: [
        this.getValue(this.applicant.subject_1, "name"),
        this.getValue(this.applicant.subject_2, "name"),
        this.getValue(this.applicant.subject_3, "name"),
        this.getValue(this.applicant.subject_4, "name"),
        this.getValue(this.applicant.subject_4, "name"),
      ],
      services: [
        this.getValue(this.applicant.service_1, "name"),
        this.getValue(this.applicant.service_2, "name"),
        this.getValue(this.applicant.service_3, "name"),
      ],
      general: [
        { label: this.__("Applicant ID"), value: this.applicant.number },
        { label: this.__("Email"), value: this.applicant.email },
        { label: this.__("Phone"), value: this.applicant.phone },
        {
          label: this.__("Education Level"),
          value: this.applicant.education_level.name,
        },
        {
          label: this.__("Years of experience"),
          value: this.applicant.years_of_experience,
        },
        {
          label: this.__("Address"),
          value: this.applicant.address,
        },
        {
          label: this.__("City"),
          value: this.applicant.city,
        },
        {
          label: this.__("State"),
          value: this.applicant.state,
        },
        {
          label: this.__("Country"),
          value: this.applicant.country.name,
        },
      ],
      links: [
        {
          label: this.__("Blog"),
          value: this.getValue(this.applicant, "blog_url"),
        },
        {
          label: this.__("Portfolio"),
          value: this.getValue(this.applicant, "online_portfolio_url"),
        },
        {
          label: this.__("Linkedin"),
          value: this.getValue(this.applicant, "linked_in_url"),
        },
      ],
    };
  },
  methods: {
    getValue(data, key) {
      if (data) {
        return Object.hasOwn(data, key) ? data[key] : null;
      }
      return null;
    },
    enrollAsAuthor() {
      let scope = this;
      this.confirmDialog(scope.__("Yes, Enroll"), () => {
        scope.$inertia.post(
          route("admin.applicants.enroll", scope.applicant.uuid),
          scope.formConfig
        );
      });
    },
    destroy() {
      this.deleteConfirmDialog(
        this,
        route("admin.applicants.destroy", this.applicant.uuid)
      );
    },
  },
};
</script>
