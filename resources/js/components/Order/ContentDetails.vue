<template>
  <h5 class="card-title">{{ __("Project Brief") }}</h5>
  <hr />
  <Input
    v-model="$store.state.form.title"
    name="title"
    :label="__('Title')"
    :required="true"
    :placeholder="__('Enter the title')"
  />

  <TextArea
    v-model="$store.state.form.content_goals"
    name="content_goals"
    :label="__('Content Goals & Things to Mention')"
    :required="true"
  />
  
  <Select
    v-model="$store.state.form.grammatical_person_id"
    :options="$store.state.data.grammatical_people"
    :label="__('Grammatical Person')"
    name="grammatical_person_id"    
  />
  <TextArea
    v-model="$store.state.form.target_audience"
    :label="__('Target Audience')"
    name="grammatical_person_id" 
  />
  <TextArea
    v-model="$store.state.form.target_keywords"
    :label="__('Target Keywords')"
    name="target_keywords"
  />
  <TextArea
    v-model="$store.state.form.links_to_example_content"
    :label="__('Links to Example Content')"
    name="links_to_example_content"
  />
  <TextArea
    v-model="$store.state.form.style_and_tone"
    :label="__('Style & Tone')"
    name="style_and_tone"
  />
  <TextArea
    v-model="$store.state.form.structure_and_formatting_requirements"
    :label="__('Structure & Formatting Requirements')"
    name="structure_and_formatting_requirements"
  />
  <TextArea
    v-model="$store.state.form.referencing_and_linking_preferences"
    :label="__('Referencing & Linking Preferences')"
    name="referencing_and_linking_preferences"
  />
  <TextArea
    v-model="$store.state.form.things_to_avoid"
    :label="__('Things to Avoid')"
    name="things_to_avoid"
  />

  <TextArea
    name="additional_notes"
    v-model="$store.state.form.additional_notes"
    :label="__('Additional Notes')"
    :placeholder="__('Include any additional notes here')"    
  />
</template>

<script>
import { TextArea, Input, Select } from "../Form/Index.js";

export default {
  inject: ["$store"],
  components: {
    TextArea,
    Input,
    Select,
  },
};
</script>
