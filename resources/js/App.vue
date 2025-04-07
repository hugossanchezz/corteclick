<template>
    <div id="app-container">
        <Header />
        <router-view></router-view>
        <Footer />
    </div>
</template>

<script>
import Header from "@/components/Common/Header.vue"; // Asegúrate de que la ruta a tu Header sea correcta
import Footer from "@/components/Common/Footer.vue"; // Asegúrate de que la ruta a tu Footer sea correcta

export default {
    components: {
        Header,
        Footer,
    },
};
</script>

<style scoped>
#app-container {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
}
</style>
