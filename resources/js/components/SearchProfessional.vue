<template>
    <div class="col m2 s2 input-field" style="margin-top: 0px;padding-left: 0px;margin-bottom: 0px;" id="search2">
        <div class="col m12 s12 input-field">
            <input id="mat_prov_cole" name="mat_prov_cole" type="text" v-model="keywords" autocomplete="off" maxlength="10" required>
            <label for="mat_prov_cole" id="lblMatricula" name="lblMatricula">Matrícula</label>
            <small class="errorTxt3"></small>
        </div>
        <!-- <div class="col m12 s12 input-field">
            <ul v-if="results.length > 0">
                <li v-for="result in results" :key="result.id" v-text="result.nom_ape"></li>
                <input id="matricula2" type="text" v-model="keywords" autocomplete="off" value="result.mat_prov_cole">
            </ul>
        </div> -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            keywords: document.getElementById("matricula_original").value,
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