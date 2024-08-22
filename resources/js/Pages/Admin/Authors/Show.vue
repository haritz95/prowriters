<template>
  <AppHead :title="data.title" />
  <ProfileLayout :author="author">
    <div class="card">
      <div class="card-body">
        <h5>{{ __("Career Summary") }}</h5>
        <div class="pre-formatted" v-html="author.profile.bio"></div>
        <hr />

        <h5>{{ __("General Information") }}</h5>
        <table class="table table-bordered">
          <tr>
            <td class="text-muted text-left">{{ __("Author Level") }}</td>
            <td class="text-left">{{ author.profile.author_level.name }}</td>
          </tr>
          <tr v-for="(row, index) in general" :key="index">
            <td class="text-muted text-left">{{ row.label }}</td>
            <td class="text-left">{{ row.value }}</td>
          </tr>
        </table>

        <h5>{{ __("Services") }}</h5>
        <template v-for="(service, index) in data.services" :key="index">
          <span v-if="service" class="badge badge-lg badge-soft-primary me-2 mb-2">{{
            service.name
          }}</span>
        </template>

        <hr />
        <h5>{{ __("Subjects") }}</h5>
        <template v-for="(subject, index) in data.subjects" :key="index">
          <span v-if="subject" class="badge badge-lg badge-soft-primary me-2 mb-2">{{
            subject.name
          }}</span>
        </template>

        <hr />

        <h5>{{ __("Payment method") }}</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>{{ __("Name") }}</th>
              <th>{{ __("Details") }}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-muted">
                {{ author.profile.payment_method }}
              </td>
              <td>
                {{ author.profile.payment_method_details }}
              </td>
            </tr>
          </tbody>
        </table>

        <h5>{{ __("Links") }}</h5>
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

        <div class="d-grid gap-2" v-if="data.attachment_uuid">
          <a
            class="btn btn-light"
            :href="route('attachments.download', data.attachment_uuid)"
          >
            <i class="fa-regular fa-file"></i> {{ __("Download") }} {{ __("Resume") }}</a
          >
        </div>
      </div>
    </div>
  </ProfileLayout>
</template>

<script>
import ProfileLayout from "./Partials/ProfileLayout.vue";
export default {
  props: ["data", "author"],
  components: {
    ProfileLayout,
  },
  data() {
    return {
      subjects: [
        this.getValue(this.author.profile.subject_1, "name"),
        this.getValue(this.author.profile.subject_2, "name"),
        this.getValue(this.author.profile.subject_3, "name"),
        this.getValue(this.author.profile.subject_4, "name"),
        this.getValue(this.author.profile.subject_4, "name"),
      ],
      services: [
        this.getValue(this.author.profile.service_1, "name"),
        this.getValue(this.author.profile.service_2, "name"),
        this.getValue(this.author.profile.service_3, "name"),
      ],
      general: [
        { label: this.__("ID"), value: this.author.code },
        { label: this.__("Email"), value: this.author.email },
        { label: this.__("Phone"), value: this.author.phone },
        {
          label: this.__("Education Level"),
          value: this.author.profile.education_level.name,
        },
        {
          label: this.__("Years of experience"),
          value: this.author.profile.years_of_experience,
        },
        {
          label: this.__("Address"),
          value: this.author.profile.address,
        },
        {
          label: this.__("City"),
          value: this.author.profile.city,
        },
        {
          label: this.__("State"),
          value: this.author.profile.state,
        },
        {
          label: this.__("Country"),
          value: this.author.country.name,
        },
      ],
      links: [
        {
          label: this.__("Blog"),
          value: this.getValue(this.author.profile, "blog_url"),
        },
        {
          label: this.__("Portfolio"),
          value: this.getValue(this.author.profile, "online_portfolio_url"),
        },
        {
          label: this.__("Linkedin"),
          value: this.getValue(this.author.profile, "linked_in_url"),
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
  },
};
</script>
