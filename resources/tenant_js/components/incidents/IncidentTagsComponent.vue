<template>
    <span>
        <v-chip label :color="tag.color" text-color="white" v-for="(tag,key) in incident.tags" :key="tag.id" close v-model="close[key]">
            <v-icon left v-text="tag.icon"></v-icon>{{ tag.value }}
        </v-chip>
        <v-btn v-role="'IncidentsManager'" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
          <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="tagAddDialog" max-width="500px">
          <v-card>
            <v-card-text>

            <v-autocomplete
                    v-model="newTag"
                    :items="pendingTagsToAssign"
                    attach
                    chips
                    label="Etiquetes"
                    item-value="id"
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
                    <v-chip label :title="data.item.description" :color="data.item.color" text-color="white">
                        <v-icon left v-text="data.item.icon"></v-icon>{{ data.item.value }}
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
      tagToRemove: null,
      newTag: null,
      adding: false,
      tagAddDialog: false,
      tagRemoveDialog: false,
      close: []
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
  computed: {
    pendingTagsToAssign () {
      const tagsIds = this.incident.tags.map(tag => tag['id'])
      return this.tags.filter(tag => {
        return !tagsIds.includes(tag.id)
      })
    }
  },
  watch: {
    close (close) {
      let tagObjToRemove = this.incident.tags[close.indexOf(false)]
      if (tagObjToRemove && this.$hasRole('IncidentsManager')) {
        this.tagToRemove = tagObjToRemove.id
        this.remove()
      }
    }
  },
  methods: {
    add () {
      this.adding = true
      window.axios.post('/api/v1/incidents/' + this.incident.id + '/tags/' + this.newTag, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.tagAddDialog = false
        this.newTag = null
      }).catch(error => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    remove () {
      this.removing = true
      const url = '/api/v1/incidents/' + this.incident.id + '/tags/' + this.tagToRemove
      window.axios.delete(url).then(() => {
        this.$emit('refresh')
        this.removing = false
        this.tagRemoveDialog = false
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
    }
  },
  created () {
    this.incident.tags.forEach(tag => {
      this.close.push(true)
    })
  }
}
</script>
