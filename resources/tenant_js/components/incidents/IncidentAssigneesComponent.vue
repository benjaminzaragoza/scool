<template>
    <span>
        <user-avatar class="mr-2" :hash-id="assignee.hashid" v-for="assignee in assignees" :key="assignee.id"
                     :alt="assignee.name"
                     v-if="assignee.hashid"
                     @dblclick="removeAssignee(assignee)"
        ></user-avatar>
        <v-btn v-role="'IncidentsManager'" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
          <v-icon>add</v-icon>
        </v-btn>
        <v-btn v-role="'IncidentsManager'" icon flat color="error" class="text--white ma-0" @click="showRemoveDialog" v-if="assignees.length > 0">
          <v-icon>remove</v-icon>
        </v-btn>
        <v-dialog v-model="assigneeAddDialog" max-width="500px">
          <v-card>
            <v-card-text>
            <user-select
                    label="Assignar a:"
                    :users="pendingUsersToAssign"
                    v-model="newAssignee"
            ></user-select>
          </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="assigneeAddDialog=false">Tancar</v-btn>
            <v-btn color="success" flat @click="add()" :loading="adding" :disabled="adding || newAssignee === null">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="assigneeRemoveDialog" max-width="500px">
          <v-card>
            <v-card-text>
            <user-select
                    label="Treure l'assignació a:"
                    :users="assignees"
                    v-model="newDesassignee"
            ></user-select>
          </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="assigneeRemoveDialog=false">Tancar</v-btn>
            <v-btn color="success" flat @click="remove()" :loading="removing" :disabled="removing || newDesassignee === null">Treure</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'
import UserSelect from '../users/UsersSelectComponent'

export default {
  name: 'IncidentAssignees',
  components: {
    'user-avatar': UserAvatar,
    'user-select': UserSelect
  },
  data () {
    return {
      removing: false,
      newDesassignee: null,
      newAssignee: null,
      adding: false,
      assigneeAddDialog: false,
      assigneeRemoveDialog: false
    }
  },
  computed: {
    pendingUsersToAssign () {
      const assigneesIds = this.assignees.map(assignee => assignee['id'])
      return this.incidentUsers.filter(user => {
        return !assigneesIds.includes(user.id)
      })
    }
  },
  props: {
    assignees: {
      type: Array,
      required: true
    },
    incident: {
      type: Object,
      required: true
    },
    incidentUsers: {
      type: Array,
      default: function () {
        return []
      }
    }
  },
  methods: {
    showAddDialog () {
      this.assigneeAddDialog = true
    },
    add () {
      this.adding = true
      const url = '/api/v1/incidents/' + this.incident.id + '/assignees/' + this.newAssignee
      window.axios.post(url, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.assigneeAddDialog = false
        this.newAssignee = null
      }).catch(error => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    showRemoveDialog () {
      this.assigneeRemoveDialog = true
    },
    remove () {
      this.removing = true
      const url = '/api/v1/incidents/' + this.incident.id + '/assignees/' + this.newDesassignee
      window.axios.delete(url).then(() => {
        this.$emit('refresh')
        this.removing = false
        this.assigneeRemoveDialog = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.removing = false
      })
    },
    removeAssignee (assignee) {
      if (!this.$hasRole('IncidentsManager')) return
      console.log('removeAssignee')
      console.log(assignee)
    }
  }
}
</script>
