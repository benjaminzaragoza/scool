<template>
    <v-autocomplete
            name="user"
            :label="label"
            :items="users"
            v-model="user"
            item-text="name"
            item-value="id"
            chips
            clearable
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    @input="data.parent.selectItem(data.item)"
                    :selected="data.selected"
                    class="chip--select-multi"
                    :key="JSON.stringify(data.item)"
            >
                <user-avatar :hash-id="data.item.hashid"
                             :alt="data.item.name"
                ></user-avatar>
                {{ data.item.name }}
            </v-chip>
        </template>
        <template slot="item" slot-scope="{ item: user }">
            <template v-if="typeof user !== 'object'">
                <v-list-tile-content v-text="user"></v-list-tile-content>
            </template>
            <template v-else>
                <v-list-tile-avatar>
                    <img :src="'/user/' + user.hashid + '/photo'">
                </v-list-tile-avatar>
                <v-list-tile-content>
                    <v-list-tile-title v-html="user.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="user.email"></v-list-tile-sub-title>
                </v-list-tile-content>
            </template>
        </template>
    </v-autocomplete>
</template>

<style>

</style>

<script>
  import UserAvatar from '../ui/UserAvatarComponent'
  export default {
    components: {
      'user-avatar': UserAvatar
    },
    data () {
      return {
        user: {}
      }
    },
    props: {
      users: {
        type: Array,
        required: true
      },
      label: {
        type: String,
        default: "Escolliu l'usuari a suplantar"
      }
    },
    watch: {
      user (user) {
        if (user) {
          window.location.href = '/impersonate/take/' + user
        }
      }
    }
  }
</script>
