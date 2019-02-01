<template>
    <v-tooltip bottom>
        <v-btn slot="activator" icon class="mx-0" :href="href()" target="_blank">
            <v-icon color="primary">person</v-icon>
        </v-btn>
        <span>Consultar dades personals</span>
    </v-tooltip>
</template>

<script>
export default {
  name: 'UserPersonalDataIconlink',
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    href () {
      return '/personal_data/' + this.user.person_id
    }
  }
}
</script>
