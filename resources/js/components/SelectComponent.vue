<template>
    <div class="col m7 s7 input-field" style="margin-top: 0px;">
        <div class="col m7 s7 input-field">
            <select v-model="selected_id" @change="loadObras">
                <option value = "" selected>Seleccione una Obra</option>
                <option v-for="result in obras" v-bind="result.id">
                    @{{ result.desc_os }}
                </option>
            </select>
            <label>Obra Social</label>
        </div>
        
    </div>
</template>
<script>
export default {
    data() {
        return {
            selected_id: 0,
            obras: []
        }
    },
    methods: {
        loadObras() {
            axios.get('/get-oss', { params: { keywords: this.selected_id } })
                .then(response => this.obras = response.data)
                .catch(error => {});
        }
    }
}
</script>