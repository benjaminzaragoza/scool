<template>
    <v-text-field prepend-icon="person"
                  v-model="internalUsername"
                  label="Nom usuari"
                  :loading="loading"
                  @input="input()"
    ></v-text-field>
</template>

<script>
import axios from 'axios'
import withSnackbar from '../mixins/withSnackbar'

export default {
  name: 'ProposedUserComponent',
  mixins: [withSnackbar],
  data () {
    return {
      loading: true,
      internalUsername: this.username
    }
  },
  model: {
    prop: 'username',
    event: 'toggle'
  },
  props: {
    username: {},
    name: {
      type: String,
      required: true
    },
    sn1: {
      type: String,
      required: true
    }
  },
  watch: {
    name (value) {
      this.proposeFreeUserName(value, this.sn1)
    },
    sn1 (value) {
      this.proposeFreeUserName(this.name, value)
    }
  },
  methods: {
    proposeFreeUserName (name, sn1) {
      if (name && sn1) {
        axios.get('/api/v1/proposeFreeUserName/' + name + '/' + sn1).then(response => {
          this.loading = false
          this.internalUsername = response.data
          this.$emit('input', this.internalUsername)
        }).catch(error => {
          this.loading = false
          this.showError(error)
        })
      }
    },
    input () {
      this.$emit('input', this.internalUsername)
    }
  },
  created () {
    this.proposeFreeUserName(this.name, this.sn1)
  }
}
</script>
