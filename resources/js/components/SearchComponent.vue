<template>
    <div class="col m5 s5 input-field" style="margin-top: 0px;">
        <div class="col m4 s4 input-field">
            <input id="cod_os" type="text" v-model="keywords" autocomplete="off">
            <label for="cod_os">Obra Social</label>
            <small class="errorTxt1"></small>
        </div>
        <div class="col m8 s8 input-field">
            <ul v-if="results.length > 0">
                <li v-for="result in results" :key="result.id" v-text="result.desc_os"></li>
            </ul>
        </div>

        <div class="col m8 s8 input-field">
            <ul v-if="results.length > 0">
                <li v-for="result in results" :key="result.id" v-text="result.desc_os"></li>
            </ul>
        </div>

        
    </div>
</template>
<script>
export default {
    data() {
        return {
            keywords: null,
            results: []
        };
    },
    watch: {
        keywords(after, before) {
            this.fetch();
        }
    },
    methods: {
        fetch() {
            axios.get('/res-search', { params: { keywords: this.keywords } })
                .then(response => this.results = response.data)
                .catch(error => {});
        }
    }
}
</script>