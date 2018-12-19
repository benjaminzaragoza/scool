<template>
    <span>
        <span :title="study.department_code" v-text="study.department_name"></span>
        <v-btn v-role="'CurriculumManager'" icon flat color="teal" class="text--white ma-0" @click="dialog = true">
          <v-icon v-if="study.department_id">edit</v-icon>
          <v-icon v-else>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" max-width="500px">
          <v-card>
            <v-card-text>
              <department-select
                      v-model="department"
                      :departments="departments"
                      :error-messages="departmentErrors"
                      @input="$v.department.$touch()"
                      @blur="$v.department.$touch()"
              ></department-select>
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
import DepartmentSelectComponent from '../departments/DepartmentsSelectComponent'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'StudyDepartment',
  components: {
    'department-select': DepartmentSelectComponent
  },
  mixins: [validationMixin],
  validations: {
    department: { required }
  },
  data () {
    return {
      dialog: false,
      assigning: false,
      department: this.study.department_id
    }
  },
  props: {
    departments: {
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
    departmentErrors () {
      const errors = []
      if (!this.$v.department.$dirty) return errors
      !this.$v.department.required && errors.push('És obligatori indicar un departament')
      return errors
    }
  },
  methods: {
    assign () {
      this.assigning = true
      window.axios.put('/api/v1/studies/' + this.study.id + '/department/' + this.department).then(response => {
        this.dialog = false
        this.$emit('assigned', this.department)
        this.assigning = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.assigning = false
      })
    }
  }
}
</script>
