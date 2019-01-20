<template>
    <v-card class="elevation-3">
        <v-card-text>
            <user-avatar :hash-id="user.hashid"
                         :alt="user.name"
                         :user="user"
            ></user-avatar>
            <span class="font-weight-medium">{{ user.name }}</span>:

            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <v-flex md3>
                        <v-text-field
                                label="Nom"
                                v-model="givenName"
                                :error-messages="givenNameErrors"
                                @input="$v.givenName.$touch()"
                                @blur="givenNameBlur()"
                                tabindex="1"
                                required
                        ></v-text-field>
                    </v-flex>
                    <v-flex md2>
                        <v-text-field
                                label="Cognom1"
                                v-model="sn1"
                                :error-messages="sn1Errors"
                                @input="$v.sn1.$touch()"
                                @blur="sn1Blur()"
                                tabindex="2"
                                required
                        ></v-text-field>
                    </v-flex>
                    <v-flex md2>
                        <v-text-field
                                label="Cognom2"
                                v-model="sn2"
                                @blur="sn2Blur()"
                                required
                                tabindex="3"
                        ></v-text-field>
                    </v-flex>
                    <v-flex md3>
                        <v-text-field
                                label="Nom complet"
                                v-model="fullname"
                                readonly
                                required
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>

        </v-card-text>
        <v-card-actions>
            <v-btn color="primary" @click="save" :disabled="loading || $v.$invalid" :loading="loading">
                <v-icon>save</v-icon> Guardar</v-btn>
            <v-btn flat @click="close">
                <v-icon small>close</v-icon> Sortir</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'UserNameManage',
  mixins: [validationMixin],
  components: {
    'user-avatar': UserAvatar
  },
  validations: {
    givenName: { required },
    sn1: { required }
  },
  data () {
    return {
      loading: false,
      givenName: this.user.givenName,
      sn1: this.user.sn1,
      sn2: this.user.sn2,
      fullname: this.user.name,
      errors: []
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    givenNameErrors () {
      const givenNameErrors = []
      if (!this.$v.givenName.$dirty) return givenNameErrors
      !this.$v.givenName.required && givenNameErrors.push('El nom és obligatori.')
      this.errors['givenName'] && givenNameErrors.push(this.errors['givenName'])
      return givenNameErrors
    },
    sn1Errors () {
      const sn1Errors = []
      if (!this.$v.sn1.$dirty) return sn1Errors
      !this.$v.sn1.required && sn1Errors.push('El 1r cognom és obligatori.')
      this.errors['sn1'] && sn1Errors.push(this.errors['sn1'])
      return sn1Errors
    }
  },
  methods: {
    updateFullName () {
      this.fullname = ''
      if (this.givenName) this.fullname = this.givenName
      if (this.sn1) {
        if (this.fullname === '') this.fullname = this.sn1
        else this.fullname = this.fullname + ' ' + this.sn1
      }
      if (this.sn2) {
        if (this.fullname === '') this.fullname = this.sn2
        else this.fullname = this.fullname + ' ' + this.sn2
      }
    },
    formatName (name) {
      const lc = name.toLowerCase()
      return lc.charAt(0).toUpperCase() + lc.slice(1)
    },
    givenNameBlur () {
      this.$v.givenName.$touch()
      if (this.givenName) this.givenName = this.formatName(this.givenName)
      this.updateFullName()
    },
    sn1Blur () {
      this.$v.sn1.$touch()
      if (this.sn1) this.sn1 = this.formatName(this.sn1)
      this.updateFullName()
    },
    sn2Blur () {
      if (this.sn2) this.sn2 = this.formatName(this.sn2)
      this.updateFullName()
    },
    close () {
      this.$emit('close')
    },
    save () {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        window.axios.put('/api/v1/user_person/' + this.user.id, {
          givenName: this.givenName,
          sn1: this.sn1,
          sn2: this.sn2,
          name: this.fullname
        }).then(response => {
          this.loading = false
          this.$v.$reset()
          this.$emit('saved', this.user)
        }).catch(error => {
          if (error && error.status === 422) {
            this.errors = error.data && error.data.errors
            this.loading = false
            this.$snackbar.showError(error)
          }
        })
      }
    }

  }
}
</script>
