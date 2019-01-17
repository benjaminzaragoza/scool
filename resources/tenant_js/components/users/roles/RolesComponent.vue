<template>
    <span>
        <!--<role-add :roles="roles"></role-add>-->
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <roles-list :roles="roles"></roles-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import RolesList from './RolesList'
export default {
  name: 'Roles',
  components: {
    'roles-list': RolesList
  },
  props: {
    roles: {
      type: Array,
      required: true
    }
  }
}
</script>
