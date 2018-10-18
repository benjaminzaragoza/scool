<template>
    <v-dialog
            v-model="dialog"
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            @keydown.esc="dialog = false"
    >
        <v-btn icon slot="activator" title="Afegir titular" style="margin: 0px">
            <v-icon color="teal">add</v-icon>
        </v-btn>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Afegir titular</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="addHolder()" :disabled="adding" :loading="adding">Afegir</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text>
                <v-list three-line subheader>
                    <v-subheader>Plaça</v-subheader>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>Especialitat, família, codi i notes</v-list-tile-title>
                            <v-list-tile-sub-title> {{ job.specialty_description }} | {{ job.family_description }} | {{ job.fullcode }} | {{ job.notes }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ job.type }}</v-list-tile-title>
                            <v-list-tile-sub-title>Tipus plaça</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-subheader>Titular</v-subheader>

                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <user-select
                                    name="holder"
                                    label="Escolliu un titular"
                                    :users="internalUsers"
                                    v-model="holder"
                                    :error-messages="holderErrors"
                                    @input="$v.holder.$touch()"
                                    @blur="$v.holder.$touch()"
                            ></user-select>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            </v-card-text>
            <v-card-actions>
                <v-btn color="success" @click="addHolder()" :disabled="adding" :loading="adding">Afegir</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
  import * as actions from '../../store/action-types'
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import { required } from 'vuelidate/lib/validators'
  import UserSelect from '../users/UsersSelectComponent.vue'

  export default {
    name: 'AddHolderToJobIconComponent',
    components: {
      'user-select': UserSelect
    },
    mixins: [validationMixin, withSnackbar],
    data () {
      return {
        dialog: false,
        adding: false,
        holder: null,
        internalUsers: this.users
      }
    },
    validations: {
      holder: {required}
    },
    props: {
      job: {
        type: Object,
        required: true
      },
      users: {
        type: Array,
        required: true
      }
    },
    computed: {
      holderErrors () {
        const errors = []
        if (!this.$v.holder.$dirty) return errors
        !this.$v.holder.required && errors.push('Cal especificar un titular per a la plaça.')
        return errors
      }
    },
    watch: {
      users () {
        this.internalUsers = this.users
      }
    },
    methods: {
      addHolder () {
        if (!this.$v.$invalid) {
          this.adding = true
          this.$store.dispatch(actions.EDIT_JOB, {
            id: this.job.id,
            type: this.job.type_id,
            code: this.job.code,
            family: this.job.family_id,
            specialty: this.job.specialty_id,
            holder: this.holder,
            order: this.job.order,
            notes: this.job.notes
          }).then(response => {
            this.adding = false
            this.dialog = false
            this.showMessage('Titular afegir correctament al a plaça')
          }).catch(error => {
            this.adding = false
            console.log(error)
            if (error.status === 422) this.mapErrors(error.data.errors)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      }
    }
  }
</script>
