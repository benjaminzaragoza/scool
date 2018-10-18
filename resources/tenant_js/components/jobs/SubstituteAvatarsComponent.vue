<template>
    <span style="display: inline-block;">
        <v-dialog
                v-model="dialog"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
                scrollable
                @keydown.esc="dialog=false"
        >
        <v-card tile>
          <v-toolbar card dark color="primary">
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Dades substitució</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark flat @click.native="finishSubstitution()" :disabled="disableFinishSubstitutionButton()" :loading="finishingSubstitution">Finalitzar Subtitució</v-btn>
              <v-btn dark flat @click.native="modify()" :disabled="modifying" :loading="modifying">Modificar</v-btn>
              <v-btn dark color="red" @click.native="confirmRemove()" :disabled="removing" :loading="removing">Eliminar</v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
            <v-list three-line subheader>
              <v-subheader>Susbtitut</v-subheader>
              <v-list-tile avatar>
                <v-list-tile-content style="display:inline;" v-if="currentSubstitute">
                    <user-avatar :hash-id="currentSubstitute.hash_id"
                                 :alt="currentSubstitute.description + ' | Inici:' + currentSubstitute.start_at + ' - Fí:' +  currentSubstitute.end_at"
                    ></user-avatar>
                    <v-list-tile-title>{{currentSubstitute.description}}</v-list-tile-title>
                    <v-list-tile-sub-title>Substitut</v-list-tile-sub-title>
                </v-list-tile-content>
            </v-list-tile>
              <v-list-tile>
                    <v-list-tile-content>
                        <date-picker v-model="start_date" label="Data d'inici" name="start_date"></date-picker>
                    </v-list-tile-content>
              </v-list-tile>
                <v-list-tile>
                    <v-list-tile-content>
                        <date-picker v-model="end_date" label="Data fí" name="end_date"></date-picker>
                    </v-list-tile-content>
              </v-list-tile>
            </v-list>
            <v-divider></v-divider>
            <v-list three-line subheader>
              <v-subheader>Plaça que ocupa</v-subheader>
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
              <v-list-tile avatar>
                    <v-list-tile-content>
                        <user-avatar :hash-id="job.holder_hashid"
                                     :alt="job.holder_description"
                        ></user-avatar>
                        <v-list-tile-title>{{ job.holder_name }}</v-list-tile-title>
                        <v-list-tile-sub-title>Titular</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
              <v-list-tile avatar>
                    <v-list-tile-content>
                        <template v-if="job.active_user_hash_id">
                            <user-avatar :hash-id="job.active_user_hash_id"
                                         :alt="job.active_user_description"
                            ></user-avatar>
                        </template>
                        <v-list-tile-title>{{ job.active_user_description }}</v-list-tile-title>
                        <v-list-tile-sub-title>Usuari actiu</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar>
                        <v-list-tile-content style="display:inline;">
                            <user-avatar    v-for="substitute in job.substitutes" :key="substitute.id" @dblclick="showSubstituteDialog(substitute)"
                                            :hash-id="substitute.hash_id"
                                            :alt="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' + substitute.end_at"
                            ></user-avatar>
                            <v-list-tile-title>{{ substitutesNames() }}</v-list-tile-title>
                            <v-list-tile-sub-title>Substituts</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
            </v-list>
          </v-card-text>
          <div style="flex: 1 1 auto;"></div>
            <v-card-actions>
                <v-btn color="success" @click.native="finishSubstitution()" :loading="finishingSubstitution" :disabled="disableFinishSubstitutionButton()">Finalitzar Subtitució</v-btn>
                <v-btn color="primary" @click.native="modify()" :disabled="modifying" :loading="modifying">Modificar</v-btn>
                <v-btn color="red" class="white--text" @click.native="confirmRemove()" :disabled="removing" :loading="removing">Eliminar</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
      </v-dialog>
        <v-dialog v-model="confirmDialog" persistent max-width="290" @keydown.esc="confirmDialog = false">
            <v-card>
                <v-card-title class="headline">Si us plau confirmeu</v-card-title>
                <v-card-text>Esteu segurs que voleu esborrar aquesta substitució?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat @click.native="confirmDialog = false">Cancel·lar</v-btn>
                    <v-btn color="red darken-1" flat @click.native="remove()" :disabled="removing" :loading="removing" id="confirm_button">Esborrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <user-avatar
                v-for="substitute in job.substitutes"
                :key="substitute.id"
                @dblclick="showSubstituteDialog(substitute)"
                :hash-id="substitute.hash_id"
                :alt="substitute.description + ' | Inici:' + substitute.start_at + ' - Fí:' +  substitute.end_at"
        ></user-avatar>
    </span>
