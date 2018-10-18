<template>
    <span>
    <v-btn
            fab
            bottom
            right
            color="pink"
            dark
            fixed
            @click.stop="dialog = !dialog"
    >
        <v-icon>add</v-icon>
    </v-btn>
    <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
        <v-toolbar color="blue darken-3">
            <v-btn icon dark @click.native="dialog = false">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title class="white--text title">Afegir grup de google</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>exit</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-stepper v-model="step" vertical>
                <v-stepper-step :complete="step > 1" step="1">
                  Dades del grup
                  <small>Nom, correu electrònic, descripció</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                  <v-card class="mb-5">
                      <google-group-add-form @created="groupCreated"></google-group-add-form>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="step > 2" step="2">Membres del grup</v-stepper-step>
                <v-stepper-content step="2">
                    TODO MEMBERS
                    <v-btn color="error" @click="step = 1">Endarrera</v-btn>
                </v-stepper-content>
            </v-stepper>

        </v-card>
    </v-dialog>
    </span>
</template>

<script>
  import GoogleGroupAddForm from './GoogleGroupAddFormComponent'
  export default {
    name: 'GoogleGroupAddComponent',
    components: {
      'google-group-add-form': GoogleGroupAddForm
    },
    data () {
      return {
        dialog: false,
        step: 1,
        group: null,
      }
    },
    methods: {
      groupCreated (group) {
        this.group = group
        this.step = 2
      }
    }
  }
</script>
