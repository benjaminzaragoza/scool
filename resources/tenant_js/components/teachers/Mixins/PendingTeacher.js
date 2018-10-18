import { required, maxLength, email } from 'vuelidate/lib/validators'

export default {
  validations: {
    name: { required, maxLength: maxLength(255) },
    sn1: { required, maxLength: maxLength(255) },
    sn2: { maxLength: maxLength(255) },
    identifier: { required, maxLength: maxLength(12) },
    identifierType: { required },
    birthdate: { required },
    street: { required },
    number: { required },
    postal_code: { required },
    locality: { required },
    province: { required },
    email: { required, email },
    mobile: { required },
    phone: { required },
    degree: { required },
    specialty: { required },
    force: { required },
    start_date: { required },
    administrative_status: { required },
    teacher: { required }
    // checkbox: { required }
  },
  data () {
    return {
      loadingPostalCode: false,
      loadingLocality: false,
      loadingProvince: false,
      postalCodes: [],
      localities: [],
      provinces: [],
      searchPostalCodes: null,
      searchLocalities: null,
      searchProvinces: null,
      name: '',
      sn1: '',
      sn2: '',
      identifierType: 'NIF',
      identifierTypes: ['NIF', 'NIE', 'Passaport'],
      identifier: '',
      street: '',
      number: '',
      floor: '',
      floor_number: '',
      postal_code: '',
      locality: '',
      province: '',
      email: '',
      other_emails: [],
      mobile: '',
      other_mobiles: [],
      phone: '',
      other_phones: [],
      degree: '',
      other_degrees: '',
      languages: '',
      profiles: '',
      other_training: '',
      force: '',
      specialty: '',
      teacher_start_date: '',
      start_date: '',
      opositions_date: '',
      administrative_status: {},
      destination_place: '',
      teacher: {},
      // checkbox: false,
      birthdate: '',
      birthdateMenu: false,
      startDateMenu: false,
      identifier_photocopy: '',
      photo: '',
      username: '',
      job: {}
    }
  },
  computed: {
    // checkboxErrors () {
    //   const errors = []
    //   if (!this.$v.checkbox.$dirty) return errors
    //   !this.$v.checkbox.required && errors.push("Heu d'acceptar les condicions per poder continuar!")
    //   return errors
    // },
    selectErrors () {
      const errors = []
      if (!this.$v.select.$dirty) return errors
      !this.$v.select.required && errors.push('Item is required')
      return errors
    },
    nameErrors () {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.maxLength && errors.push('El nom ha de tenir com a màxim 255 caràcters.')
      !this.$v.name.required && errors.push('El nom és obligatori.')
      return errors
    },
    sn1Errors () {
      const errors = []
      if (!this.$v.sn1.$dirty) return errors
      !this.$v.sn1.maxLength && errors.push('El primer cognom ha de tenir com a màxim 255 caràcters.')
      !this.$v.sn1.required && errors.push('El cognom és obligatori.')
      return errors
    },
    sn2Errors () {
      const errors = []
      if (!this.$v.sn2.$dirty) return errors
      !this.$v.sn2.maxLength && errors.push('El segon cognom ha de tenir com a màxim 255 caràcters.')
      return errors
    },
    identifierTypeErrors () {
      const errors = []
      if (!this.$v.identifierType.$dirty) return errors
      !this.$v.identifierType.required && errors.push("El tipus d'identificador és obligatori.")
      return errors
    },
    identifierErrors () {
      const errors = []
      if (!this.$v.identifier.$dirty) return errors
      !this.$v.identifier.maxLength && errors.push('El identificador ha de tenir com a màxim 12 caràcters.')
      !this.$v.identifier.required && errors.push('El identificador és obligatori.')
      return errors
    },
    birthdateErrors () {
      const errors = []
      if (!this.$v.birthdate.$dirty) return errors
      !this.$v.birthdate.required && errors.push('La data de naixement és obligatòria.')
      return errors
    },
    streetErrors () {
      const errors = []
      if (!this.$v.street.$dirty) return errors
      !this.$v.street.required && errors.push('El carrer és obligatòri.')
      return errors
    },
    numberErrors () {
      const errors = []
      if (!this.$v.number.$dirty) return errors
      !this.$v.number.required && errors.push('El número de carrer és obligatòri.')
      return errors
    },
    postalCodeErrors () {
      const errors = []
      if (!this.$v.postal_code.$dirty) return errors
      !this.$v.postal_code.required && errors.push('El codi postal és obligatòri.')
      return errors
    },
    localityErrors () {
      const errors = []
      if (!this.$v.locality.$dirty) return errors
      !this.$v.locality.required && errors.push('El poble/ciutat és obligatòri.')
      return errors
    },
    provinceErrors () {
      const errors = []
      if (!this.$v.province.$dirty) return errors
      !this.$v.province.required && errors.push('La província és obligatòria.')
      return errors
    },
    emailErrors () {
      const errors = []
      if (!this.$v.email.$dirty) return errors
      !this.$v.email.email && errors.push('Ha de ser un correu electrònic vàlid')
      !this.$v.email.required && errors.push('El correu electrònic és obligatori')
      return errors
    },
    mobileErrors () {
      const errors = []
      if (!this.$v.mobile.$dirty) return errors
      !this.$v.mobile.required && errors.push('El mòbil és obligatori')
      return errors
    },
    degreeErrors () {
      const errors = []
      if (!this.$v.degree.$dirty) return errors
      !this.$v.degree.required && errors.push("La titulació d'accés és obligatoria")
      return errors
    },
    forceErrors () {
      const errors = []
      if (!this.$v.force.$dirty) return errors
      !this.$v.force.required && errors.push('El cos és obligatori')
      return errors
    },
    specialtyErrors () {
      const errors = []
      if (!this.$v.specialty.$dirty) return errors
      !this.$v.specialty.required && errors.push('La especialitat és obligatòria')
      return errors
    },
    startDateErrors () {
      const errors = []
      if (!this.$v.start_date.$dirty) return errors
      !this.$v.start_date.required && errors.push('La data és obligatòria')
      return errors
    },
    administrativeStatusErrors () {
      const errors = []
      if (!this.$v.administrative_status.$dirty) return errors
      !this.$v.administrative_status.required && errors.push('La situació administrativa és obligatòria')
      return errors
    },
    // teacherErrors () {
    //   const errors = []
    //   if (!this.$v.teacher.$dirty) return errors
    //   !this.$v.teacher.required && errors.push('Camp obligatori pels substituts')
    //   return errors
    // }
    formattedBirthdate: {
      get: function () {
        return this.formatDate(this.birthdate)
      },
      set: function (value) {
        this.birthdate = this.unformatDate(value)
      }
    },
    formatted_start_date: {
      get: function () {
        return this.formatDate(this.start_date)
      },
      set: function (value) {
        this.start_date = this.unformatDate(value)
      }
    }
  },
  props: {
    specialties: {
      type: Array,
      required: true
    },
    forces: {
      type: Array,
      required: true
    },
    administrativeStatuses: {
      type: Array,
      required: true
    },
    teachers: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatDate (date) {
      if (!date) return null
      try {
        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      } catch (error) {
        return null
      }
    },
    unformatDate (date) {
      if (!date) return null
      try {
        const [day, month, year] = date.split('/')
        return `${year}-${month}-${day}`
      } catch (error) {
        return null
      }
    },
    clear () {
      this.name = ''
      this.sn1 = ''
      this.sn2 = ''
      this.identifier = ''
      this.birthdate = ''
      this.street = ''
      this.number = ''
      this.floor = ''
      this.floor_number = ''
      this.postal_code = ''
      this.locality = ''
      this.province = ''
      this.email = ''
      this.other_emails = []
      this.phone = ''
      this.other_phones = []
      this.degree = ''
      this.other_degrees = ''
      this.languages = ''
      this.profiles = ''
      this.other_training = ''
      this.force = ''
      this.specialty = ''
      this.teacher_start_date = ''
      this.start_date = ''
      this.opositions_date = ''
      this.administrative_status = ''
      this.destination_place = ''
      this.teacher = {}
      // this.checkbox = false
    },
    saveBirthdate (date) {
      this.$refs.menu.save(date)
    }
  }
}
