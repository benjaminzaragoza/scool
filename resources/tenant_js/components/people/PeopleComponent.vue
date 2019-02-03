<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar dense color="primary">
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile href="/users" target="_blank">
                                <v-list-tile-title>Gestió d'usuaris</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users/permissions" target="_blank">
                                <v-list-tile-title>Gestionar Permisos</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users/roles" target="_blank">
                                <v-list-tile-title>Gestionar Rols</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/google_users" target="_blank">
                                <v-list-tile-title>Usuaris de Google</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/moodle/users" target="_blank">
                                <v-list-tile-title>Usuaris Moodle</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/ldap_users" target="_blank">
                                <v-list-tile-title>Usuaris Ldap</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Persones</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
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

                            <div id="massive_actions" v-if="selected.length > 0" style="text-align: left;">
                                <people-delete-multiple :people="selected" @deleted="selected=[];refresh(false)"></people-delete-multiple>
                            </div>

                            <v-data-table
                                    v-model="selected"
                                    select-all
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
                                <template slot="items" slot-scope="props">
                                    <tr>
                                        <td>
                                            <v-checkbox
                                                    v-model="props.selected"
                                                    primary
                                                    hide-details
                                            ></v-checkbox>
                                        </td>
                                        <td class="text-xs-left" v-html="props.item.id"></td>
                                        <td class="text-xs-left" v-html="props.item.identifier_id"></td>
                                        <td class="text-xs">
                                            <a target="_blank" :href="'/users?action=show&id=' + props.item.userId">{{ props.item.userEmail }}</a>
                                        </td>
                                        <td class="text-xs-left">
                                            <person-edit-name :value="props.item.givenName" :user="props.item"></person-edit-name>
                                        </td>
                                        <td class="text-xs-left">
                                            <person-edit-name :value="props.item.sn1" :user="props.item"></person-edit-name>
                                        </td>
                                        <td class="text-xs-left">
                                            <person-edit-name :value="props.item.sn2" :user="props.item"></person-edit-name>
                                        </td>
                                        <td class="text-xs-left">
                                            <person-emails :email="props.item.email" :user="props.item"></person-emails>
                                        </td>
                                        <td class="text-xs-left" v-html="props.item.mobile"></td>
                                        <td class="text-xs-left">
                                            {{ props.item.birthdate_formatted }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ props.item.birthplace_name }}
                                        </td>
                                        <td class="text-xs-left" v-html="props.item.gender"></td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.formatted_created_at_diff }}</span>
                                                <span>{{ props.item.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.formatted_updated_at_diff }}</span>
                                                <span>{{ props.item.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <json-dialog-component btn-class="ma-0" icon="info" name="Actual" title="Tota la informació en format Json" :json="props.item"></json-dialog-component>
                                            <person-show-icon :person="props.item" :people="people"></person-show-icon>
                                            <person-edit-icon :person="props.item" :people="people"></person-edit-icon>
                                            <person-delete-icon :person="props.item" :people="people"></person-delete-icon>
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
import UserAvatar from '../ui/UserAvatarComponent'
import ConfirmIcon from '../ui/ConfirmIconComponent'
import PeopleDeleteMultiple from './PeopleDeleteMultiple'
import PersonShowIcon from './PersonShowIcon'
import PersonEditIcon from './PersonEditIcon'
import PersonDeleteIcon from './PersonDeleteIcon'
import PersonEmails from './PersonEmails'
import PersonMobiles from './PersonMobiles'
import PersonEditName from './PersonEditName'
import JsonDialogComponent from '../ui/JsonDialogComponent'

export default {
  name: 'People',
  components: {
    'user-avatar': UserAvatar,
    'confirm-icon': ConfirmIcon,
    'people-delete-multiple': PeopleDeleteMultiple,
    'person-show-icon': PersonShowIcon,
    'person-edit-icon': PersonEditIcon,
    'person-delete-icon': PersonDeleteIcon,
    'person-emails': PersonEmails,
    'person-mobiles': PersonMobiles,
    'person-edit-name': PersonEditName,
    'json-dialog-component': JsonDialogComponent

  },
  data () {
    return {
      selected: [],
      search: '',
      internalPeople: this.people,
      removing: false,
      refreshing: false
    }
  },
  computed: {
    filteredPeople: function () {
      return this.internalPeople
      // if (this.showStatusHeader) return this.internalTeachers
      // return this.internalTeachers.filter(teacher => teacher.administrative_status_id === this.administrativeStatus.id)
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id' })
      headers.push({ text: 'Identifier', value: 'identifier_id' })
      headers.push({ text: 'Usuari', value: 'userEmail' })
      // headers.push({text: 'Foto', value: 'full_search', sortable: false})
      headers.push({ text: 'Nom', value: 'givenName' })
      headers.push({ text: '1r Cognom', value: 'sn1' })
      headers.push({ text: '2n Cognom', value: 'sn2' })
      headers.push({ text: 'email', value: 'email' })
      headers.push({ text: 'mobile', value: 'mobile' })
      // if (this.showStatusHeader) headers.push({text: 'Estatus', value: 'administrative_status_code'})
      headers.push({ text: 'Data naixement', value: 'birthdate_formatted' })
      headers.push({ text: 'Lloc de naixement', value: 'birthplace_name' })
      headers.push({ text: 'Sexe', value: 'gender' })
      headers.push({ text: 'Data creació', value: 'formatted_created_at' })
      headers.push({ text: 'Data actualització', value: 'formatted_updated_at' })
      headers.push({ text: 'Accions', sortable: false })
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
      this.refreshing = true
      window.axios.get('/api/v1/people').then(response => {
        this.internalPeople = response.data
        this.refreshing = false
        this.$snackbar.showMessage('Dades personals actualitzades correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar(error)
      })
    }
  }
}
</script>
