<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12 md10>
                            <h1 class="title grey--text text--darken-3">Cicles per famílies professionals</h1>
                            <p class="mt-3">
                                L'Institut de l'Ebre ofereix els següents cicles formatius de grau superior:
                            </p>
                        </v-flex>
                        <v-flex xs12 md2>
                            <div class="d-flex justify-between align-center mb-3">
                                <v-btn @click="expandAll">
                                    <v-icon>add</v-icon>
                                    Expandir
                                </v-btn>
                                <v-btn @click="none">
                                    <v-icon>remove</v-icon>
                                    Tancar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                    <v-expansion-panel
                            v-model="panel"
                            expand
                    >
                        <v-expansion-panel-content
                                v-for="(family,i) in families"
                                :key="i"
                        >
                            <div slot="header">
                                <span class="subheading grey--text text--darken-2">{{family.name}}</span>
                                <template v-if="$hasRole('CurriculumManager')">
                                    <v-btn icon flat small href="/curriculum" target="_blank" v-if="!family.studies" title="Aquesta família no té cap estudi assignat" color="red">
                                        <v-icon>notification_important</v-icon>
                                    </v-btn>
                                    <v-btn icon flat small href="/curriculum"  target="_blank" v-if="family.studies && family.studies.length === 0" title="Aquesta família no té cap estudi assignat" color="red">
                                        <v-icon>notification_important</v-icon>
                                    </v-btn>
                                </template>
                            </div>
                            <v-card v-for="study in family.studies" :key="study.id" class="mt-0">
                                <v-card-text class="ml-4 pt-0">
                                    <a :href="'/public/curriculum/estudis/' + study.slug" class="body-2 cyan--text text--darken-3">{{ study.name }}</a>
                                </v-card-text>
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
