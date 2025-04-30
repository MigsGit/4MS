<script setup>
import { ref } from 'vue'
import Multiselect from 'vue-multiselect'

const selected = ref( null )
const options = [
  { value: '', label: '-Select-' },
  { value: '1', label: 'Option 1' },
  { value: '2', label: 'Option 2' },
  { value: 'N/A', label: 'N/A' }
]
selected.value =  { value: '1', label: 'Option 1' };
console.log('selected',selected);
console.log('options',options);

</script>

<template>
  <div class="container">
    <h2>Vue Multiselect Example</h2>

    <Multiselect
      v-model="selected"
      :options="options"
      :searchable="true"
      :close-on-select="true"
      label="label"
      track-by="value"
      placeholder="-Select-"
    />

    <p>Selected: {{ selected }}</p>
  </div>
</template>

<style>
@import "https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css";

.container {
  max-width: 400px;
  margin: 2rem auto;
  padding: 1rem;
}
</style>
