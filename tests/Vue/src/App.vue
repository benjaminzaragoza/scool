<template>
  <div id="app">
    <birthdate-input label="PROVA"></birthdate-input>
  </div>
</template>

<script>

import BirthdateInputComponent from '../../../resources/assets/tenant_js/components/ui/BirthDateInputComponent.vue'

export default {
  components: {
    'birthdate-input': BirthdateInputComponent
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  color: #2c3e50;
}
</style>
