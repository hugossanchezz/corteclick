<template>
    <div class="app__container grid">
        <Header />
        <router-view></router-view>
        <Footer />
    </div>
</template>

<script>
import Header from "@/js/components/Common/Header.vue"; // Asegúrate de que la ruta a tu Header sea correcta
import Footer from "@/js/components/Common/Footer.vue"; // Asegúrate de que la ruta a tu Footer sea correcta

export default {
    components: {
        Header,
        Footer,
    },
};
</script>

<style scoped lang="scss">

.app__container{
    min-height: 100vh;
    grid-template-rows: auto 1fr auto;
}
</style>
