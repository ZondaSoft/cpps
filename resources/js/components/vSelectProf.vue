<template>
    <div class="col m8 s8" style="margin-top: 0px;">
        <v-select id="mat_prov_cole"
            v-model="selected_id"
            label="nom_ape"
            :options="profesionales"
            class="input-field"
            style="border-style: hidden none inset;"
        ></v-select>
        <label>Matricula - Apellido y Nombre</label>
    </div>
</template>
<script>
export default {
    data() {
        return {
            selected_id: null,
            profesionales: []
        }
    },
    mounted() {
        this.loadProfesionales();
    },
    methods: {
        loadProfesionales() {
            axios.get('/get-prof', { params: {} })
                .then(response => this.profesionales  = response.data)
                .catch(error => {});
        }
    }
}
</script>