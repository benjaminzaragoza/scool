<template>
    <span>
        <v-btn flat icon :title="title" color="primary" dark class="ma-0" @click="toggle">
            <v-icon>visibility</v-icon>
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <slot></slot>
        </v-dialog>
    </span>
</template>

<script>
export default {
  name: 'FullScreenDialog',
  model: {
    prop: 'dialog',
    event: 'toggle'
  },
  props: {
    resource: {
      type: Object,
      required: false
    },
    dialog: {},
    title: {
      type: String,
      default: ''
    }
  },
  methods: {
    toggle () {
      if (this.resource) this.$emit('toggle', this.resource.id)
      else this.$emit('toggle', !this.dialog)
    }
  }
}
</script>
