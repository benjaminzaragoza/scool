<template>
    <v-dialog v-model="value" :fullscreen="$vuetify.breakpoint.xsOnly" :max-width="maxWidth" @keydown.esc="internalValue = false">
        <slot></slot>
    </v-dialog>
</template>

<script>
export default {
  name: 'DialogComponent',
  data () {
    return {
      internalValue: this.value
    }
  },
  props: {
    value: {},
    maxWidth: {
      type: String,
      default: '650px'
    },
    fullscreen: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    internalValue (newValue) {
      this.$emit('input', newValue)
    }
  }
}
</script>
