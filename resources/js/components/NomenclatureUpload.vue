<template>
    <div class="position-relative min-h-500">
        <div v-if="showPreloader" class="preloader">
            <div class="spinner"/>
        </div>
        <div class="container">
            <div>
                <input type="file" @change="uploadFile" ref="file" required accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                <button @click="submitFile">Загрузить</button>
            </div>
            <div v-if="this.message" class="mt-4">
                {{ this.message }}
            </div>
            <div v-for="(error, key) in errors" class="mt-2 text-danger d-flex gap-4">
                {{ key }}: {{ error }}
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'NomenclatureUpload',

    data() {
        return {
            image: null,
            showPreloader: false,
            errors: [],
            message: null,
        }
    },
    methods: {
        uploadFile() {
            this.image = this.$refs.file.files[0]
        },
        submitFile() {
            if (!this.image) {
                this.errors = {file: ['Выберите файл']}
                return
            }
            this.showPreloader = true;
            this.message = null;
            this.errors = [];

            const formData = new FormData();
            formData.append('file', this.image);
            const headers = { 'Content-Type': 'multipart/form-data' };
            axios.post('/api/nomenclature/upload', formData, { headers })
                .then((res) => {
                    this.message = res.data.message
                })
                .catch((err) => {
                    this.errors = err.response.data.errors
                    this.message = err.response.data.message
                    console.log(this.errors)
                })
                .finally(() => {
                    this.showPreloader = false;
                });
        },
    },
}
</script>
