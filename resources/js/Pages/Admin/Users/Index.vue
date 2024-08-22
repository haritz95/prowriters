<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <AddButton :href="route('admin.users.create')" />
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['users', 'filters']"
        />       
      </div>
      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="users.links"
          :total="users.total"
          :only="['users', 'filters']"
        >
          <template v-slot>
            <tr v-for="(user, index) in users.data" :key="index">
              <td class="align-middle">
                <img
                  :src="user.small_avatar"
                  class="avatar rounded-circle"
                />
              </td>
              <td class="align-middle">
                <Link :href="route('admin.users.show', user.uuid)">{{
                  user.full_name
                }}</Link>               
                <div v-if="user.roles.length > 0" class="mt-2">                  
                  <small class="badge text-bg-light">{{  data.roles[user.roles[0].name] }}</small>
                </div>
                <div v-if="user.inactive" class="mt-2">
                  <span class="badge text-bg-danger">{{ __("Inactive") }}</span>
                </div>
              </td>
              <td class="align-middle">
                <div>
                  <i class="fa-regular fa-envelope"></i> {{ user.email }}
                </div>
                <div class="mt-2">
                  <i class="fa-solid fa-phone"></i> {{ user.phone }}
                </div>
              </td>
              <td class="align-middle">
                {{ localDateTime(user.last_login_at) }}
              </td>
            </tr>
          </template>
        </Table>
      </div>
    </div>
  </div>
</template>

<script>
import Search from "./Partials/Search.vue";
import Table from "../../../components/Table.vue";

export default {
  props: ["data", "users", "filters"],
  components: {
    Table,
    Search,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: "",
            className: "",
          },
          {
            name: this.__("Name"),
            className: "",
          },
          {
            name: this.__("Email"),
            className: "",
          },
          {
            name: this.__("Last login"),
            className: "",
          },
        ],
      },
    };
  },
};
</script>