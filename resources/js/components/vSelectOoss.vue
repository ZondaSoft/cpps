<template>
    <div style="margin-top: 0px;">
        <v-select
            id="mat_prov_cole""
            v-model="selected_id"
            label="desc_os"
            clearable=false
            :options="obras"
            class="input-field"
        ></v-select>
        <label>Obra Social</label>
    </div>
</template>
<script>
export default {
    data() {
        return {
            selected_id: null,
            obras: []
        }
    },
    mounted() {
        this.loadObras();
    },
    methods: {
        loadObras() {
            axios.get('/get-oss', { params: {} })
                .then(response => this.obras  = response.data)
                .catch(error => {});
        }
    }
}
</script>


<style>
    #mat_prov_cole .v-select .dropdown-toggle {
        border: none;
        }
</style>