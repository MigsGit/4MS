<script setup>
import { ref, onMounted } from 'vue'
import Multiselect from 'vue-multiselect'

const options = ref({
    test: []
})
const selectedOption = ref({
    test:''
})

const loadOptions = async () => {
  // Simulate API response
  const dropdownMasterByOpt = [
    { id: 2, dropdown_masters_details: 'test 2' },
    { id: 3, dropdown_masters_details: 'test test' }
  ]

  // Transform and assign to options
  options.value.test.splice(0, options.value.test.length,
    { value: '', label: '-Select-', disabled: true },
    { value: 'N/A', label: 'N/A' },
    ...dropdownMasterByOpt.map(item => ({
      value: item.id,
      label: item.dropdown_masters_details
    }))
  )

  // Set selected value if needed
  selectedOption.value.test = { value: 2, label: 'test 2' } // pre-selected value
}

onMounted(() => {
  loadOptions()
})
</script>

<template>
  <Multiselect
    v-model="selectedOption.test"
    :options="options.test"
    placeholder="Select an option"
    label="label"
    track-by="value"
    :searchable="true"
    :close-on-select="true"
  />
  {{selectedOption.test}}
</template>

<style scoped>
@import "vue-multiselect/dist/vue-multiselect.min.css";
</style>
