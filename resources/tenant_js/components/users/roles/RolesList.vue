<template>
    <span>
        <v-toolbar color="primary" dense>
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>

                <v-list>
                    <v-list-tile href="/users/permissions" target="_blank">
                        <v-list-tile-title>Gestionar Permisos</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/users" target="_blank">
                        <v-list-tile-title>Gestionar Usuaris</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Roles</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text" @click="settings">
                <v-icon>settings</v-icon>
            </v-btn>
            <v-btn icon class="white--text" @click="refresh" :disabled="refreshing" :loading="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-card-text class="px-0 mb-2">
                <v-card>
                    <v-card-title>
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
                        <roles-delete-multiple :users="selected" @deleted="selected=[];refresh(false)"></roles-delete-multiple>
                    </div>
                    <v-data-table
                            v-model="selected"
                            select-all
                            class="px-0 mb-2 hidden-sm-and-down"
                            :headers="headers"
                            :items="internalRoles"
                            :search="search"
                            item-key="id"
                            disable-initial-sort
                            no-results-text="No s'ha trobat cap registre coincident"
                            no-data-text="No hi han dades disponibles"
                            rows-per-page-text="Rols per pàgina"
                            :rows-per-page-items="[5,10,25,50,100,200,500,1000,{'text':'Tots','value':-1}]"
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
                                <td class="text-xs-left cell">
                                    {{ props.item.id }}
                                </td>
                                <td class="text-xs-left cell" style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <v-tooltip bottom>
                                        <span slot="activator">{{ props.item.name }}</span>
                                        <span>{{ props.item.name }}</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-xs-left cell" style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <v-tooltip bottom>
                                        <span slot="activator">{{ props.item.guard_name }}</span>
                                        <span>{{ props.item.guard_name }}</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-xs-left cell" v-html="props.item.formatted_created_at_diff" :title="props.item.formatted_created_at"></td>
                                <td class="text-xs-left cell" :title="props.item.formatted_updated_at">{{props.item.formatted_updated_at_diff}}</td>
                                <td class="text-xs-left cell">
                                    <delete-role :role="props.item"></delete-role>
                                </td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card>

                <v-data-iterator
                        class="hidden-md-and-up"
                        content-tag="v-layout"
                        row
                        wrap
                        :items="internalRoles"
                >
                    <v-flex
                            slot="item"
                            slot-scope="props"
                            xs12
                            sm6
                            md4
                            lg3
                    >
                        <v-card>
                            <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                            <v-divider></v-divider>
                            <v-list dense>
                                <v-list-tile>
                                    <v-list-tile-content>Email:</v-list-tile-content>
                                    <v-list-tile-content class="align-end">{{ props.item.email }}</v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-content>Created at:</v-list-tile-content>
                                    <v-list-tile-content class="align-end">{{ props.item.created_at }}</v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-content>Updated at:</v-list-tile-content>
                                    <v-list-tile-content class="align-end">{{ props.item.updated_at }}</v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-card>
                    </v-flex>
                </v-data-iterator>

            </v-card-text>
        </v-card>
    </span>
</template>

<script>
import { mapGetters } from 'vuex'
import * as mutations from '../../../store/mutation-types'
import * as actions from '../../../store/action-types'
import RolesDeleteMultiple from './RolesDeleteMultiple'
import RoleDelete from './RoleDelete'
export default {
  name: 'RolesList',
  components: {
    'roles-delete-multiple': RolesDeleteMultiple,
    'role-delete': RoleDelete
  },
  data () {
    return {
      selected: [],
      refreshing: false,
      search: '',
      headers: [
        { text: 'Id', align: 'left', value: 'id' },
        { text: 'Name', value: 'name' },
        { text: 'Guard', value: 'guard_name' },
        { text: 'Data creació', value: 'created_at_timestamp' },
        { text: 'Data actualització', value: 'updated_at_timestamp' },
        { text: 'Accions', sortable: false }
      ]
    }
  },
  computed: {
    ...mapGetters({
      internalRoles: 'roles'
    })
  },
  methods: {
    settings () {
      console.log('settings TODO') // TODO
    },
    refresh (message = true) {
      this.refreshing = true
      this.$store.dispatch(actions.FETCH_ROLES).then(response => {
        this.refreshing = false
        if (message) this.$snackbar.showMessage('Roles actualizats correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    this.$store.commit(mutations.SET_ROLES, this.roles)
  }
}
</script>
