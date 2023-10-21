import {createRouter, createWebHistory} from "vue-router";
import NomenclatureList from "./components/NomenclatureList.vue";
import NomenclatureUpload from "./components/NomenclatureUpload.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: NomenclatureList,
        },
        {
            path: '/upload',
            component: NomenclatureUpload
        }
    ],
});

export default router
