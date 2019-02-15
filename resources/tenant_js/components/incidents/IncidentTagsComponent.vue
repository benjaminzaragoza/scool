<template>
    <span>
        <v-chip small label :color="tag.color" text-color="white" v-for="(tag,key) in incidentTags" :key="tag.id" close v-model="close[key]">
            <v-icon left v-text="tag.icon"></v-icon>{{ tag.value }}
        </v-chip>
        <v-progress-circular
                size="16"
                v-if="removing"
                indeterminate
                color="primary"
        ></v-progress-circular>
        <v-tooltip bottom>
            <v-btn slot="activator" v-role="'IncidentsManager'" v-if="pendingTags.length > 0" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
              <v-icon>add</v-icon>
            </v-btn>
            <span>Afegir una etiqueta</span>
        </v-tooltip>

        <v-dialog v-model="tagAddDialog" max-width="500px">
          <v-card>
            <v-card-text>

            <v-autocomplete
                    v-model="newTag"
                    :items="pendingTags"
                    attach
                    chips
                    label="Etiquetes"
                    :return-object="true"
            >
                <template slot="selection" slot-scope="data">
                    <v-chip
                            label
                            @input="data.parent.selectItem(data.item)"
                            :selected="data.selected"
                            class="chip--select-multi"
                            :color="data.item.color"
                            text-color="white"
                            :key="JSON.stringify(data.item)"
                    ><v-icon left v-text="data.item.icon"></v-icon>{{ data.item.value }}</v-chip>
                </template>
                <template slot="item" slot-scope="data">
                    <v-chip small label :title="data.item.description" :color="data.item.color" text-color="white">
                        <v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}
                    </v-chip>
                </template>
           </v-autocomplete>

          </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="tagAddDialog=false">Tancar</v-btn>
            <v-btn color="success" flat @click="add()" :loading="adding" :disabled="adding || newTag === null">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>

    </span>
</template>

<script>

export default {
  name: 'IncidentTags',
  data () {
    return {
      removing: false,
      newTag: null,
      adding: false,
      tagAddDialog: false,
      tagRemoveDialog: false,
      close: [],
      pendingTags: [],
      incidentTags: []
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    },
    tags: {
      type: Array,
      required: true
    }
  },
  watch: {
    incident: {
      handler: function (newIncident) {
        this.sync(newIncident.tags)
      },
      deep: true
    },
    close (close) {
      let tagObjToRemove = this.incidentTags[close.indexOf(false)]
      if (tagObjToRemove && this.$hasRole('IncidentsManager')) {
        this.remove(tagObjToRemove)
      }
    }
  },
  methods: {
    pendingTagsToAssign () {
      const tagsIds = this.incidentTags.map(tag => tag['id'])
      return this.tags.filter(tag => {
        return !tagsIds.includes(tag.id)
      })
    },
    add () {
      this.adding = true
      window.axios.post('/api/v1/incidents/' + this.incident.id + '/tags/' + this.newTag.id, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.tagAddDialog = false
        this.newTag = null
        this.pendingTags.splice(this.pendingTags.indexOf(this.newTag), 1)
      }).catch(error => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    remove (tag) {
      this.removing = true
      const url = '/api/v1/incidents/' + this.incident.id + '/tags/' + tag.id
      window.axios.delete(url).then(() => {
        this.$emit('refresh')
        this.removing = false
        this.tagRemoveDialog = false
        this.pendingTags.push(tag)
      }).catch(error => {
        this.$snackbar.showError(error)
        this.removing = false
      })
    },
    showAddDialog () {
      this.tagAddDialog = true
    },
    showRemoveDialog () {
      this.tagRemoveDialog = true
    },
    syncClose () {
      this.close = []
      this.incidentTags.forEach(tag => {
        this.close.push(true)
      })
    },
    sync (tags) {
      this.incidentTags = this.incident.tags
      this.syncClose()
      this.pendingTags = this.pendingTagsToAssign()
    }
  },
  created () {
    this.sync(this.incident.tags)
  }
}
</script>
