<template>
    <div>
        <span v-if="!editing" v-on:dblclick="startEdit">{{name}}</span>
        <input v-focus  @focus="$event.target.select()" type="text" v-else v-model="name" @keydown.esc="cancel" @keydown.enter="edit">
        <i class="fa fa-fw fa-edit green" @click="startEdit" v-if="!editing"></i>
        <template v-else>
            <i class="fa fa-fw fa-check red" @click="edit"></i>
            <i class="fa fa-fw fa-times green" @click="cancel"></i>
        </template>
    </div>
</template>

<style>
    .red {
        color: #f56954;
    }
    .green {
        color: #00a65a;
    }
</style>

<script>
import swal from 'sweetalert'

export default {
  data () {
    return {
      editing: false,
      name: this.value,
      oldValue: this.value
    }
  },
  props: {
    value: {
      type: String,
      required: true
    },
    tenant: {
      type: Object,
      required: true
    }
  },
  methods: {
    cancel () {
      this.editing = false
      this.name = this.oldValue
    },
    startEdit () {
      this.editing = true
      this.oldValue = this.name
    },
    edit () {
        axios.put('api/v1/tenant/' + this.tenant.id + '/name',{'name': this.name}) // eslint-disable-line
        .then(response => {
          this.editing = false
        }).catch(error => {
          console.log(error)
          this.editing = false
          this.oldValue = this.name
          swal('Error', 'Error editant el camp')
        })
    }
  },
  directives: {
    focus: {
      inserted: function (el) {
        el.focus()
      }
    }
  }
}
</script>
