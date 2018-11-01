<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex>
                <v-card>
                    <v-toolbar color="teal" dark>
                        <v-toolbar-side-icon></v-toolbar-side-icon>
                        <v-toolbar-title>Places per famílies</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-list>
                        <v-list-group
                                v-for="family in families"
                                :key="family.code"
                                no-action
                        >
                            <v-list-tile slot="activator">
                                <v-list-tile-content>
                                    <v-list-tile-title>
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ family.name }}</span>
                                            <span>{{ family.code }}</span>
                                        </v-tooltip>
                                    </v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile v-for="job in family.jobs" :key="job.id">
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ job.specialty && job.specialty.code }} | {{ job.specialty && job.specialty.name }}</v-list-tile-title>
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
    families: {
      type: Array,
      required: true
    }
  }
}
</script>
