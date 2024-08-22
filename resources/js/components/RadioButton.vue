<template>
  <div class="btn-group d-flex" role="group" aria-label="Basic radio toggle button group">  
    <template v-for="(row, index) in options" :key="index">
      <input v-model="selectedOption" v-bind:value="row.id" type="radio" class="btn-check" :id="'btnradio_' + row.id" autocomplete="off">
       <label class="btn btn-outline-primary" :for="'btnradio_' + row.id"> {{ row.name }}</label>
    </template> 
  </div>
</template>
<script>
export default {
  props: ["options", "default_value"],
  watch: {
    options: function (newOption) {
      if (newOption) {
        this.selectedOption = this.default_value
          ? this.default_value
          : null;
     
      }
    }, 
    selectedOption: function (newOption) {
      this.$emit("update:modelValue", newOption);
    },
    default_value: function (value) {
      this.selectedOption = value;     
    }    
  },
  data() {
    return {
      selectedOption: this.default_value,
    };
  }
};
</script>
