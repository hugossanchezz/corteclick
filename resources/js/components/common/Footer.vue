<script>
export default {
    name: "AppFooter",

    data() {
        return {
            currentYear: new Date().getFullYear(), // Obtener el año actual
        };
    },
};
</script>
<template>
    <footer class="main-footer">
        <section class="copyright">
            © {{ currentYear }} Corteclick.
            <a
                class="autor"
                href="https://github.com/hugossanchezz"
                target="_blank"
                >Hugo Sánchez Ciudad</a
            >.
        </section>
    </footer>
</template>

<style lang="scss">
@use '@/sass/variables' as *;

footer {
    background-color: map-get($colores, "azul_oscuro");
    color: map-get($colores, "blanco");
    @include fuente("parrafo");

    width: 100%;

    padding: 30px 0;
    text-align: center;

    .copyright a {
        color: map-get($colores, "naranja");
    }
}
</style>
