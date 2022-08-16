<template>
    <div class="col m2 s2 input-field" style="margin-top: 0px;padding-left: 0px;" id="search1">
        <div class="col m12 s12 input-field" style="margin-bottom: 0px;">
            <input id="cod_os" type="text" v-model="keywords" autocomplete="off" maxlength="10" required>
            <label for="cod_os" class="active" id="lblCod_os" name="lblCod_os">Obra Social</label>
            <small class="errorTxt1"></small>
        </div>
        <!-- <div class="col m5 s5 input-field">
            <ul v-if="results.length > 0">
                <li v-for="result in results" :key="result.id" v-text="result.desc_os"></li>
                <input id="nom_os" type="text" v-model="keywords" autocomplete="off" value="result.cod_os">
            </ul>
        </div> -->
        
    </div>
</template>
<script>
// import axios from 'axios';

export default {
    data() {
        return {
            keywords: document.getElementById("cod_os_original").value,
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
            axios.get('/searchOoss', { params: { keywords: this.keywords } })
                .then(response => this.results = response.data)
                .catch(error => {});
        }
    }
}
</script>