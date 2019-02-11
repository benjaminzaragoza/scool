<template>
  <v-dialog v-model="dialog" :fullscreen="$vuetify.breakpoint.xsOnly" @keydown.esc="dialog = false">
      <v-tooltip bottom slot="activator" v-if="icon">
          <v-btn
                 :small="small"
                 icon
                 :title="title"
                 flat
                 slot="activator"
                 :color="iconColor"
                 dark
                 :class="btnClass"
          >
              <v-icon :small="small" v-text="icon"></v-icon>
          </v-btn>
          <span v-text="title"></span>
      </v-tooltip>
      <v-btn v-else
                :title="title"
                flat
                slot="activator"
                color="blue darken-2"
                dark
                v-text="name"
        ></v-btn>
      <v-card>
          <v-toolbar dark color="primary">
              <v-btn icon dark @click.native="dialog = false">
                  <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title v-text="title"></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                  <v-btn dark flat @click.native="dialog = false" class="hidden-sm-and-down">
                      Sortir
                      <v-icon right dark>exit_to_app</v-icon>
                  </v-btn>
              </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
              <tree-view :data="json" :options="{  rootObjectKey: 'root' }"></tree-view>
          </v-card-text>
      </v-card>

  </v-dialog>
</template>

<script>
export default {
  name: 'JsonDialogComponent',
  data () {
    return {
      dialog: false
    }
  },
  props: {
    small: {
      default: false
    },
    iconColor: {
      default: 'primary'
    },
    btnClass: {
      type: String,
      default: ''
    },
    json: {
      required: true
    },
    objectName: {
      type: String,
      default: 'Objecte'
    },
    title: {
      type: String,
      default: 'Objecte'
    },
    name: {
      type: String,
      default: 'Objecte actual'
    },
    icon: {
      type: String,
      default: null
    }
  }
}
</script>
