<template>
    <form>
        <h1 class="subheading primary--text">
            <div>
                <p>Dades personals</p>
            </div>
        </h1>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md3>
                    <v-text-field
                            name="name"
                            label="Nom"
                            v-model="name"
                            :error-messages="nameErrors"
                            :counter="255"
                            @input="$v.name.$touch()"
                            @blur="$v.name.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            name="sn1"
                            label="1r Cognom"
                            v-model="sn1"
                            :error-messages="sn1Errors"
                            :counter="255"
                            @input="$v.sn1.$touch()"
                            @blur="$v.sn1.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-text-field
                            name="sn2"
                            label="2n Cognom"
                            v-model="sn2"
                            :error-messages="sn2Errors"
                            :counter="255"
                            @input="$v.sn2.$touch()"
                            @blur="$v.sn2.$touch()"
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <v-select
                            name="identifierType"
                            label="Tipus id"
                            autocomplete
                            required
                            combobox
                            clearable
                            :error-messages="identifierTypeErrors"
                            @input="$v.identifierType.$touch()"
                            @blur="$v.identifierType.$touch()"
                            :items="identifierTypes"
                            v-model="identifierType"
                    ></v-select>
                </v-flex>
                <v-flex md1>
                    <v-text-field
                            name="identifier"
                            label="DNI/NIE/Passaport"
                            v-model="identifier"
                            :error-messages="identifierErrors"
                            @input="$v.identifier.$touch()"
                            @blur="$v.identifier.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-menu
                            ref="menu"
                            lazy
                            :close-on-content-click="false"
                            v-model="birthdateMenu"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            min-width="290px"
                    >
                        <v-text-field
                                name="formattedBirthdate"
                                hint="format DD/MM/AAAA"
                                persistent-hint
                                slot="activator"
                                label="Data de naixement"
                                :value="formattedBirthdate" @change.native="formattedBirthdate = $event.target.value"
                                :error-messages="birthdateErrors"
                                @input="$v.birthdate.$touch()"
                                @blur="$v.birthdate.$touch()"
                                prepend-icon="event"
                        ></v-text-field>
                        <v-date-picker
                                ref="picker"
                                locale="ca"
                                :value="birthdate" @change.native="birthdate = $event.target.value"
                                @change="saveBirthdate"
                                min="1900-01-01"
                                :max="new Date().toISOString().substr(0, 10)"
                        ></v-date-picker>
                    </v-menu>
                </v-flex>
            </v-layout>
        </v-container>

        <h1 class="subheading primary--text">
            <div>
                <p>Adreça</p>
            </div>
        </h1>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md3>
                    <v-text-field
                            name="street"
                            label="Adreça"
                            hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
                            v-model="street"
                            :error-messages="streetErrors"
                            :counter="255"
                            @input="$v.street.$touch()"
                            @blur="$v.street.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <v-text-field
                            name="number"
                            label="Número"
                            v-model="number"
                            :error-messages="numberErrors"
                            @input="$v.number.$touch()"
                            @blur="$v.number.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <v-text-field
                            name="floor"
                            label="Pis"
                            v-model="floor"
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <v-text-field
                            name="floor_number"
                            label="# pis"
                            v-model="floor_number"
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <v-select
                            name="postal_code"
                            label="Codi postal"
                            autocomplete
                            :loading="loadingPostalCode"
                            cache-items
                            required
                            combobox
                            clearable
                            :error-messages="postalCodeErrors"
                            @input="$v.postal_code.$touch()"
                            @blur="$v.postal_code.$touch()"
                            :items="postalCodes"
                            :search-input.sync="searchPostalCodes"
                            v-model="postal_code"
                    ></v-select>
                </v-flex>
                <v-flex md3>
                    <v-select
                            name="locality"
                            label="Localitat"
                            tabindex = "-1"
                            item-text="name"
                            autocomplete
                            :loading="loadingLocality"
                            cache-items
                            required
                            combobox
                            clearable
                            :items="localities"
                            :search-input.sync="searchLocalities"
                            :error-messages="localityErrors"
                            v-model="locality"
                            @input="$v.locality.$touch()"
                            @blur="$v.locality.$touch()"
                    ></v-select>
                </v-flex>
                <v-flex md2>
                    <v-select
                            name="province"
                            label="Província"
                            tabindex = "-1"
                            item-text="name"
                            autocomplete
                            :loading="loadingProvince"
                            cache-items
                            required
                            combobox
                            clearable
                            :items="provinces"
                            :search-input.sync="searchProvinces"
                            v-model="province"
                            :error-messages="provinceErrors"
                            @input="$v.province.$touch()"
                            @blur="$v.province.$touch()"
                    ></v-select>
                </v-flex>
            </v-layout>
        </v-container>


        <h1 class="subheading primary--text">
            <div>
                <p>Contacte</p>
            </div>
        </h1>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md2>
                    <v-text-field
                            name="emailfield"
                            label="Correu electrònic"
                            v-model="email"
                            :error-messages="emailErrors"
                            @input="$v.email.$touch()"
                            @blur="$v.email.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-select
                            name="other_emails"
                            v-model="other_emails"
                            label="Altres correus"
                            multiple
                            tags
                            clearable
                            :items="[]"
                    ></v-select>
                </v-flex>
                <v-flex md1>
                    <v-text-field
                            name="mobile"
                            label="Mòbil"
                            v-model="mobile"
                            :error-messages="mobileErrors"
                            @input="$v.mobile.$touch()"
                            @blur="$v.mobile.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-select
                            name="other_mobiles"
                            v-model="other_mobiles"
                            label="Altres Mòbils"
                            multiple
                            tags
                            clearable
                            :items="[]"
                    ></v-select>
                </v-flex>
                <v-flex md2>
                    <v-text-field
                            name="phone"
                            label="Telèfon"
                            v-model="phone"
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-select
                            name="other_phones"
                            v-model="other_phones"
                            label="Altres Telèfons"
                            multiple
                            tags
                            clearable
                            :items="[]"
                    ></v-select>
                </v-flex>
            </v-layout>
        </v-container>

        <h1 class="subheading primary--text">
            <div>
                <p>Formació</p>
            </div>
        </h1>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md6>
                    <v-text-field
                            name="degree"
                            label="Titulació d'accés"
                            v-model="degree"
                            :error-messages="degreeErrors"
                            :counter="255"
                            @input="$v.degree.$touch()"
                            @blur="$v.degree.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            name="other_degrees"
                            label="Altres titulacions"
                            v-model="other_degrees"
                            :counter="255"
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            name="languages"
                            label="Idiomes segons marc Europeu"
                            v-model="languages"
                            :counter="255"
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            name="profiles"
                            label="Perfils professionals"
                            v-model="profiles"
                            :counter="255"
                    ></v-text-field>
                </v-flex>
                <v-flex md12>
                    <v-text-field
                            name="other_training"
                            label="Altres formacions"
                            v-model="other_training"
                            :counter="255"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>

        <h1 class="subheading primary--text">
            <div>
                <p>Cos i especialitat</p>
            </div>
        </h1>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md3>
                    <specialty-select
                            name="specialty"
                            label="Especialitat"
                            :error-messages="specialtyErrors"
                            @input="$v.specialty.$touch()"
                            @blur="$v.specialty.$touch()"
                            :specialties="specialties"
                            v-model="specialty"
                    ></specialty-select>
                </v-flex>
                <v-flex md5>
                    <v-select
                            name="force"
                            label="Cos"
                            autocomplete
                            tabindex="-1"
                            required
                            clearable
                            :error-messages="forceErrors"
                            @input="$v.force.$touch()"
                            @blur="$v.force.$touch()"
                            :items="forces"
                            v-model="force"
                            item-text="name"
                    >
                    </v-select>
                </v-flex>
                <v-flex md4>
                    <v-text-field
                            name="teacher_start_date"
                            label="Any inici serveis ensenyament"
                            v-model="teacher_start_date"
                    ></v-text-field>
                </v-flex>
                <v-flex md4>

                    <v-menu
                            lazy
                            name="startDateMenu"
                            v-model="startDateMenu"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            min-width="290px"
                    >
                        <v-text-field
                                slot="activator"
                                name="formatted_start_date"
                                label="Data incorporació centre"
                                hint="format DD/MM/AAAA"
                                persistent-hint
                                prepend-icon="event"
                                :value="formatted_start_date" @change.native="formatted_start_date = $event.target.value"
                                :error-messages="startDateErrors"
                                @input="$v.start_date.$touch()"
                                @blur="$v.start_date.$touch()"
                                required
                        ></v-text-field>
                        <v-date-picker
                                name="start_date"
                                locale="ca"
                                v-model="start_date"
                        ></v-date-picker>
                    </v-menu>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            name="opositions_date"
                            label="Data superació oposicions"
                            v-model="opositions_date"
                    ></v-text-field>
                </v-flex>
                <v-flex md5>
                    <v-select
                            name="administrative_status"
                            label="Situació administrativa"
                            autocomplete
                            required
                            clearable
                            :items="administrativeStatuses"
                            v-model="administrative_status"
                            :error-messages="administrativeStatusErrors"
                            @input="$v.administrative_status.$touch()"
                            @blur="$v.administrative_status.$touch()"
                            item-text="name"
                    >
                    </v-select>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            name="destination_place"
                            label="Lloc destinació definitiva (només comissió serveis)"
                            v-model="destination_place"
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <teacher-select
                            name="teacher"
                            label="Professor al que substitueix (si s'escau)"
                            :teachers="teachers"
                            v-model="teacher"
                    ></teacher-select>
                </v-flex>
                <!-- TODO: Terms of Service | Compliance with LOPD i GDPR -->
                <!--<v-flex md6>-->
                <!--<v-checkbox-->
                <!--name="checkbox"-->
                <!--label="Accepteu les condicions d'ús?"-->
                <!--v-model="checkbox"-->
                <!--:error-messages="checkboxErrors"-->
                <!--@change="$v.checkbox.$touch()"-->
                <!--@blur="$v.checkbox.$touch()"-->
                <!--required-->
                <!--&gt;</v-checkbox>-->
                <!--</v-flex>-->
            </v-layout>
        </v-container>

        <h1 class="subheading primary--text">
            <div>
                <p>Documentació</p>
            </div>
        </h1>

        <v-container fluid grid-list-md fluid>
            <v-layout row wrap>
                <v-flex md3>
                    <upload-card
                            name="file"
                            v-model="identifier_photocopy"
                            label="Fotocopia del DNI, NIE o Passaport"
                    ></upload-card>
                </v-flex>
                <v-flex md3>
                    <upload-card
                            name="photo"
                            v-model="photo"
                            label="Foto carnet"
                    ></upload-card>
                </v-flex>

            </v-layout>
        </v-container>

        <v-card v-if="confirmMode">
            <v-card-title class="blue darken-3 white--text">Plaça a assignar i dades del nou usuari</v-card-title>
            <v-card-text class="px-0 mb-2">
                <v-container grid-list-md text-xs-center fluid>
                    <v-layout row wrap>
                        <v-flex md6>
                            <h1>Plaça</h1>
                            <jobs-select-for-pendingteacher
                                    :teacher="teacher"
                                    :jobs="jobs"
                                    :job="job"
                                    @input="job = $event"
                            ></jobs-select-for-pendingteacher>
                        </v-flex>
                        <v-flex md6>
                            <h1>Usuari</h1>
                            <proposed-user v-if="ready"
                                           :name="name"
                                           :sn1="sn1"
                                           :value="username"
                                           @input="username = $event"></proposed-user>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>

        <template v-if="confirmMode">
            <v-btn @click.native="cancel">
                <v-icon>close</v-icon> Sortir
            </v-btn>
            <v-btn black color="green" @click="createTeacher" class="white--text" :disabled="creating" :loading="creating">
                <v-icon>add</v-icon> Crear nou professor
            </v-btn>
        </template>
        <v-btn v-else @click="submit" id="sendButton">Enviar</v-btn>
    </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import axios from 'axios'
  import PendingTeacher from './Mixins/PendingTeacher'
  import TeacherSelect from './TeacherSelectComponent.vue'
  import UploadCardComponent from '../ui/UploadCardComponent.vue'
  import ProposedUser from '../users/ProposedUserComponent.vue'
  import JobsSelectForPendingTeacher from '../jobs/JobsSelectForPendingTeacher.vue'
  import SpecialtySelect from '../specialties/SpecialtySelectComponent'

  export default {
    name: 'PendingTeacherForm',
    components: {
      'upload-card': UploadCardComponent,
      'teacher-select': TeacherSelect,
      'proposed-user': ProposedUser,
      'jobs-select-for-pendingteacher': JobsSelectForPendingTeacher,
      'specialty-select': SpecialtySelect
    },
    mixins: [validationMixin, withSnackbar, PendingTeacher],
    data () {
      return {
        ready: false,
        creating: false
      }
    },
    props: {
      pendingTeacher: {
        type: Object,
        default: null
      },
      confirmMode: {
        type: Boolean,
        default: false
      },
      jobs: {
        type: Array,
        default: () => { return [] }
      }
    },
    watch: {
      birthdateMenu (val) {
        val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      searchPostalCodes (val) {
        val && (val.length > 1) && this.queryPostalCodes(val)
      },
      searchLocalities (val) {
        val && (val.length > 1) && this.queryLocalities(val)
      },
      searchProvinces (val) {
        val && (val.length > 1) && this.queryProvinces(val)
      },
      postal_code: function (newPostalCode) {
        this.setLocality(newPostalCode)
        this.setProvince(newPostalCode)
      },
      specialty: function (newSpecialty) {
        this.setForce(newSpecialty)
      }
    },
    methods: {
      cancel () {
        this.$emit('cancel')
      },
      setForce (specialty) {
        if (specialty) {
          let foundForce = this.forces.find(force => {
            return force.id === specialty.force_id
          })
          if (foundForce) this.force = foundForce
        }
      },
      setLocality (postalCode) {
        if (postalCode) {
          let foundLocality = this.allLocalities.find(locality => {
            return locality.postalcode === postalCode
          })
          if (foundLocality) {
            this.localities.push(foundLocality)
            this.locality = foundLocality
          }
        }
      },
      setProvince (postalCode) {
        if (postalCode) {
          let foundProvince = this.allProvinces.find(province => {
            return postalCode.startsWith(province.postal_code_prefix)
          })
          if (foundProvince) {
            this.provinces.push(foundProvince)
            this.province = foundProvince
          }
        }
      },
      getSpecialty (specialtyId) {
        return this.specialties.find(specialty => specialty.id === specialtyId)
      },
      getForce (forceId) {
        return this.forces.find(force => force.id === forceId)
      },
      getAdministrativestatus (statusId) {
        return this.administrativeStatuses.find(status => status.id === statusId)
      },
      getTeacher (teacherId) {
        return this.teachers.find(teacher => teacher.teacher_id === teacherId)
      },
      queryPostalCodes (v) {
        this.postalCodes = this.allPostalCodes.filter(postalCode => {
          return (postalCode || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      queryLocalities (v) {
        this.localities = this.allLocalities.filter(locality => {
          return (locality.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      queryProvinces (v) {
        this.provinces = this.allProvinces.filter(province => {
          return (province.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
        })
      },
      validateDNI (dni) {
        let numero, lt, letra
        let regexpdni = /^[XYZ]?\d{5,8}[A-Z]$/

        dni = dni.toUpperCase()

        if (regexpdni.test(dni) === true) {
          numero = dni.substr(0, dni.length - 1)
          numero = numero.replace('X', 0)
          numero = numero.replace('Y', 1)
          numero = numero.replace('Z', 2)
          lt = dni.substr(dni.length - 1, 1)
          numero = numero % 23
          letra = 'TRWAGMYFPDXBNJZSQVHLCKET'
          letra = letra.substring(numero, numero + 1)
          if (letra !== lt) return false
          else return true
        } else return false
      },
      createTeacher (teacher) {
        if (!this.$v.$invalid) {
          if (this.identifierType === 'NIF' && !this.validateDNI(this.identifier)) {
            this.showError('El DNI no és vàlid')
            return
          }
          let postData = { ...this.getPostTeacher(), username: this.username, job_id: this.job.id, pending_teacher_id: this.pendingTeacher.id }
          this.creating = true
          axios.post('/api/v1/approved_teacher', postData).then(response => {
            this.creating = false
          }).catch(error => {
            this.creating = false
            console.log(error)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
          console.log(this.$v.form.$errors)
        }
      },
      submit () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          if (this.identifierType === 'NIF' && !this.validateDNI(this.identifier)) {
            this.showError('El DNI no és vàlid')
            return
          }
          axios.post('api/v1/add_teacher', this.getPostTeacher()).then(response => {
            this.showMessage('Dades enviades correctament')
            this.clear()
            this.$v.$reset()
          }).catch(error => {
            console.log(error)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      },
      getPostTeacher () {
        let teacher = {
          name: this.name,
          sn1: this.sn1,
          sn2: this.sn2,
          identifier_type: this.identifierType,
          identifier: this.identifier,
          birthdate: this.birthdate,
          street: this.street,
          number: this.number,
          floor: this.floor,
          floor_number: this.floor_number,
          postal_code: this.postal_code,
          locality_id: this.locality.id,
          locality: this.locality.name,
          province_id: this.province.id,
          province: this.province.name,
          email: this.email,
          other_emails: this.other_emails.join(),
          mobile: this.mobile,
          other_mobiles: this.other_mobiles.join(),
          phone: this.phone,
          other_phones: this.other_phones.join(),
          degree: this.degree,
          other_degrees: this.other_degrees,
          languages: this.languages,
          profiles: this.profiles,
          other_training: this.other_training,
          force_id: this.force.id,
          force: this.force.name,
          specialty_id: this.specialty,
          specialty: this.getSpecialty(this.specialty).name,
          teacher_start_date: this.teacher_start_date,
          start_date: this.start_date,
          opositions_date: this.opositions_date,
          administrative_status_id: this.administrative_status.id,
          administrative_status: this.administrative_status.name,
          destination_place: this.destination_place,
          teacher_id: this.teacher.teacher_id,
          teacher: this.teacher.name,
          teacher_hashid: this.teacher.hashid
        }
        if (this.photo) teacher.photo = this.photo
        if (this.identifier_photocopy) teacher.identifier_photocopy = this.identifier_photocopy
        return teacher
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
        this.mobile = ''
        this.other_mobiles = []
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
      },
      fetchAllProvinces () {
        return new Promise((resolve, reject) => {
          this.loadingProvince = true
          axios.get('/api/v1/provinces').then(response => {
            this.allProvinces = response.data
            this.loadingProvince = false
            resolve(response)
          }).catch(error => {
            this.loadingProvince = false
            console.log(error)
            this.showError(error)
            reject(error)
          })
        })
      },
      fetchAllLocalities () {
        return new Promise((resolve, reject) => {
          this.loadingLocality = true
          axios.get('/api/v1/localities').then(response => {
            this.allLocalities = response.data
            this.allPostalCodes = [...new Set(this.allLocalities.map(locality => locality['postalcode']))] // Remove duplicates
            this.loadingLocality = false
            this.fillTipicalLocaties()
            resolve(response)
          }).catch(error => {
            this.loadingLocality = false
            console.log(error)
            this.showError(error)
            reject(error)
          })
        })
      },
      fillTipicalLocaties () {
        this.localities = this.allLocalities.filter(locality => {
          return locality.postalcode.startsWith('43')
        })
      },
      mapTeacher (pendingTeacher) {
        this.name = pendingTeacher.name
        this.sn1 = pendingTeacher.sn1
        this.sn2 = pendingTeacher.sn2
        this.identifier = pendingTeacher.identifier
        this.birthdate = pendingTeacher.birthdate
        this.street = pendingTeacher.street
        this.number = pendingTeacher.number
        this.floor = pendingTeacher.floor
        this.floor_number = pendingTeacher.floor_number
        this.postal_code = pendingTeacher.postal_code
        this.locality = pendingTeacher.locality
        this.province = pendingTeacher.province
        this.email = pendingTeacher.email
        this.other_emails = pendingTeacher.other_emails && pendingTeacher.other_emails.split(',')
        this.phone = pendingTeacher.phone
        this.other_phones = pendingTeacher.other_phones && pendingTeacher.other_phones.split(',')
        this.mobile = pendingTeacher.mobile
        this.other_mobiles = pendingTeacher.other_mobiles && pendingTeacher.other_mobiles.split(',')
        this.degree = pendingTeacher.degree
        this.other_degrees = pendingTeacher.other_degrees
        this.languages = pendingTeacher.languages
        this.profiles = pendingTeacher.profiles
        this.other_training = pendingTeacher.other_training
        this.force = this.getForce(pendingTeacher.force_id)
        this.specialty = this.getSpecialty(pendingTeacher.specialty_id)
        this.teacher_start_date = pendingTeacher.teacher_start_date
        this.start_date = pendingTeacher.start_date
        this.opositions_date = pendingTeacher.opositions_date
        this.administrative_status = this.getAdministrativestatus(pendingTeacher.administrative_status_id)
        this.destination_place = pendingTeacher.destination_place
        this.teacher = this.getTeacher(pendingTeacher.teacher_id)
        this.ready = true
      }
    },
    created () {
      this.fetchAllProvinces().then(response => {
        this.fetchAllLocalities().then(response => {
          if (this.pendingTeacher) this.mapTeacher(this.pendingTeacher)
        })
      })
    }
  }
</script>
