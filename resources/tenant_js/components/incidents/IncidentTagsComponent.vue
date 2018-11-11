<template>
    <span>
        <v-chip label :color="tag.color" text-color="white" v-for="(tag,key) in tags" :key="tag.id" close v-model="close[key]">
            <v-icon left v-text="tag.icon"></v-icon>{{ tag.value }}
        </v-chip>
        <v-btn v-role="'IncidentsManager'" icon flat color="teal" class="text--white ma-0" @click="add">
          <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="tagAddDialog" max-width="500px">
          <v-card>
            <v-card-text>
            TODO TAGS SELECT
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
    tags: {
      type: Array,
      required: true
    }
  },
  watch: {
    close (newValue) {
      console.log('close changed:')
      console.log(newValue)
      if (this.$hasRole('IncidentsManager')) {
        // TODO remove tag
      }
    }
  },
  methods: {
    add () {
      this.adding = true
      const url = '/api/v1/incidents/' + this.incident.id + '/tags/' + this.newTag
      window.axios.post(url, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.tagAddDialog = false
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
    this.tags.forEach(tag => {
      this.close.push(true)
    })
  }
}
</script>
