<template>
    <v-dialog v-if="show" v-model="show" max-width="290">
        <v-card>
            <v-card-title class="headline" v-text="title"></v-card-title>
            <v-card-text v-text="message"></v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn
                        color="green darken-1"
                        flat="flat"
                        @click="close"
                >
                    Cancel·lar
                </v-btn>

                <v-btn
                        color="green darken-1"
                        flat="flat"
                        @click="close"
                        v-text="confirmText"
                ></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'
import * as getter from '../../store/getter-types.js'
import * as actions from '../../store/action-types'

export default {
  name: 'ConfirmDialog',
  computed: {
    ...mapGetters({ show: getter.CONFIRM_DIALOG_SHOW })
  },
  props: {
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      required: true
    },
    confirmText: {
      type: String,
      required: true
    }
  },
  methods: {
    close () {
      this.$store.dispatch(actions.SET_CONFIRM_DIALOG_SHOW, false)
    }
  }
}
</script>
