<template>
    <span>
        <span v-if="user.user_type_id">
            <a :href="userTypeLink()" target="_blank" class="mr-0">{{ formatUserType() }}</a>
            <v-tooltip bottom class="ml-0">
                <v-btn flat slot="activator" icon small color="success" @click="dialog=true">
                    <v-icon small>edit</v-icon>
                </v-btn>
                <span>Modificar el tipus d'usuari</span>
            </v-tooltip>
        </span>
        <span v-else>
            <v-tooltip bottom>
                <v-btn flat slot="activator" icon small color="primary" @click="dialog=true">
                    <v-icon small>add</v-icon>
                </v-btn>
                <span>Establir el tipus d'usuari</span>
            </v-tooltip>
        </span>
        <v-dialog
                v-if="dialog"
                v-model="dialog"
                width="500"
                @keydown.esc="dialog=false"
        >
            <v-toolbar color="primary" dense>
                <v-toolbar-title class="white--text title">Tipus d'usuari</v-toolbar-title>
            </v-toolbar>
            <user-type-card-form :user="user" @close="dialog=false" @changed="$emit('changed')" :user-types="userTypes" :user-type="user.user_type_id"></user-type-card-form>
        </v-dialog>
    </span>
</template>

<script>
import UserTypeCardForm from './UserTypeCardForm'
export default {
  name: 'UsersUserTypeManagement',
  components: {
    'user-type-card-form': UserTypeCardForm
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    userTypes: {
      type: Array,
      required: true
    }
  },
  methods: {
    userTypeIds () {
      const field = this.userIdFields[this.user.user_type_id]
      return this.user[field]
    },
    userTypeLinks () {
      const prefix = this.userTypeLinksPrefix[this.user.user_type_id]
      if (prefix) return prefix + this.userTypeIds()
      return '#'
    },
    userTypeLink () {
      if (this.user.user_type_id) {
        return this.userTypeLinks()
      }
      return '#'
    },
    formatUserType () {
      if (this.user.user_type_id) return this.userTypesTranslation[this.user.user_type_id]
    }
  },
  created () {
    this.userTypesTranslation = {
      1: 'Professor',
      2: 'Alumne/a',
      3: 'Conserge',
      4: 'Becari',
      5: 'Administratiu/va',
      6: 'Familiars'
    }
    this.userTypeLinksPrefix = {
      1: '/teachers/',
      2: '/students/'
      // 3: '/janitors/',
      // 4: '/administrative_assistants/',
      // 5: '/familiars/'
    }
    this.userIdFields = {
      1: 'teacher_id',
      2: 'student_id'
      // 3: 'janitor_id',
      // 4: 'administrative_assistant_id',
      // 5: 'familiar_id'
    }
  }
}
</script>
