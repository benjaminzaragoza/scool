<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex md12>
                <role-users @loaded="$emit('loaded')" role="Incident" :role-users="incidentUsers"></role-users>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import RoleUsersComponent from '../permissions/RoleUsersComponent'
export default {
  name: 'IncidentUsersComponent',
  components: {
    'role-users': RoleUsersComponent
  },
  props: {
    incidentUsers: {
      type: Array,
      required: false
    }
  }
}
</script>
