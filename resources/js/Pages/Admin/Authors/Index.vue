<template>
  <AppHead :title="data.title" />
  <div class="container page-container">
    <PageTitle :title="data.title">
      <AddButton :href="data.urls.new_item" />
    </PageTitle>

    <div class="row">
      <div class="col-md-3">
        <Search
          :data="data"
          :filters="filters.filters"
          :only="['authors', 'filters']"
        />
      </div>

      <div class="col-md-9">
        <Table
          :options="tableOptions"
          :links="authors.links"
          :total="authors.total"
          :only="['authors', 'filters']"
        >
          <template v-slot>
            <tr v-for="(author, index) in authors.data" :key="index">
              <td class="align-middle">
                <img
                  :src="author.small_avatar"
                  class="avatar rounded-circle"
                />
              </td>
              <td class="align-middle">
                <Link
                  :href="route('admin.authors.show', author.uuid)"
                  >{{ author.full_name }}</Link
                >
                <div>
                  <small class="text-muted"
                    >{{ __("ID") }}: {{ author.code }}</small
                  >
                </div>
                <div>
                  <small class="text-muted"
                    ><i class="fa-solid fa-medal"></i> {{ __("Level") }} :
                    <span class="text-danger">{{
                      author.author_level_name
                    }}</span></small
                  >
                </div>
                <div v-if="author.inactive"><span class="badge text-bg-danger">{{ __('Inactive')}}</span></div>
              </td>
              <td class="align-middle">
                <div><i class="fa-regular fa-envelope"></i> {{ author.email }}</div>
                <div> <i class="fa-solid fa-phone"></i> {{ author.phone }}</div>
              </td>              
              <td class="align-middle">                
                {{ localDateTime(author.last_login_at) }}
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
  props: ["data", "authors", "filters"],
  components: {
    Table,  
    Search,
  },
  data() {
    return {
      tableOptions: {
        titles: [
          {
            name: '',
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