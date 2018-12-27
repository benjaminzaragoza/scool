<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <h1 class="title grey--text text--darken-3">Cicles per famílies professionals</h1>
                    <p class="mt-3">
                        L'Institut de l'Ebre ofereix els següents cicles formatius de grau superior:
                    </p>

                    <div class="d-flex justify-between align-center mb-3">
                        <v-btn @click="expandAll">all</v-btn>
                        <v-btn @click="none">none</v-btn>
                    </div>

                    <v-expansion-panel
                            v-model="panel"
                            expand
                    >
                        <v-expansion-panel-content
                                v-for="(family,i) in families"
                                :key="i"
                        >
                            <div slot="header" v-text="family.name"></div>
                            <v-card v-for="study in family.studies" :key="study.id">
                                <v-card-text v-text="study.name"></v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
  name: 'CurriculumPublicComponent',
  data () {
    return {
      panel: []
    }
  },
  props: {
    families: {
      type: Array,
      required: true
    }
  },
  methods: {
    // Create an array the length of our items
    // with all values as true
    expandAll () {
      this.panel = [...this.families.keys()].map(_ => true)
    },
    // Reset the panel
    none () {
      this.panel = []
    }
  },
  created () {
    this.expandAll()
  }
}
</script>
