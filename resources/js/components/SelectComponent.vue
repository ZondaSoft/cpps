<template>
    <div class="col m7 s7 input-field" style="margin-top: 0px;">
        <div class="col m7 s7 input-field">
            <select v-model="selected_id" @change="loadObras">
                <option value = "" selected>Seleccione una Obra</option>
                <!-- <option v-for="(os, index) in obras" :value="os.id">{{os.desc_os}}</option> -->

                <option v-for="(os, id) in obras" :key="id" :value="os.desc_os">
                    {{ os.desc_os }} {{ os.cod_os }}
                </option>
            </select>
            <label>Obra Social</label>
        </div>

        <div class="col m5 s5 input-field">
                <ul v-if="obras.length > 0">
                    <li v-for="result in obras" :key="result.id" v-text="result.desc_os"></li>
                </ul>
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
            axios.get('/get-oss', { params: {} })
                .then(response => this.obras = response.data)
                .catch(error => {});
        }
    }
}
</script>