<script>
import { useRoute } from 'vue-router';
import { ref,computed, onMounted, onBeforeUnmount } from 'vue';

import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import SecondaryButton from "@/js/components/actions/SecondaryButton.vue";

export default {
    name: "Header",

    components: {
        PrimaryButton,
        SecondaryButton,
    },
    setup() {
        const route = useRoute();
        const isLandingPage = computed(() => route.path === '/');
        const isAuthenticated = ref(false);

        return {
            isLandingPage,
            isAuthenticated,
        };
    },
};
</script>

<template>
    <header class="flex-column" :class="{ 'landing-bg': isLandingPage }">
        <div class="header__navigation flex">
            <div class="navigation__logo">
                <router-link to="/"> Corteclik </router-link>
            </div>

            <nav class="navigation__nav flex">
                <div class="nav__links flex">
                    <div class="links__item flex-center">
                        <router-link to="/" exact>Inicio</router-link>
                    </div>
                    <div class="links__item flex-center">
                        <router-link to="/locals">Pedir cita</router-link>
                    </div>
                    <div class="links__item flex-center">
                        <router-link to="/new-local"
                            >Registrar mi local</router-link
                        >
                    </div>
                </div>
            </nav>
            <div class="navigation__auth flex-center">
                <div v-if="!isAuthenticated" class="auth__item flex-center">
                    <router-link to="/auth">
                        <SecondaryButton label="Iniciar Sesión" />
                    </router-link>
                </div>
                <div v-if="!isAuthenticated" class="auth__item flex-center">
                    <router-link to="/auth/register">
                        <SecondaryButton label="Registrarme" />
                    </router-link>
                </div>
                <div v-if="isAuthenticated" class="auth__item flex-center">
                    <router-link to="/profile">
                        <svg
                            class="profile__icon"
                            xmlns="http://www.w3.org/2000/svg"
                            height="34px"
                            width="34px"
                            viewBox="0 -960 960 960"
                            fill="#f5f5f5"
                        >
                            <path
                                d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"
                            />
                        </svg>
                    </router-link>
                </div>
            </div>
        </div>
        <div v-if="isLandingPage" class="header__carrusel flex-column">
            <div class="carrusel__modal glass-effect flex-column">
                <div class="modal__text">
                    ¡Consigue tu nuevo look con solo un click!
                </div>
                <router-link to="/appointment">
                    <PrimaryButton label="Pedir Cita" />
                </router-link>
            </div>
        </div>
    </header>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

header {
    background-color: map-get($colores, "azul_oscuro");

    a {
        color: map-get($colores, "blanco");
        @include fuente("titulo_secundario");
        text-decoration: none;
    }

    .header__navigation {
        padding: 10px 50px;

        .navigation__logo {
            width: 10%;
            a {
                @include fuente("logo");

                &:hover {
                    color: map-get($colores, "naranja");
                }
            }
        }

        .navigation__logo a {
            display: inline-block;
            width: 100%;
            height: 100%;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navigation__nav {
            width: 60%;
            padding: 0 20px;
            .links__item {
                a {
                    width: 100%;
                    height: 100%;
                    padding: 10px 20px;
                }

                &:hover {
                    border-bottom: 1px solid map-get($colores, "naranja");
                }
            }
        }

        .navigation__auth {
            width: 30%;
            justify-content: end;

            .auth__item {
                .profile__icon {
                    transition: fill 0.3s ease-in-out;

                    &:hover {
                        fill: map-get($colores, "naranja");
                    }
                }
            }
        }
    }

    .header__carrusel {
        height: 30rem;

        justify-content: center;
        align-items: center;

        .carrusel__modal {
            padding: 1rem;
            border-radius: 5px;
            align-items: center;

            .modal__text {
                color: map-get($colores, "blanco");
                @include fuente("titulo_principal");

                margin-bottom: 1rem;
            }

            img {
                width: 100%;
            }
        }
    }
}

.landing-bg {
    background-image: url("/img/landing/carrusel/barber_pole.webp");
    background-size: cover;
    background-position: center;
}
</style>
