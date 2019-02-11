<template>
    <v-dialog
            v-model="dialog"
            width="500"
    >
        <v-tooltip bottom slot="activator">
            <v-btn icon small color="accent" slot="activator">
                <v-icon small>credit_card</v-icon>
            </v-btn>
            <span>Afegir altres identificadors personals</span>
        </v-tooltip>
        <v-card>
            <v-toolbar dense color="primary">
                <v-toolbar-title class="white--text">Altres identificadors personals</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text" @click="dialog=false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
                <v-text-field
                        label="Altres identificadors personals"
                        v-model="dataOtherIdentifiers"
                        placeholder="TODO"
                        hint="Altres identificadors personals"
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
  name: 'OtherIdentifiersField',
  data () {
    return {
      dialog: false,
      dataOtherIdentifiers: null
    }
  },
  model: {
    prop: 'otherIdentifiers',
    event: 'input'
  },
  props: {
    otherIdentifiers: ''
  },
  methods: {
    input () {
      this.$emit('input', this.dataOtherIdentifiers)
    },
    blur () {
      this.$emit('blur', this.dataOtherIdentifiers)
    }
  }
}
</script>
