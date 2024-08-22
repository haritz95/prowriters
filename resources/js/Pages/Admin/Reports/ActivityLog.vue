<template>
    <AppHead :title="data.title" />
    <div class="container page-container">
      <PageTitle :title="data.title" />
      <div class="row">    
        <div class="col-md-12">        
            
            <pre>{{ activities }}</pre>
       
        </div>
      </div>
    </div>
  </template>
    
    <script>
  import Pagination from "../../../components/Pagination.vue";
  import Star from "../../../components/Star.vue";
  
  export default {
    components: {
      Pagination,
      Star,
    },
    props: ["data", "activities"],
    data() {
      return {
        tableOptions: {
          titles: [
            {
              name: this.__("Details"),
              className: "col-md-3",
            },
            {
              name: this.__("Deadline"),
              className: "col-md-5",
            },
            {
              name: this.__("Status"),
              className: "col-md-2",
            },
            {
              name: this.__("Earnings"),
              className: "col-md-2 text-end",
            },
          ],
        },
      };
    },
  };
  </script>
    