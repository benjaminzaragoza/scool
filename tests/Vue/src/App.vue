<template>
  <div id="app">
    <birthdate-input></birthdate-input>
  </div>
</template>

<script>
import MessageToggle from '@/components/MessageToggle.vue'
import BirthdateInputComponent from '@/components/BirthdateInputComponent.vue'
import List from '@/components/List.vue'

export default {
  components: {
    MessageToggle,
    List,
    'birthdate-input':  BirthdateInputComponent
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  color: #2c3e50;
}
</style>
