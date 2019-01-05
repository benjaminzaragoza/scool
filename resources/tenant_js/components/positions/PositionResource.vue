<template>
    <span>
        <span :title="position.resource_name" v-text="position.resource_code"></span>
        <v-btn v-role="'PositionsManager'" icon flat color="teal" class="text--white ma-0" @click="dialog = true">
          <v-icon v-if="position.resource_id">edit</v-icon>
          <v-icon v-else>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
          <v-card>
            <v-card-text>
              TODO SELECT
            </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="dialog = false">Tancar</v-btn>
            <v-btn color="success" flat @click="assign" :loading="assigning" :disabled="assigning || $v.$invalid">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'PositionResource',
  components: {
    // 'resource-select': ResourceSelectComponent
  },
  mixins: [validationMixin],
  validations: {
    resource: { required }
  },
  data () {
    return {
      dialog: false,
      assigning: false,
      resource: this.position.resource_id
    }
  },
  props: {
    position: {
      type: Object,
      required: true
    }
  },
  computed: {
    resourceErrors () {
      const errors = []
      if (!this.$v.resource.$dirty) return errors
      !this.$v.resource.required && errors.push('És obligatori indicar un recurs')
      return errors
    }
  },
  methods: {
    assign () {
      if (!this.$v.$invalid) {
        this.assigning = true
        window.axios.put('/api/v1/studies/' + this.position.id + '/resource/' + this.resource).then(response => {
          this.dialog = false
          this.$emit('assigned', this.resource)
          this.assigning = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.assigning = false
        })
      }
    }
  }
}
</script>
