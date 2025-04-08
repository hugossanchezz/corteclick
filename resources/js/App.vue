<template>
    <Header />
    <router-view></router-view>
    <Footer />
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
