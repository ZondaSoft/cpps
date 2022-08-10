<template>
    <div class="col m1 s1 input-field" style="margin-top: 0px;padding-left: 0px;padding-right: 0px;" id="search5">
        <div class="col m12 s12 input-field" style="padding-right: 0px;">
            <input id="id_nomen" type="text" v-model="keywords" autocomplete="off" maxlength="10" required>
            <label for="id_nomen">Código</label>
            <small class="errorTxt3"></small>
        </div>
        <!-- <div class="col m12 s12 input-field">
            <ul v-if="results.length > 0">
                <li v-for="result in results" :key="result.id" v-text="result.nom_prest"></li>
                <input id="matricula2" type="text" v-model="keywords" autocomplete="off" value="result.id_nomen">
            </ul>
        </div> -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            keywords: document.getElementById("nemotecnico_original").value,
            results: [],
            account_id: null
        };
    },
    watch: {
        keywords(after, before) {
            this.fetch();
        }
    },
    methods: {
        fetch() {
            axios.get('/searchProfessional', { params: { keywords: this.keywords } })
                .then(response => this.results = response.data)
                .catch(error => {});
        }
    }
}
</script>