<template>
    <div class="col m7 s7 input-field" style="margin-top: 0px;">
        <v-select
            v-model="selected_id"
            label="desc_os"
            :options="obras"
            class="input-field"
            style="border-style: hidden none inset;"
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