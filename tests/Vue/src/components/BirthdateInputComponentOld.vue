<template>
    <v-menu
            ref="menu"
            lazy
            :close-on-content-click="false"
            v-model="birthdateMenu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-right="40"
            min-width="290px"
    >
        <v-text-field
                name="formattedBirthdate"
                hint="format DD/MM/AAAA"
                persistent-hint
                slot="activator"
                label="Data de naixement"
                :value="formattedBirthdate" @change.native="formattedBirthdate = $event.target.value"
                :error-messages="birthdateErrors"
                @input="$v.birthdate.$touch()"
                @blur="$v.birthdate.$touch()"
                prepend-icon="event"
        ></v-text-field>
        <v-date-picker
                ref="picker"
                locale="ca"
                :value="birthdate" @change.native="birthdate = $event.target.value"
                @change="saveBirthdate"
                min="1900-01-01"
                :max="new Date().toISOString().substr(0, 10)"
        ></v-date-picker>
    </v-menu>
</template>