<template>
    <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            @keydown.esc="dialog = false"
    >
        <v-btn icon slot="activator" title="Afegir substitut" style="margin: 0px" :disabled="activeSubstitute">
            <v-icon color="teal">add</v-icon>
        </v-btn>
        <v-card tile>
            <v-toolbar card dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Afegir substitut</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="addSubstitute()" :disabled="adding" :loading="adding">Afegir</v-btn>
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
                            <substitute-avatars :job="job"></substitute-avatars>
                            <v-list-tile-title>{{ substitutesNames() }}</v-list-tile-title>
                            <v-list-tile-sub-title>Substituts</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
                <v-list three-line subheader>
                    <v-subheader>Substitut</v-subheader>
                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <available-users v-if="dialog" :job-type="job.type_id" v-model="user"></available-users>
                        </v-list-tile-content>
                    </v-list-tile>

                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <date-picker v-model="start_date" label="Data d'inici" name="start_date"></date-picker>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            </v-card-text>
            <v-card-actions>
                <v-btn color="success" @click="addSubstitute()" :disabled="adding" :loading="adding">Afegir</v-btn>
                <v-btn flat @click="dialog=false">Sortir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import AvailableUsers from './AvailableUsersComponent'
import withSnackbar from '../mixins/withSnackbar'
import axios from 'axios'
import moment from 'moment'
import DatePicker from '../ui/DatePicker'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import SubstituteAvatars from './SubstituteAvatarsComponent'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'AddSubstituteIconComponent',
  mixins: [withSnackbar, validationMixin],
  components: {
    'available-users': AvailableUsers,
    'date-picker': DatePicker,
    'substitute-avatars': SubstituteAvatars,
    'user-avatar': UserAvatar
  },
  validations: {
    start_date: { required },
    user: { required }
  },
  data () {
    return {
      user: {},
      dialog: false,
      start_date: moment(new Date()).format('YYYY-MM-DD'),
      adding: false
    }
  },
  props: {
    job: {
      type: Object,
      required: true
    }
  },
  computed: {
    activeSubstitute () {
      if (this.job.substitutes.filter(substitute => substitute.end_at == null).length > 0) return true
      if (this.job.substitutes.filter(substitute => {
        return moment(substitute.end_at).isAfter(moment())
      }).length > 0) return true
      return false
    }
  },
  methods: {
    substitutesNames () {
      return this.job.substitutes.map(substitute => substitute.name).join(', ')
    },
    addSubstitute () {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.adding = true
        axios.post('/api/v1/job/' + this.job.id + '/substitution', {
          user: this.user.id,
          start_at: this.start_date
        }).then(response => {
          this.showMessage('Substitut afegit correctament')
          this.dialog = false
          this.adding = false
          this.$emit('change')
        }).catch(error => {
          this.adding = false
          this.showError(error)
        })
      } else {
        if (this.$v.user.$dirty) {
          !this.$v.user.required && this.showError('Cal escollir un usuari com a substitut')
        }
        if (this.$v.start_date.$dirty) {
          !this.$v.start_date.required && this.showError("Cal indicar una data d'inici de la substitució")
        }
        this.$v.$touch()
      }
    }
  }
}
</script>
