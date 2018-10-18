<template>
    <span>
        <v-toolbar color="blue darken-3">
            <v-toolbar-side-icon class="white--text"></v-toolbar-side-icon>
            <v-toolbar-title class="white--text title">Registre de canvis</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>settings</v-icon>
            </v-btn>
            <v-btn icon class="white--text">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-card-text class="px-0 mb-2 hidden-sm-and-down">
                <v-data-table
                        :headers="headers"
                        :items="entries"
                        :pagination.sync="pagination"
                        class="elevation-1"
                >
                    <template slot="items" slot-scope="{ item }">
                      <td class="text-xs-left">{{ item.id }}</td>
                      <td class="text-xs-left"> {{ item.description }}</td>
                      <td class="text-xs-left">{{ item.element }}</td>
                      <td class="text-xs-left">{{ item.type }}</td>
                      <td class="text-xs-left">
                          <user-avatar :hash-id="item.user_hashid"
                                       :alt="item.user_description"
                          ></user-avatar>
                      </td>
                      <td class="text-xs-left">{{item.field_name }} ({{ item.key }})</td>
                      <td class="text-xs-left">{{ item.old_value_description }}</td>
                      <td class="text-xs-left">{{ item.new_value_description }}</td>
                      <td class="text-xs-left">{{ item.formatted_created_at_diff }}</td>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </span>
</template>

<script>
  import UserAvatar from '../ui/UserAvatarComponent'

  export default {
    name: 'AuditLogComponent',
    components: {
      'user-avatar': UserAvatar
    },
    data () {
      return {
        pagination: {
          sortBy: 'id',
          'descending': true
        },
        internalEntries: this.entries,
        headers: [
          {text: 'Id', value: 'id'},
          {text: 'Descripció', value: 'description'},
          {text: 'Element modificat', value: 'element'},
          {text: 'Típus modificació', value: 'type'},
          {text: 'Usuari', value: 'user_description'},
          {text: 'Camp modificat', value: 'key'},
          {text: 'Antic valor', value: 'old_value'},
          {text: 'Nou valor', value: 'new_value'},
          {text: 'Data modificació', value: 'created_at'}
        ]
      }
    },
    props: {
      entries: {
        type: Array,
        required: true
      }
    },
    watch: {
      entries () {
        this.internalEntries = this.entries
      }
    }
  }
</script>
