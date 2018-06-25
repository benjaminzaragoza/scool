<template>
    <form>
        Codi de professor: mostrar el codi que s'assignarà (next available code) i permetre canviar?
        Departament: segons la plaça escollida es marca un departament per defecte
        <v-text-field
                label="Codi professor"
                v-model="code"
                :error-messages="codeErrors"
                @input="$v.code.$touch()"
                @blur="$v.code.$touch()"
                required
        ></v-text-field>
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
        <v-btn color="primary" @click="assign" :disabled="assigning" :loading="assigning">Assignar</v-btn>
        <v-btn color="error" @click="$emit('back')">Tornar endarrera</v-btn>
    </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'
  import axios from 'axios'
  import AdministrativeStatusSelect from './AdministrativeStatusSelectComponent'
  import SpecialtySelect from '../specialties/SpecialtySelectComponent'
  import DepartmentSelect from '../curriculum/departments/DepartmentsSelectComponent'

  export default {
    name: 'AssignTeacherInfoToUserComponent',
    mixins: [validationMixin],
    components: {
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
        code: null
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
        console.log('watched!!!')
        this.initialize()
      }
    },
    methods: {
      initialize () {
        console.log('initialize')
        this.selectDepartmentByJob(this.job)
        this.selectSpecialtyByJob(this.job)
      },
      selectDepartmentByJob (job) {
        this.department = this.jobs.filter(j => j.id === job)[0].department_id
      },
      selectSpecialtyByJob (job) {
        this.specialty = this.jobs.filter(j => j.id === job)[0].specialty_id
      },
      assign () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          console.log('Valid!')
          this.assigning = true
          axios.post('/api/v1/teacher', {
            user_id: this.user,
            code: this.code,
            // administrative_status_id: this. administrative_status_id
            // department_id: this.department.id
            // speciality
          }).then(response => {
            this.assigning = false
            this.showMessage('Informació de professor assignada correctament')
            this.$emit('assigned', response.data)
          }).catch(error => {
            this.assigning = false
            console.log(error)
            this.showError(error)
          })
        } else {
          console.log('INVALID')
          this.$v.$touch()
        }
      }
    },
    created () {
      if (this.job) this.initialize()
    }
  }
</script>