</template>

<script>
  import DatePicker from '../ui/DatePicker'
  import withSnackbar from '../mixins/withSnackbar'
  import axios from 'axios'
  import moment from 'moment'
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'
  import UserAvatar from '../ui/UserAvatarComponent'

  export default {
    name: 'SubstituteAvatarsComponent',
    mixins: [withSnackbar, validationMixin],
    components: {
      'date-picker': DatePicker,
      'user-avatar': UserAvatar
    },
    validations: {
      start_date: { required }
    },
    data () {
      return {
        dialog: false,
        currentSubstitute: null,
        start_date: null,
        end_date: null,
        finishingSubstitution: false,
        modifying: false,
        removing: false,
        confirmDialog: false
      }
    },
    props: {
      job: {
        required: true
      }
    },
    watch: {
      currentSubstitute (newValue) {
        if (newValue) {
          if (newValue.start_at) this.start_date = moment(newValue.start_at).format('YYYY-MM-DD')
          if (newValue.end_at) this.end_date = moment(newValue.end_at).format('YYYY-MM-DD')
        }
      }
    },
    methods: {
      disableFinishSubstitutionButton () {
        if (this.currentSubstitute && this.currentSubstitute.end_at !== null) return true
        if (this.finishingSubstitution) return true
        return false
      },
      finishSubstitution () {
        this.finishingSubstitution = true
        axios.put('/api/v1/job/' + this.job.id + '/substitution', {
          user_id: this.currentSubstitute.id,
          end_at: moment().format('YYYY-MM-DD hh:mm:ss')
        }).then(response => {
          this.finishingSubstitution = false
          this.dialog = false
          this.showMessage('Substitució finalitzada correctament')
          this.$emit('change')
        }).catch(error => {
          this.finishingSubstitution = false
          console.log(error)
          this.showError(error)
        })
      },
      modify () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.modifying = true
          let postdata = {
            user_id: this.currentSubstitute.id,
            start_at: this.start_date
          }
          if (this.end_date) postdata.end_at = this.end_date
          axios.put('/api/v1/job/' + this.job.id + '/substitution', postdata).then(response => {
            this.modifying = false
            this.dialog = false
            this.showMessage('Modificació realitzada correctament')
            this.$emit('change')
          }).catch(error => {
            this.modifying = false
            console.log(error)
            this.showError(error)
          })
        } else {
          if (this.$v.start_date.$dirty) {
            !this.$v.start_date.required && this.showError("Cal especificar una data d'inici")
          }
          this.$v.$touch()
        }
      },
      confirmRemove () {
        this.confirmDialog = true
      },
      remove () {
        this.removing = true
        axios.delete('/api/v1/job/' + this.job.id + '/substitution/' + this.currentSubstitute.id).then(response => {
          this.removing = false
          this.confirmDialog = false
          this.dialog = false
          this.showMessage("S'ha esborrat la substitució correctament")
          this.$emit('change')
        }).catch(error => {
          this.removing = false
          console.log(error)
          this.showError(error)
        })
      },
      showSubstituteDialog (substitute) {
        this.currentSubstitute = substitute
        this.dialog = true
      },
      substitutesNames () {
        return this.job.substitutes.map(substitute => substitute.name).join(', ')
      }
    }
  }
</script>
