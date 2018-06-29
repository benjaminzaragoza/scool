<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>

                        <v-list>
                            <v-list-tile>
                                <v-list-tile-title>TODO</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Google groups</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                TODO Filter here?
                                <v-spacer></v-spacer>
                                <v-text-field
                                        append-icon="search"
                                        label="Buscar"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                                    class="px-0 mb-2 hidden-sm-and-down"
                                    :headers="headers"
                                    :items="filteredGroups"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Grups per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: group }">
                                    <tr>
                                        <td class="text-xs-left">
                                            <span :title="'etag: ' + group.etag" v-html="group.id"></span>
                                        </td>
                                        <td class="text-xs-left" v-html="group.name"></td>
                                        <td class="text-xs-left" v-html="group.email"></td>
                                        <td class="text-xs-left" v-html="group.directMembersCount"></td>
                                        <td class="text-xs-left" v-html="group.adminCreated"></td>
                                        <td class="text-xs-left">
                                            <template v-if="group.aliases">
                                                {{ group.aliases.join() }}
                                            </template>
                                        </td>
                                        <td class="text-xs-left" v-html="group.description"></td>
                                        <td class="text-xs-left">
                                            <v-btn icon class="mx-0" @click="">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon
                                                    :id="'google_group_remove_' + group.email.replace('@','_')"
                                                    icon="delete"
                                                    color="pink"
                                                    :working="removing"
                                                    @confirmed="remove(group)"
                                                    tooltip="Eliminar"
                                                    message="Esteu segurs que voleu eliminar el grup?"
                                            ></confirm-icon>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import withSnackbar from '../../mixins/withSnackbar'
  import axios from 'axios'
  import ConfirmIcon from '../../ui/ConfirmIconComponent'

  export default {
    name: 'GoogleGroupsComponent',
    mixins: [withSnackbar],
    components: {
      'confirm-icon': ConfirmIcon
    },
    data () {
      return {
        search: '',
        internalGroups: this.groups,
        removing: false,
        refreshing: false
      }
    },
    computed: {
      filteredGroups: function () {
        return this.internalGroups
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        headers.push({text: 'name', value: 'name'})
        headers.push({text: 'email', value: 'email'})
        headers.push({text: 'Membres del grup', value: 'directMembersCount'})
        headers.push({text: 'Created by admin', value: 'adminCreated'})
        headers.push({text: 'Alias', value: 'aliases'})
        headers.push({text: 'Descripció', value: 'description'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      }
    },
    props: {
      groups: {
        type: Array,
        required: true
      }
    },
    methods: {
      refresh () {
        this.refreshing = true
        axios.get('/api/v1/gsuite/groups').then(response => {
          this.refreshing = false
          this.internalGroups = response.data
        }).catch(error => {
          this.refreshing = false
          console.log(error)
        })
      },
      remove (group) {
        console.log(group)
        this.removing = true
        axios.delete('/api/v1/gsuite/groups/' + group).then(response => {
          this.removing = false
          this.refresh()
        }).catch(error => {
          this.removing = false
          console.log(error)
        })
      }
    }
  }
</script>
