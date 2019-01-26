<template>
    <a :href="href()" target="_blank">{{text}}</a>
</template>

<script>
export default {
  name: 'UserShowLink',
  props: {
    id: {
      required: false
    },
    user: {
      type: Object,
      required: false
    },
    text: {
      type: String,
      required: true
    }
  },
  methods: {
    href () {
      return '/users/' + this.getId()
    },
    getId () {
      if (this.id) return this.id
      if (this.user) return this.user.id
    }
  }
}
</script>
