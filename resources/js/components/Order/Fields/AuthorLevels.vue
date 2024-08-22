<template>
  <div class="mt-4">
    <h5 class="order-form-section-title">
      {{ label }}
    </h5>

    <div
      class="list-group mx-0 p-2"
      v-for="(row, index) in author_levels"
      v-bind:key="row.id"
    >
      <label
        class="list-group-item d-flex gap-2"
        @click.prevent="handleSelectAuthorLevel(row.id)"
      >
        <input
          class="form-check-input flex-shrink-0"
          type="radio"               
          :name="'btnradio_' + row.id"
        :id="'btnradio_' + row.id"
        autocomplete="off"

          v-bind:value="row.id"
          v-model="model"
        />
        <span>
          <div>{{ row.name }}</div>
          <small class="d-block text-muted">{{ row.description }}</small>
        </span>
      </label>
      <!-- <div v-if="row.is_popular" class="ribbon blue">
              <span>{{ __("Popular") }}</span>
            </div> -->
    </div>
  </div>
</template>

<script>
export default {
  props: ["modelValue", "author_levels", "label"],
  emits: ["update:modelValue"],
  computed: {
    model: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit("update:modelValue", value);
      },
    },
  },
  methods: {
    handleSelectAuthorLevel(id) {
      this.$emit("update:modelValue", id);
    },
  },
};
</script>

<style lang="scss" scoped>
$primary-color: #6f7dfb;

.author-level-box {
  cursor: pointer;
  position: relative;
  border: 1px solid #373f59 !important;

  .name {
    margin-bottom: 15px;
    font-weight: bold;
    letter-spacing: 0.5px;
    text-__form: uppercase;
    __ition: color 0.2s;
  }
}

.author-level-box:hover {
  background: #fefbf5;
  border: 1px solid $primary-color !important;
}

.author-level-box.active {
  color: #fff;
  background: linear-gradient(180deg, #6687f0 0, #8093db 100%);
  border: 2px solid #6787f0;
}

.author-level-box.active::before {
  font-family: "Font Awesome 5 Free";
  content: "\f00c";
  color: $primary-color;
  font-weight: 900;
  display: block;
  position: absolute;
  height: 13px;
  width: 15px;
  top: 9px;
  right: 9px;
}

.ribbon {
  position: absolute;
  right: -5px;
  top: -5px;
  z-index: 1;
  overflow: hidden;
  width: 93px;
  height: 93px;
  text-align: right;
}
.ribbon span {
  font-size: 0.8rem;
  color: #fff;
  text-__form: uppercase;
  text-align: center;
  font-weight: bold;
  line-height: 32px;
  __form: rotate(45deg);
  width: 125px;
  display: block;
  background: #79a70a;
  background: linear-gradient(#9bc90d 0%, #79a70a 100%);
  box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
  position: absolute;
  top: 17px; // change this, if no border
  right: -29px; // change this, if no border
}

.ribbon span::before {
  content: "";
  position: absolute;
  left: 0px;
  top: 100%;
  z-index: -1;
  border-left: 3px solid #79a70a;
  border-right: 3px solid __parent;
  border-bottom: 3px solid __parent;
  border-top: 3px solid #79a70a;
}
.ribbon span::after {
  content: "";
  position: absolute;
  right: 0%;
  top: 100%;
  z-index: -1;
  border-right: 3px solid #79a70a;
  border-left: 3px solid __parent;
  border-bottom: 3px solid __parent;
  border-top: 3px solid #79a70a;
}

.blue span {
  background: linear-gradient(#2989d8 0%, #1e5799 100%);
}
.blue span::before {
  border-left-color: #1e5799;
  border-top-color: #1e5799;
}
.blue span::after {
  border-right-color: #1e5799;
  border-top-color: #1e5799;
}

.author-level-box.active .blue span {
  background: #da722c;
}

.separator {
  display: flex;
  align-items: center;
  text-align: center;
}

.separator::before,
.separator::after {
  content: "";
  flex: 1;
  border-bottom: 1px solid #eee;
}

.separator:not(:empty)::before {
  margin-right: 0.25em;
}

.separator:not(:empty)::after {
  margin-left: 0.25em;
}
</style>
