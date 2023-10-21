<template>
    <div>
        <div v-if="isEmptyResult" class="container">
            Записей не обнаружено. <router-link to="/upload">Загрузите</router-link> данные.
        </div>
        <div v-else class="row justify-content-center">
            <div class="col-md-12">
                <div class="card position-relative">
                    <div v-if="showPreloader" class="preloader">
                        <div class="spinner"/>
                    </div>
                    <table class="table">
                        <thead>
                            <tr class="card-header">
                                <th scope="col">
                                    Код
                                </th>
                                <th scope="col">
                                    Наименование
                                </th>
                                <th scope="col">
                                    Уровень1
                                </th>
                                <th scope="col">
                                    Уровень2
                                </th>
                                <th scope="col">
                                    Уровень3
                                </th>
                                <th scope="col">
                                    Цена
                                </th>
                                <th scope="col">
                                    ЦенаСП
                                </th>
                                <th scope="col">
                                    Количество
                                </th>
                                <th scope="col">
                                    Поля свойств
                                </th>
                                <th scope="col">
                                    Совместные покупки
                                </th>
                                <th scope="col">
                                    Единица измерения
                                </th>
                                <th scope="col">
                                    Картинка
                                </th>
                                <th scope="col">
                                    Выводить на главной
                                </th>
                                <th scope="col">
                                    Описание
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="nomenclature in nomenclatures">
                                <th>
                                    {{ nomenclature.code }}
                                </th>
                                <th>
                                    {{ nomenclature.name }}
                                </th>
                                <th>
                                    {{ nomenclature.level1 }}
                                </th>
                                <th>
                                    {{ nomenclature.level2 }}
                                </th>
                                <th>
                                    {{ nomenclature.level3 }}
                                </th>
                                <th>
                                    {{ nomenclature.price }}
                                </th>
                                <th>
                                    {{ nomenclature.price_sp }}
                                </th>
                                <th>
                                    {{ nomenclature.quantity }}
                                </th>
                                <th>
                                    {{ nomenclature.properties }}
                                </th>
                                <th>
                                    {{ nomenclature.joint_purchases }}
                                </th>
                                <th>
                                    {{ nomenclature.measurement }}
                                </th>
                                <th>
                                    {{ nomenclature.image_path }}
                                </th>
                                <th>
                                    {{ nomenclature.show_main }}
                                </th>
                                <th>
                                    {{ nomenclature.description }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li v-for="link in links">
                            <a class="page-link cursor-pointer" :class="{'bg-secondary text-light': this.page == link.label}" v-html="link.label" @click.prevent="getList(link.url)"></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'NomenclatureList',

    data() {
        return {
            nomenclatures: [],
            showPreloader: false,
            links: [],
            page: 1,
        }
    },
    computed: {
        isEmptyResult() {
            return this.nomenclatures.length === 0;
        }
    },
    mounted() {
        this.getList();
    },
    methods: {
        getList(url = '/api/nomenclature') {
            this.showPreloader = true;
            axios.get(url)
                .then((response) => {
                    this.links = response.data.links
                    this.nomenclatures = response.data.data
                    this.page = response.data.current_page
                })
                .finally(() => {
                    this.showPreloader = false;
                })
        },
    },
}
</script>
