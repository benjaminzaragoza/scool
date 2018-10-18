<template>
    <v-select
            :items="users"
            v-model="internalUser"
            label="Seleccioneu un usuari"
            item-text="name"
            chips
            max-height="auto"
            autocomplete
            clearable
            prepend-icon="person"
            @input="input"
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    :selected="data.selected"
                    :key="JSON.stringify(data.item)"
                    class="chip--select-multi"
                    @input="data.parent.selectItem(data.item)"
            >
                <user-avatar :hash-id="data.item.hashid"
                             :alt="data.item.name"
                ></user-avatar>
                {{ data.item.name }}
            </v-chip>
        </template>
        <template slot="item" slot-scope="data">
            <template v-if="typeof data.item !== 'object'">
                <v-list-tile-content v-text="data.item"></v-list-tile-content>
            </template>
            <template v-else>
                <v-list-tile-avatar>
                    <img :src="'/user/' + data.item.hashid + '/photo'"
                         :alt="data.item.name"
                         :title="data.item.name">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-select>
</template>

<script>
  import UserAvatar from '../ui/UserAvatarComponent'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    name: 'AvailableUsersComponent',
    components: {
      'user-avatar': UserAvatar
    },
    mixins: [withSnackbar],
    data () {
      return {
        users: [],
        internalUser: this.user
      }
    },
    model: {
      prop: 'user',
      event: 'input'
    },
    props: {
      jobType: {
        type: Number,
        default: 1
      },
      user: {
        required: true
      }
    },
    watch: {
      user (newUser) {
        this.internalUser = newUser
      }
    },
    methods: {
      input () {
        this.$emit('input', this.internalUser)
      }
    },
    mounted () {
      axios.get('/api/v1/available-users/' + this.jobType).then(response => {
        this.users = response.data
      }).catch(error => {
        console.log(error)
        this.showError(error)
      })
    }
  }
</script>
