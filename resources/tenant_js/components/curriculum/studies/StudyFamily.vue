<template>
    <span>
        <span :title="study.family_code" v-text="study.family_name"></span>
        <v-btn v-role="'CurriculumManager'" icon flat color="teal" class="text--white ma-0" @click="assignDialog = true">
          <v-icon v-if="study.family_id">edit</v-icon>
          <v-icon v-else>add</v-icon>
        </v-btn>
        <v-dialog v-model="assignDialog" max-width="500px">
          <v-card>
            <v-card-text>
              <family-select
                      v-model="family"
                      :familys="familys"
                      :error-messages="familyErrors"
                      @input="$v.family.$touch()"
                      @blur="$v.family.$touch()"
              ></family-select>
            </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="assignDialog=false">Tancar</v-btn>
            <v-btn color="success" flat @click="assign" :loading="assigning" :disabled="assigning || this.$v.invalid">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
export default {
  name: 'StudyFamily',
  props: {
    studies: {
      type: Array,
      default: function () {
        return undefined
      }
    },
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
