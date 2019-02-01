<template>
    <a :href="userTypeLink()" target="_blank">{{ formatUserType(props.item.user_type_id) }}</a>
</template>

<script>
export default {
  name: 'UsersUserTypeManagement',
  props: {
    user: {
      type: Object,
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
    formatUserType (userType) {
      if (userType) return this.userTypesTranslation[userType]
    }
  },
  created () {
    this.userTypesTranslation = {
      1: 'Professor',
      2: 'Estudiant',
      3: 'Conserge',
      4: 'Administratiu',
      5: 'Familiar'
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
