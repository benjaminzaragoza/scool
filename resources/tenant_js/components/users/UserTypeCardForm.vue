<template>
    <v-card>
        <v-card-text>
            <user-types-select
                    :user-types="userTypes"
                    v-model="dataUserType"
                    :item-value="null"
            ></user-types-select>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    class="grey--text"
                    flat
                    @click="$emit('close')"
            >
                Cancel·lar
            </v-btn>
            <v-btn
                    color="primary"
                    :loading="loading"
                    :disabled="loading"
                    @click="change"
            >
                Canviar
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import UserTypesSelect from './UserTypesSelect'

export default {
  name: 'UserTypeCardForm',
  components: {
    'user-types-select': UserTypesSelect
  },
  data () {
    return {
      dataUserType: null,
      loading: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    userType: {
      type: Number
    },
    userTypes: {
      type: Array,
      required: true
    }
  },
  methods: {
    update () {
      window.axios.put('/api/v1/user/' + this.user.id + '/type/' + this.dataUserType.id).then(() => {
        this.$snackbar.showMessage("Típus d'usuari canviat correctament")
        this.loading = false
        this.$emit('close')
        this.$emit('changed')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    },
    remove () {
      window.axios.delete('/api/v1/user/' + this.user.id + '/type').then(() => {
        this.$snackbar.showMessage("Correcte. Ara l'usuari no té cap tipus assignat")
        this.loading = false
        this.$emit('close')
        this.$emit('changed')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    },
    change () {
      this.loading = true
      if (this.dataUserType) this.update()
      else this.remove()
    }
  },
  created () {
    if (this.userType) {
      const found = this.userTypes.find(type => type.id === this.userType)
      if (found) this.dataUserType = found
    }
  }
}
</script>
