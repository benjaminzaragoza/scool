<template>
    <span>
        <v-tooltip bottom class="ma-0" v-if="user.person_id">
            <v-btn slot="activator" icon class="mx-0 ma-0" :href="href()" target="_blank">
                <v-icon color="primary">person</v-icon>
            </v-btn>
            <span>Consultar dades personals</span>
        </v-tooltip>
        <v-tooltip bottom class="ma-0" v-else>
            <v-btn slot="activator" icon class="mx-0 ma-0" :href="href()" target="_blank">
                <v-icon color="primary">add</v-icon>
            </v-btn>
            <span>Afegir dades personals</span>
        </v-tooltip>
    </span>
</template>

<script>
export default {
  name: 'UserPersonalDataIcon',
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
