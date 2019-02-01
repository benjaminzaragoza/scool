<template>
    <v-card>
        <v-card-text>
            TODO SELECT TIPUS USUARI
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    class="grey--text"
                    flat
                    @click="$emit('close')"
            >
                Cancel·lar
            </v-btn>
            <v-btn
                    color="primary"
                    @click="$emit('close')"
            >
                Canviar
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
  name: 'UserTypeCardForm'
}
</script>
