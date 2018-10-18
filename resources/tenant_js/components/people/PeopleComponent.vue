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
                    <v-toolbar-title class="white--text title">Persones</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="refresh">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                TODO SELECT HERE
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
                                    class="px-0 m-0 hidden-sm-and-down"
                                    :headers="headers"
                                    :items="filteredPeople"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Persones per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: person }">
                                    <tr>
                                        <td class="text-xs-left" v-html="person.id"></td>
                                        <td class="text-xs-left" v-html="person.identifier_id"></td>
                                        <td class="text-xs">
                                            <a target="_blank" :href="'/users?action=show&id=' + person.userId">{{ person.userEmail }}</a>
                                        </td>
                                        <td class="text-xs-left" v-html="person.givenName"></td>
                                        <td class="text-xs-left" v-html="person.sn1"></td>
                                        <td class="text-xs-left" v-html="person.sn2"></td>
                                        <td class="text-xs-left" v-html="person.email"></td>
                                        <td class="text-xs-left" v-html="person.mobile"></td>
                                        <td class="text-xs-left" v-html="person.other_emails"></td>
                                        <td class="text-xs-left" v-html="person.other_phones"></td>
                                        <td class="text-xs-left" v-html="person.birthdate"></td>
                                        <td class="text-xs-left" v-html="person.gender"></td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ person.formatted_created_at_diff }}</span>
                                                <span>{{ person.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ person.formatted_updated_at_diff }}</span>
                                                <span>{{ person.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <!--<show-person-icon :person="person" :persons="persons"></show-person-icon>-->
                                            <v-btn icon class="mx-0" @click="editItem(person)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon
                                                    :id="'person_remove_' + person.id"
                                                    icon="delete"
                                                    color="pink"
                                                    :working="removing"
                                                    @confirmed="remove(person)"
                                                    tooltip="Eliminar"
                                                    message="Esteu segurs?"
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
  import withSnackbar from '../mixins/withSnackbar'
  import UserAvatar from '../ui/UserAvatarComponent'
  import ConfirmIcon from '../ui/ConfirmIconComponent'

  export default {
    name: 'PeopleComponent',
    mixins: [withSnackbar],
    components: {
      'user-avatar': UserAvatar,
      'confirm-icon': ConfirmIcon
    },
    data () {
      return {
        search: '',
        internalPeople: this.people,
        removing: false
      }
    },
    computed: {
      // ...mapGetters({
      //   internalTeachers: 'teachers'
      // }),
      filteredPeople: function () {
        return this.internalPeople
        // if (this.showStatusHeader) return this.internalTeachers
        // return this.internalTeachers.filter(teacher => teacher.administrative_status_id === this.administrativeStatus.id)
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        headers.push({text: 'Identifier', value: 'identifier_id'})
        headers.push({text: 'Usuari', value: 'userEmail'})
        // headers.push({text: 'Foto', value: 'full_search', sortable: false})
        headers.push({text: 'Nom', value: 'givenName'})
        headers.push({text: '1r Cognom', value: 'sn1'})
        headers.push({text: '2n Cognom', value: 'sn2'})
        headers.push({text: 'email', value: 'email'})
        headers.push({text: 'mobile', value: 'mobile'})
        headers.push({text: 'Altres emails', value: 'other_emails'})
        headers.push({text: 'Altres telèfons i mòbils', value: 'other_phones'})
        // if (this.showStatusHeader) headers.push({text: 'Estatus', value: 'administrative_status_code'})
        headers.push({text: 'Data i llocs de naixament', value: 'birthdate'})
        headers.push({text: 'Sexe', value: 'gender'})
        headers.push({text: 'Data creació', value: 'formatted_created_at'})
        headers.push({text: 'Data actualització', value: 'formatted_updated_at'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      }
    },
    props: {
      people: {
        type: Array,
        required: true
      }
    },
    methods: {
      refresh () {
        console.log('TODO refresh')
      }
    }
  }
</script>
