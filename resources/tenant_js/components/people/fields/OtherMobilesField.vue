<template>
    <v-dialog
                v-model="dialog"
                width="500"
        >
            <v-tooltip bottom slot="activator">
                <v-btn icon small color="secondary" slot="activator">
                    <v-icon small>smartphone</v-icon>
                </v-btn>
                <span>Afegir altres telèfons mòbils</span>
            </v-tooltip>
            <v-card>
                <v-toolbar dense color="primary">
                    <v-toolbar-title class="white--text">Altres telèfons mòbils</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="dialog=false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <v-text-field
                            label="Altres telèfons mòbils"
                            v-model="dataOtherMobiles"
                            placeholder="666777888, 666555444"
                            hint="Altres telèfons mòbils, separats per comes"
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
                    <v-spacer></v-spacer>
                    <v-btn
                            color="primary"
                            @click="dialog = false"
                    >
                    Guardar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
</template>

<script>
export default {
  name: 'OtherMobilesField',
  data () {
    return {
      dialog: false,
      dataOtherMobiles: null
    }
  },
  model: {
    prop: 'otherMobiles',
    event: 'input'
  },
  props: {
    otherMobiles: ''
  },
  methods: {
    input () {
      this.$emit('input', this.dataOtherMobiles)
    },
    blur () {
      this.$emit('blur', this.dataOtherMobiles)
    }
  }
}
</script>
