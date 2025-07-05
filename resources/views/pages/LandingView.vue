<script>
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import DangerButton from "@/js/components/actions/DangerButton.vue";

export default {
    name: "LoginView",

    components: {
        PrimaryButton,
        DangerButton,
    },
    methods: {
        clickLogin() {
            console.log("Ir a login");
        },
        clickRegister() {
            console.log("Ir a register");
        },
    },
};
</script>

<template>
    <main class="flex-column">
        <section class="main__redirect flex">
            <div class="redirect__login flex-column">
                <h2>¿Ya tienes una cuenta?</h2>
                <router-link to="/auth">
                    <PrimaryButton label="Iniciar Sesión" />
                </router-link>
            </div>

            <div class="redirect__register flex-column">
                <h2>¿Quieres crear una cuenta?</h2>
                <router-link to="/auth/register">
                    <PrimaryButton label="Registrarme" />
                </router-link>
            </div>
        </section>

        <section class="main__description flex-column">
            <div class="description__title flex">
                <img src="img/landing/tools/perfume_comb.svg" alt="Decorative perfume and comb icon" />
                <h1>¿Qué ofrecemos?</h1>
                <img src="/img/landing/tools/razor.svg" alt="Decorative razor icon" />
            </div>

            <p>
                En <span class="span__corteclick">Corteclick</span> buscamos
                simplificar el proceso de reserva de citas en peluquerías y
                barberías locales. Así como ayudarte a encontrar tu look deseado
                de la forma más rápida y cómoda posible.
            </p>
        </section>
        <section class="main__how flex-column">
            <h1>¿Como funcionamos?</h1>
            <div class="how__timeline flex">
                <div class="timeline__item flex-column">
                    <p>Selecciona el local</p>
                    <img src="/img/landing/timeline/local.svg" alt="Local icon" />
                </div>
                <div class="timeline__item timeline__arrow">
                    <img src="/img/landing/timeline/orange-right-arrow.svg" alt="Arrow to next timeline item" />
                </div>
                <div class="timeline__item flex-column">
                    <p>Elige día y hora</p>
                    <img src="/img/landing/timeline/calendar.svg" alt="Calendar icon" />
                </div>
                <div class="timeline__item timeline__arrow">
                    <img src="/img/landing/timeline/orange-right-arrow.svg" alt="Arrow to next timeline item" />
                </div>
                <div class="timeline__item flex-column">
                    <p>Reserva tu cita y ¡Listo!</p>
                    <img src="/img/landing/timeline/check.svg" alt="Appointment icon" />
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

main {
    width: 100%;
    align-items: center;

    section {
        padding: 4rem;
    }

    .main__redirect {
        margin-bottom: 0;

        div {
            width: 25rem;
            padding: 1rem 2rem;
            text-align: center;

            h2 {
                margin-bottom: 1rem;
            }
        }

        .redirect__login {
            border-right: 1px solid map-get($colores, "gris_oscuro");
        }

        .redirect__register {
            border-left: 1px solid map-get($colores, "gris_oscuro");
        }
    }

    .main__description {
        background-color: map-get($colores, "gris_claro");
        color: map-get($colores, "azul_oscuro");

        width: 100%;
        align-items: center;

        .description__title {
            justify-content: space-evenly;
            align-items: center;
            margin-bottom: 0.5rem;

            img {
                width: 2rem;
                margin: 0 1rem;
            }
        }

        p {
            width: 55%;
        }
    }

    .main__how {
        align-items: center;

        .how__timeline {
            align-items: center;
            justify-content: center;

            .timeline__item {
                padding: 1rem 2rem;
                gap: 1rem;
                align-items: center;

                img {
                    width: 5rem;
                }
            }
        }
    }
}

@media (max-width: 1280px) {
    main {
        .main__description {
            p {
                width: 80%;
            }
        }
    }
}

@media (max-width: 900px) {
    main {
        .main__description {
            p {
                width: 90%;
            }
        }
    }
}

@media (max-width: 800px) {
    main {
        .main__redirect {
            padding: 2rem 10px;
        }

        .main__how {
            .how__timeline {
                flex-direction: column;

                .timeline__arrow {
                    transform: rotate(90deg);
                }
            }
        }
    }
}

@media (max-width: 768px) {
    main {
        .main__redirect {
            flex-direction: column;

            .redirect__login {
                border-right: none;
                border-bottom: 1px solid map-get($colores, "gris_oscuro");
            }

            .redirect__register {
                border-left: none;
                border-top: 1px solid map-get($colores, "gris_oscuro");
            }
        }

        .main__description {
            .description__title {
                width: 100%;
                white-space: nowrap;
            }

            p {
                width: 100%;
            }
        }
    }
}
</style>
