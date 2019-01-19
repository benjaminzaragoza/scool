<template>
    <span>
        <v-chip :small="small" close @input="remove(role)" v-for="role in roles" :key="role.id" label outline color="grey grey--text text--darken-2">{{ role.name }} ({{ role.guard_name}})</v-chip>
    </span>
</template>

<script>
export default {
  name: 'UserRolesList',
  data () {
    return {
      close: [],
      roles: this.user.roles
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    small: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    async remove (role) {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest rol?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removeRole(role)
      } else {
        role.close = true
      }
    },
    removeRole (role) {
      console.log('TODO Remove Role')
      this.roles.splice(this.roles.indexOf(role), 1)
    }
  }
}
</script>
