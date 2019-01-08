<template>
    <span>
        <v-btn
                fab
                bottom
                right
                color="accent"
                dark
                fixed
                @click.stop="toggle"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="toggle">
            <v-toolbar color="primary">
                <v-btn icon dark @click.native="toggle">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title" v-html="title"></v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <slot></slot>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
export default {
  name: 'FloatingAddComponent',
  model: {
    prop: 'dialog',
    event: 'toggle'
  },
  props: {
    dialog: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    }
  },
  methods: {
    toggle () {
      this.$emit('toggle', !this.dialog)
    }
  }
}
</script>
