<template>
    <span>
        <span :title="study.family_code" v-text="study.family_name"></span>
        <v-btn v-role="'CurriculumManager'" icon flat color="teal" class="text--white ma-0" @click="dialog = true">
          <v-icon v-if="study.family_id">edit</v-icon>
          <v-icon v-else>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
          <v-card>
            <v-card-text>
              <family-select
                      v-model="family"
                      :families="families"
                      :error-messages="familyErrors"
                      @input="$v.family.$touch()"
                      @blur="$v.family.$touch()"
              ></family-select>
            </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="dialog = false">Tancar</v-btn>
            <v-btn color="success" flat @click="assign" :loading="assigning" :disabled="assigning || this.$v.invalid">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
import FamilySelectComponent from '../families/FamilySelectComponent'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'StudyFamily',
  components: {
    'family-select': FamilySelectComponent
  },
  mixins: [validationMixin],
  validations: {
    family: { required }
  },
  data () {
    return {
      dialog: false,
      assigning: false,
      family: this.study.family_id
    }
  },
  props: {
    families: {
      type: Array,
      default: function () {
        return undefined
      }
    },
    study: {
      type: Object,
      required: true
    }
  },
  computed: {
    familyErrors () {
      const errors = []
      if (!this.$v.family.$dirty) return errors
      !this.$v.family.required && errors.push('És obligatori indicar una família')
      return errors
    }
  },
  methods: {
    assign () {
      this.assigning = true
      window.axios.put('/api/v1/studies/' + this.study.id + '/family/' + this.family).then(response => {
        this.dialog = false
        this.$emit('assigned', this.family)
        this.assigning = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.assigning = false
      })
    }
  }
}
</script>
