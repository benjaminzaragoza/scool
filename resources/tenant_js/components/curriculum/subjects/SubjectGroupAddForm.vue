<template>
    <form>
        <study-select
                v-model="dataStudy"
                :studies="studies"
                :departments="departments"
                :families="families"
                :error-messages="dataStudyErrors"
                @input="$v.dataStudy.$touch()"
                @blur="$v.dataStudy.$touch()"
        ></study-select>
        <subject-group-select
                v-model="dataSubjectGroup"
                :subject-groups="subjectGroups"
                :error-messages="dataSubjectGroupErrors"
                @input="$v.dataSubjectGroup.$touch()"
                @blur="$v.dataSubjectGroup.$touch()"
        ></subject-group-select>
        <courses-select
                v-model="dataCourse"
                :courses="courses"
                :error-messages="dataCourseErrors"
                @input="$v.dataCourse.$touch()"
                @blur="$v.dataCourse.$touch()"
        ></courses-select>

        <subject-number
                v-model="number"
                @input="$v.number.$touch()"
                @blur="$v.number.$touch()"
                :error-messages="numberErrors"
                :study="dataStudy"
                :subject-group="dataSubjectGroup"
        ></subject-number>

        <subject-code
                v-model="code"
                @input="$v.code.$touch()"
                @blur="$v.code.$touch()"
                :course="dataCourse"
                :subject-group="dataSubjectGroup"
                :number="number"
                :error-messages="codeErrors"
        ></subject-code>

        <v-text-field
                ref="name"
                v-model="name"
                name="name"
                label="Nom"
                :error-messages="nameErrors"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
                hint="Nom de la Unitat Formativa"
        ></v-text-field>

        <v-text-field
                v-model="shortname"
                name="shortname"
                label="Nom curt"
                :error-messages="shortnameErrors"
                @input="$v.shortname.$touch()"
                @blur="$v.shortname.$touch()"
                hint="Nom curt de la UF"
        ></v-text-field>

        <v-text-field
                v-model="hours"
                name="hours"
                label="Hores"
                :error-messages="hoursErrors"
                @input="$v.hours.$touch()"
                @blur="$v.hours.$touch()"
                hint="Número d'hores totals de la UF"
        ></v-text-field>

        <v-menu
                :close-on-content-click="false"
                v-model="startMenu"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
        >
            <v-text-field
                    slot="activator"
                    v-model="start"
                    label="Data d'inici"
                    prepend-icon="event"
                    readonly
            ></v-text-field>
            <v-date-picker
                    v-model="start"
                    @input="startMenu = false"
                    :allowed-dates="allowedDates"
                    locale="ca-es"
                    :first-day-of-week="1"
            ></v-date-picker>
        </v-menu>

        <v-menu
                :close-on-content-click="false"
                v-model="endMenu"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
        >
            <v-text-field
                    slot="activator"
                    v-model="end"
                    label="Data fí"
                    prepend-icon="event"
                    readonly
            ></v-text-field>
            <v-date-picker
                    v-model="end"
                    @input="endMenu = false"
                    :allowed-dates="allowedDates"
                    locale="ca-es"
                    :first-day-of-week="1"
            ></v-date-picker>
        </v-menu>

        <v-btn @click="add(true)"
               color="success"
               :disabled="adding || $v.$invalid"
               :loading="adding">Afegir UF</v-btn>

        <v-btn @click="add(false)"
               color="primary"
               :disabled="adding || $v.$invalid"
               :loading="adding">Afegir UF i continuar</v-btn>
    </form>
</template>

<script>
export default {
  name: 'SubjectGroupAdd'
}
</script>
