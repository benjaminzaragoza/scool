<template>
    <form>
        <teacher-code
                :error-messages="codeErrors"
                v-model="code"
        ></teacher-code>
        <administrative-status-select
                :administrative-statuses="administrativeStatuses"
                v-model="administrativeStatus"
                label="Escolliu l'estatus administratiu del professor"
                :error-messages="administrativeStatusErrors"
                @input="$v.administrativeStatus.$touch()"
                @blur="$v.administrativeStatus.$touch()"
        ></administrative-status-select>
        <specialty-select
                :specialties="specialties"
                name="specialty"
                label="Especialitat"
                :error-messages="specialtyErrors"
                @input="$v.specialty.$touch()"
                @blur="$v.specialty.$touch()"
                v-model="specialty"
                :required="false"
        ></specialty-select>
        <department-select
                :departments="departments"
                name="department"
                label="Departament"
                :error-messages="departmentErrors"
                @input="$v.department.$touch()"
                @blur="$v.department.$touch()"
                v-model="department"
                :required="false"
        ></department-select>
        <v-btn v-if="!teacher" color="primary" @click="assign" :disabled="assigning" :loading="assigning">Assignar</v-btn>
        <v-btn v-else color="error" @click.native="remove" :disabled="removing" :loading="removing">Eliminar</v-btn>
        <v-btn color="error" @click="$emit('back')">Tornar endarrera</v-btn>
    </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'
  import axios from 'axios'
  import TeacherCode from './TeacherCodeComponent'
  import AdministrativeStatusSelect from './AdministrativeStatusSelectComponent'
  import SpecialtySelect from '../specialties/SpecialtySelectComponent'
  import DepartmentSelect from '../curriculum/departments/DepartmentsSelectComponent'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    name: 'AssignTeacherInfoToUserComponent',
    mixins: [validationMixin, withSnackbar],
    components: {
      'teacher-code': TeacherCode,
      'administrative-status-select': AdministrativeStatusSelect,
      'specialty-select': SpecialtySelect,
      'department-select': DepartmentSelect
    },
    validations: {
      code: { required },
      administrativeStatus: { required },
      specialty: { required },
      department: { required }
    },
    data () {
      return {
        administrativeStatus: null,
        specialty: null,
        department: null,
        assigning: false,
        code: null,
        teacher: null,
        removing: false
      }
    },
    props: {
      user: {
        required: true
      },
      job: {
        required: true
      },
      jobs: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      },
      specialties: {
        type: Array,
        required: true
      },
      departments: {
        type: Array,
        required: true
      }
    },
    computed: {
      administrativeStatusErrors () {
        const errors = []
        if (!this.$v.administrativeStatus.$dirty) return errors
        !this.$v.administrativeStatus.required && errors.push("Cal escollir l'estatus administratiu del profesor")
        return errors
      },
      codeErrors () {
        const errors = []
        if (!this.$v.code.$dirty) return errors
        !this.$v.code.required && errors.push('El codi de professor és obligatori')
        return errors
      },
      specialtyErrors () {
        const errors = []
        if (!this.$v.specialty.$dirty) return errors
        !this.$v.specialty.required && errors.push('Cal indicar la especialitat')
        return errors
      },
      departmentErrors () {
        const errors = []
        if (!this.$v.department.$dirty) return errors
        !this.$v.department.required && errors.push('Cal indicar el departament')
        return errors
      }
    },
    watch: {
      job (newJob) {
        this.initialize()
      }
    },
    methods: {
      initialize () {
        this.selectDepartmentByJob(this.job)
        this.selectSpecialtyByJob(this.job)
      },
      selectDepartmentByJob (job) {
        this.department = this.jobs.filter(j => j.id === job)[0].department_id
      },
      selectSpecialtyByJob (job) {
        this.specialty = this.jobs.filter(j => j.id === job)[0].specialty_id
      },
      remove () {
        this.removing = true
        if (this.teacher) {
          axios.delete('/api/v1/teachers/' + this.teacher.id).then(response => {
            this.removing = false
            this.teacher = null
            this.$v.$reset()
            this.$emit('deleted', this.user)
            this.showMessage('Dades del professor eliminades correctament')
          }).catch(error => {
            this.removing = false
            this.showError(error)
          }).then(() => {
            this.removing = false
          })
        }
      },
      assign () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.assigning = true
          axios.post('/api/v1/teachers', {
            user_id: this.user,
            code: this.code,
            administrative_status_id: this.administrativeStatus,
            department_id: this.department,
            specialty_id: this.specialty
          }).then(response => {
            this.assigning = false
            this.showMessage('Informació de professor assignada correctament')
            this.$emit('assigned', response.data)
            this.teacher = response.data
          }).catch(error => {
            console.log(error)
            this.assigning = false
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      }
    },
    created () {
      if (this.job) this.initialize()
    }
  }
</script>
