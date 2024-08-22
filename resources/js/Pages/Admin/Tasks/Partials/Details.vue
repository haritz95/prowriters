<template>
  <div class="row">
    <div class="col-md-3">
      <div class="p-2 task-card border">
        <div class="border-bottom mb-2 pb-2" v-if="task.bid_request">
          <small class="text-muted fw-bolder">{{ __("Bid Request") }}</small>
          <div>
            <Link :href="route('admin.bidRequests.show', task.bid_request.uuid)">{{
              task.bid_request.number
            }}</Link>
          </div>
        </div>
        <div class="border-bottom mb-2 pb-2">
          <small class="text-muted fw-bolder">{{ __("Customer") }}</small>
          <div>
            <Link :href="route('admin.customers.show', task.customer.uuid)">{{
              task.customer.full_name
            }}</Link>
          </div>
        </div>

        <div class="border-bottom mb-2 pb-2">
          <small class="text-muted fw-bolder">{{ __("Author") }}</small>
          <div v-if="task.author">
            <Link :href="route('admin.authors.show', task.author.uuid)">{{
              task.author.full_name
            }}</Link>
          </div>
          <div v-else>{{ __("Not assigned") }}</div>
        </div>

        <div class="border-bottom mb-2 pb-2">
          <small class="text-muted fw-bolder">{{ __("Editor") }}</small>
          <div v-if="task.editor">
            <Link
              v-if="hasRole(ADMIN_ROLES.SUPER_ADMIN)"
              :href="route('admin.users.show', task.editor.uuid)"
              >{{ task.editor.full_name }}</Link
            >
            <span v-else>{{ task.editor.full_name }}</span>
          </div>
          <div v-else>{{ __("Not assigned") }}</div>
        </div>

        <div
          v-for="(brief, index) in data.briefs.meta"
          class="border-bottom mb-2 pb-2"
          :key="index"
        >
          <small class="text-muted fw-bolder">{{ brief.label }}</small>
          <div class="">{{ brief.value }}</div>
        </div>

        <div
          v-for="(row, index) in commonBriefs"
          class="border-bottom mb-2 pb-2"
          :key="index"
        >
          <small class="text-muted fw-bolder">{{ row.label }}</small>
          <div class="">{{ row.value }}</div>
        </div>

        <div v-if="task.additional_services.length > 0">
          <div class="fw-bolder mt-4 mb-4">{{ __("Additional Services") }}</div>
          <div
            v-for="(service, index) in task.additional_services"
            class="border-bottom mb-2 pb-2"
            :key="index"
          >
            <div class="text-muted">{{ service.name }}</div>
            <table class="table table-sm">
              <tr>
                <td>
                  <small class="text-muted">{{ __("Quantity") }} :</small>
                </td>
                <td class="text-end">
                  <small class="text-muted">{{ service.pivot.quantity }}</small>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="border p-4">
        <div class="fw-bold">{{ __("Title") }}</div>
        <small>{{ task.title }}</small>
        <hr />

        <div class="mt-2 mb-2" v-for="(detail, index) in data.briefs.fields" :key="index">
          <div class="fw-bold">{{ detail.label }}</div>
          <small class="text-break" v-html="detail.value"></small>
          <hr />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["task", "data"],

  data() {
    return {
      commonBriefs: [
        // {
        //   label: this.__("Author Level"),
        //   value: this.task.author_level.name,
        // },

        {
          label: this.__("Created"),
          value: this.localDateTime(this.task.created_at),
        },
        {
          label: this.__("Deadline"),
          value: this.localDateTime(this.task.dead_line),
        },
        {
          label: this.__("Deadline for Author"),
          value: this.localDateTime(this.task.dead_line_for_author),
        },
        {
          label: this.__("Revisions"),
          value: this.task.revisions_taken + " / " + this.task.revisions_allowed,
        },
        {
          label: this.__("Total Price"),
          value: this.formatMoney(this.task.total),
        },
      ],
    };
  },
};
</script>
