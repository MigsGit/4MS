<script setup>
import { ref, onMounted } from 'vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const options = ref({
    test: []
})
const selected = ref({
    test: null
}) // selected value here

const loadOptions = () => {
  const dropdownMasterByOpt = [
    {
      id: 2,
      dropdown_masters_details: "test 2"
    },
    {
      id: 3,
      dropdown_masters_details: "test test"
    }
  ]

  options.value.test = [
    { value: '', label: '-Select-', disabled: true },
    { value: 'N/A', label: 'N/A' },
    ...dropdownMasterByOpt.map(item => ({
      value: item.id,
      label: item.dropdown_masters_details
    }))
  ]

  // Set pre-selected value (must match one of the `value`s above)
  selected.value.test = 3 // or 'N/A' or '' depending on use case
}

onMounted(() => {
  loadOptions()
})
</script>

<template>
  <Multiselect
    v-model="selected.test"
    :options="options.test"
    placeholder="Select an option"
    :searchable="true"
    :close-on-select="true"
  />
</template>