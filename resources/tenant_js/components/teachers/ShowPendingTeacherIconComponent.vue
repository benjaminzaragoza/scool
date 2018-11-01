<template>
    <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            @keydown.esc="dialog = false"
    >
        <v-btn slot="activator" icon class="mx-0" :id="'pending_teacher_see_' + pendingTeacher.email.replace('@', '_').replace('.', '_')">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Fitxa del professor</v-toolbar-title>
            </v-toolbar>
            <v-card-text class="px-0 mb-2">
                <v-container fluid grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs12>

                            <pending-teacher-form v-if="dialog"
                                    :jobs="jobs"
                                    :specialties="specialties"
                                    :forces="forces"
                                    :administrative-statuses="administrativeStatuses"
                                    :teachers="teachers"
                                    :pending-teacher="pendingTeacher"
                                    :confirm-mode="true"
                                    @cancel="dialog = false"
                            >
                            </pending-teacher-form>
                        </v-flex>
                    </v-layout>
                </v-container>

            </v-card-text>
        </v-card>
    </v-dialog>

</template>

<style>

</style>

<script>
import { validationMixin } from 'vuelidate'
import PendingTeacher from './Mixins/PendingTeacher'
import TeacherSelect from './TeacherSelectComponent.vue'

export default {
  components: { TeacherSelect },
  mixins: [validationMixin, PendingTeacher],
  data () {
    return {
      dialog: false
    }
  },
  props: {
    pendingTeacher: {
      type: Object,
      required: true
    },
    jobs: {
      type: Array,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    },
    specialties: {
      type: Array,
      required: true
    },
    forces: {
      type: Array,
      required: true
    },
    administrativeStatuses: {
      type: Array,
      required: true
    }
  }
}
</script>
