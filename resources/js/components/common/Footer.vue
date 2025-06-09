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
    <footer class="flex-center">
        <section class="flex-center">

            <div class="copyright flex-center">
                © {{ currentYear }} Corteclick S.L. Todos los derechos reservados.
            </div>
            
            <div class="developer flex">
                <strong>Desarrollador</strong>
                <img src="/img/footer/double_arrow_white.svg" alt="Desarrollador">
                <div class="logos flex">
                    <div>
                        <a class="flex" href="https://www.linkedin.com/in/hugossanchezz/" target="_blank">
                            <img src="/img/footer/linkedin.svg" alt="Icono de Linkedin" />
                            Linkedin</a>
                    </div>
                    <div>
                        <a class="flex" href="https://github.com/hugossanchezz" target="_blank"><img
                                src="/img/footer/github.svg" alt="Icono de Github" />Github</a>
                    </div>
                </div>
            </div>

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
    
    section {
        width: 80%;
        gap: 1rem;
    }

    .developer {
        align-items: center;

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
    }
}
</style>
