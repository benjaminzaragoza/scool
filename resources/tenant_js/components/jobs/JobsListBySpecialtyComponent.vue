<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex>
                <v-card>
                    <v-toolbar color="teal" dark>
                        <v-toolbar-side-icon></v-toolbar-side-icon>
                        <v-toolbar-title>Places per especialitat</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-list>
                        <v-list-group
                                v-for="specialty in specialties"
                                :key="specialty.code"
                                no-action
                        >
                            <v-list-tile slot="activator">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ specialty.name }}</span>
                                            <span>{{ specialty.code }}</span>
                                        </v-tooltip>
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile v-for="staff in specialty.staff" :key="staff.id">
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ staff.family && staff.family.code }} | {{ staff.family && staff.family.name }}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list-group>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<style>

</style>

<script>
export default {
  data () {
    return {
      data: 'example'
    }
  },
  props: {
    specialties: {
      type: Array,
      required: true
    }
  }
}
</script>
