<template>
    <span>
        <v-chip small label :color="tag.color" text-color="white" v-for="(tag,key) in studyTags" :key="tag.id" close v-model="close[key]">
            <v-icon left v-text="tag.icon"></v-icon>{{ tag.value }}
        </v-chip>
        <v-progress-circular
                size="16"
                v-if="removing"
                indeterminate
                color="primary"
        ></v-progress-circular>
        <v-btn v-role="'CurriculumManager'" v-if="pendingTags.length > 0" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
          <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="tagAddDialog" max-width="500px">
          <v-card>
            <v-card-text>

            <study-tags-select
                        v-model="newTag"
                        :multiple="false"
                        :return-object="true"
                        :tags="pendingTags"
            >
            </study-tags-select>
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
import StudyTagsSelect from './StudyTagsSelect'

export default {
  name: 'StudyTags',
  components: {
    'study-tags-select': StudyTagsSelect
  },
  data () {
    return {
      removing: false,
      newTag: null,
      adding: false,
      tagAddDialog: false,
      tagRemoveDialog: false,
      close: [],
      pendingTags: [],
      studyTags: [],
      dataTags: []
    }
  },
  props: {
    tags: {
      type: Array,
      required: false
    },
    study: {
      type: Object,
      required: true
    }
  },
  computed: {
    storeTags () {
      return this.$store.getters.studiesTags
    }
  },
  watch: {
    tags (tags) {
      this.dataTags = tags
    },
    study: {
      handler: function (newStudy) {
        this.sync(newStudy.tags)
      },
      deep: true
    },
    close (close) {
      let tagObjToRemove
      if (this.studyTags) {
        tagObjToRemove = this.studyTags[close.indexOf(false)]
      }
      if (tagObjToRemove && this.$hasRole('CurriculumManager')) {
        this.remove(tagObjToRemove)
      }
    }
  },
  methods: {
    pendingTagsToAssign () {
      if (this.studyTags) {
        const tagsIds = this.studyTags.map(tag => tag['id'])
        return this.dataTags.filter(tag => {
          return !tagsIds.includes(tag.id)
        })
      }
      return []
    },
    add () {
      this.adding = true
      window.axios.post('/api/v1/studies/' + this.study.id + '/tags/' + this.newTag.id, {}).then(() => {
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
      const url = '/api/v1/studies/' + this.study.id + '/tags/' + tag.id
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
      if (this.studyTags) {
        this.studyTags.forEach(tag => {
          this.close.push(true)
        })
      }
    },
    sync (tags) {
      this.studyTags = tags
      this.syncClose()
      this.pendingTags = this.pendingTagsToAssign()
    }
  },
  created () {
    this.dataTags = this.tags ? this.tags : this.storeTags
    this.sync(this.study.tags)
  }
}
</script>
