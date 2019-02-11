<template>
    <v-dialog
            v-model="dialog"
            width="500"
    >
        <v-tooltip bottom slot="activator">
            <v-btn icon small color="error" slot="activator">
                <v-icon small>phone</v-icon>
            </v-btn>
            <span>Afegir altres telèfons fixes</span>
        </v-tooltip>
        <v-card>
            <v-toolbar dense color="primary">
                <v-toolbar-title class="white--text">Altres telèfons fixes</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text" @click="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-text-field
                        label="Altres telèfons mòbils"
                        v-model="dataOtherPhones"
                        placeholder="977405253, 977524548"
                        hint="Altres telèfons fixes, separats per comes"
                        @input="input"
                        @blur="blur"
                ></v-text-field>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-btn
                        flat
                        color="primary"
                        @click="dialog = false"
                >
                Tancar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
export default {
  name: 'OtherPhonesField',
  data () {
    return {
      dialog: false,
      dataOtherPhones: null
    }
  },
  model: {
    prop: 'otherPhones',
    event: 'input'
  },
  props: {
    otherPhones: ''
  },
  methods: {
    input () {
      this.$emit('input', this.dataOtherPhones)
    },
    blur () {
      this.$emit('blur', this.dataOtherPhones)
    }
  }
}
</script>
