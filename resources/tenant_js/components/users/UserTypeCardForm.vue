<template>
    <v-card>
        <v-card-text>
            <user-types-select
                    :user-types="userTypes"
                    v-model="dataUserType"
            ></user-types-select>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    class="grey--text"
                    flat
                    @click="$emit('close')"
            >
                Cancel·lar
            </v-btn>
            <v-btn
                    color="primary"
                    @click="$emit('close')"
            >
                Canviar
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import UserTypesSelect from './UserTypesSelect'

export default {
  name: 'UserTypeCardForm',
  components: {
    'user-types-select': UserTypesSelect
  },
  data () {
    return {
      dataUserType: {}
    }
  },
  props: {
    userType: {
      type: Number
    },
    userTypes: {
      type: Array,
      required: true
    }
  },
  created () {
    console.log('CREATED')
    if (this.userType) {
      console.log('prova')
      const found = this.userTypes.find(type => type.id === this.userType)
      console.log('found:')
      console.log(found)
      if (found) this.dataUserType = found
    }
  }
}
</script>
