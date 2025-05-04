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
    <footer class="grid">
        <section class="technologies flex-column">
            <h2>Tecnologías usadas</h2>
            <div class="logos flex">
                <!-- <div class="flex">
                    <img src="/img/footer/html5.svg" alt="Icono de HTML5" />HTML
                </div>
                <div class="flex">
                    <img src="/img/footer/css.svg" alt="Icono de CSS" />CSS
                </div> -->
                <div class="flex">
                    <img src="/img/footer/sass.svg" alt="Icono de Sass" />SASS
                </div>
                <!-- <div class="flex">
                    <img
                        src="/img/footer/javascript.svg"
                        alt="Icono de JavaScript"
                    />JS
                </div> -->
                <div class="flex">
                    <img src="/img/footer/vue.svg" alt="Icono de Vue" />VUE
                </div>
                <!-- <div class="flex">
                    <img src="/img/footer/php.svg" alt="Icono de PHP" />PHP
                </div> -->
                <div class="flex">
                    <img
                        src="/img/footer/laravel.svg"
                        alt="Icono de Laravel"
                    />LARAVEL
                </div>
            </div>
        </section>
        <section class="developer flex-column">
            <h2>Desarrollador</h2>
            <div class="logos flex">
                <div>
                    <a
                        class="flex"
                        href="https://www.linkedin.com/in/hugossanchezz/"
                        target="_blank"
                    >
                        <img
                            src="/img/footer/linkedin.svg"
                            alt="Icono de Linkedin"
                        />
                        Linkedin</a
                    >
                </div>
                <div>
                    <a
                        class="flex"
                        href="https://github.com/hugossanchezz"
                        target="_blank"
                        ><img
                            src="/img/footer/github.svg"
                            alt="Icono de Github"
                        />Github</a
                    >
                </div>
            </div>
        </section>
        <section class="copyright flex-center">
            © {{ currentYear }} Corteclick S.L. Todos los derechos reservados.
        </section>
    </footer>
</template>

<style lang="scss">
@use "@/sass/variables" as *;

footer {
    background-color: map-get($colores, "azul_oscuro");
    color: map-get($colores, "blanco");
    @include fuente("parrafo");

    width: 100%;

    padding: 2rem 3rem;

    grid-template:
        "technologies developer" auto
        "copyright copyright" auto / 1fr 1fr;

    section {
        padding: 5px;
        align-items: center;

        h2 {
            margin-bottom: 5px;
        }
    }

    .technologies {
        grid-area: technologies;
    }

    .developer {
        grid-area: developer;
    }

    .logos {
        div {
            padding: 5px;
            align-items: center;
            gap: 5px;
            img {
                width: 15px;
            }
            a {
                color: map-get($colores, "blanco");
                text-decoration: none;
                gap: 5px;
                &:hover {
                    text-decoration: underline;
                }
            }
        }
    }
    .copyright {
        grid-area: copyright;
        border-top: 1px solid map-get($colores, "gris_claro");
        margin-top: 1rem;
        padding: 15px 10px;
    }
}
</style>
