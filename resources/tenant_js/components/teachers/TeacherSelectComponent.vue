<template>
    <v-select
            :name="name"
            :label="label"
            :items="teachers"
            v-model="internalTeacher"
            item-text="name"
            chips
            autocomplete
            clearable
            @input="input"
    >
        <template slot="selection" slot-scope="data">
            <v-chip
                    @input="data.parent.selectItem(data.item)"
                    :selected="data.selected"
                    class="chip--select-multi"
                    :key="JSON.stringify(data.item)"
            >
                <user-avatar :hash-id="data.item.hashid"
                             :alt="data.item.name"
                ></user-avatar>
                {{ data.item.name }}
            </v-chip>
        </template>
        <template slot="item" slot-scope="{ item: teacher }">
            <v-list-tile-avatar>
                <img :src="'/user/' + teacher.hashid + '/photo'">
            </v-list-tile-avatar>
            <v-list-tile-content>
                <v-list-tile-title v-html="teacher.name + ' (' + teacher.code + ')'"></v-list-tile-title>
                <v-list-tile-sub-title v-html="teacher.email"></v-list-tile-sub-title>
            </v-list-tile-content>
        </template>
    </v-select>
</template>

<style>

</style>

<script>
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  components: {
    'user-avatar': UserAvatar
  },
  data () {
    return {
      internalTeacher: this.teacher
    }
  },
  model: {
    prop: 'teacher',
    event: 'input'
  },
  props: {
    name: {
      type: String,
      default: 'teacher'
    },
    teacher: {
      type: Object
    },
    label: {
      type: String,
      default: 'Escolliu un professor'
    },
    teachers: {
      type: Array,
      required: true
    }
  },
  watch: {
    teacher (newTeacher) {
      this.internalTeacher = newTeacher
    }
  },
  methods: {
    input (value) {
      this.$emit('input', value)
    }
  }
}
</script>
