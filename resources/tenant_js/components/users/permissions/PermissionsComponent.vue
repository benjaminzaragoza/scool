<template>
    <span>
        <!--<permission-add :permissions="{{ $permissions }}"></permission-add>-->

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <permissions-list :permissions="permissions"></permissions-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import PermissionsList from './PermissionsList'
export default {
  name: 'Permissions',
  components: {
    'permissions-list': PermissionsList
  },
  props: {
    permissions: {
      type: Array,
      required: true
    }
  }
}
</script>
