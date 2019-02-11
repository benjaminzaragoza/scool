<template>
    <v-dialog
            v-model="dialog"
            width="500"
    >
        <v-tooltip bottom slot="activator">
            <v-btn icon small color="primary" slot="activator">
                <v-icon small>email</v-icon>
            </v-btn>
            <span>Afegir altres correus electrònics</span>
        </v-tooltip>
        <v-card>
            <v-toolbar dense color="primary">
                <v-toolbar-title class="white--text">Altres correus electrònics</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text" @click="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-text-field
                        label="Altres correus electrònics"
                        v-model="dataOtherEmails"
                        placeholder="usuari@gmail.com, usuari@xtec.cat"
                        hint="Altres correus electrònics, separats per comes"
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
  name: 'OtherEmailsField',
  data () {
    return {
      dialog: false,
      dataOtherEmails: null
    }
  },
  model: {
    prop: 'otherEmails',
    event: 'input'
  },
  props: {
    otherEmails: ''
  },
  methods: {
    input () {
      this.$emit('input', this.dataOtherEmails)
    },
    blur () {
      this.$emit('blur', this.dataOtherEmails)
    }
  }
}
</script>
