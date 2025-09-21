<script>
import { useRoute } from "vue-router";
import { ref, computed } from "vue";

import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import SecondaryButton from "@/js/components/actions/SecondaryButton.vue";
import { isAuthenticated } from "@/js/auth/eventBus";

export default {
    name: "Header",

    components: {
        PrimaryButton,
        SecondaryButton,
    },

    setup() {
        const route = useRoute(); // Accede a la ruta actual

        // Detecta si estamos en la página de inicio (landing page)
        const isLandingPage = computed(() => route.path === "/");

        // Si el usuario está autenticado
        const estaLogueado = computed(() => isAuthenticated.value);

        // Controla si el menú móvil está abierto
        const menuAbierto = ref(false);

        // Alterna el estado del menú (abrir/cerrar)
        const toggleMenu = () => {
            menuAbierto.value = !menuAbierto.value;
        };

        return {
            isLandingPage,
            estaLogueado,
            menuAbierto,
            toggleMenu,
        };
    },
};
</script>

<template>
    <header class="flex-column" :class="{
        'landing_bg': isLandingPage,
        'header_fijo': !isLandingPage
    }">
        <div class=" header__navigation flex">
            <div class="navigation__logo">
                <router-link to="/"> Corteclik </router-link>
            </div>
            <button class="hamburger" @click="toggleMenu" aria-label="Menú">
                <img src="/img/utils/menu.svg" alt="Abrir menú" class="menu_icon" />
            </button>

            <nav class="navigation__nav flex" :class="{ abierto: menuAbierto }">
                <div class="nav__links flex">

                    <div class="links__item flex-center">
                        <router-link to="/" exact>Inicio</router-link>
                    </div>
                    <div class="links__item flex-center">
                        <router-link to="/locals">Pedir cita</router-link>
                    </div>
                    <div class="links__item flex-center">
                        <router-link to="/new-local">Registrar mi local</router-link>
                    </div>
                </div>
            </nav>
            <div class="navigation__auth flex-center" :class="{ abierto: menuAbierto }">
                <div v-if="!estaLogueado" class="auth__item flex-center">
                    <router-link to="/auth">
                        <SecondaryButton label="Iniciar Sesión" />
                    </router-link>
                </div>
                <div v-if="!estaLogueado" class="auth__item flex-center">
                    <router-link to="/auth/register">
                        <SecondaryButton label="Registrarme" />
                    </router-link>
                </div>
                <div v-if="estaLogueado" class="auth__item flex-center">
                    <router-link to="/user">
                        <svg class="auth__item__avatar" xmlns="http://www.w3.org/2000/svg" height="34px" width="34px"
                            viewBox="0 -960 960 960" fill="#f5f5f5">
                            <path
                                d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z" />
                        </svg>
                    </router-link>
                </div>
            </div>
        </div>
        <!-- MODAL MENÚ EN MÓVIL -->
        <div class="menu_modal" :class="{ visible: menuAbierto, hidden: !menuAbierto }">
            <div class="menu_modal__content">
                <img @click="toggleMenu" class="menu_close" src="/img/utils/close.svg" alt="Cerrar menú" />
                <div class="links__item">
                    <router-link to="/" @click="toggleMenu">Inicio</router-link>
                </div>
                <div class="links__item">
                    <router-link to="/locals" @click="toggleMenu">Pedir cita</router-link>
                </div>
                <div class="links__item">
                    <router-link to="/new-local" @click="toggleMenu">Registrar mi local</router-link>
                </div>
                <hr>
                <div class="menu_modal__auth flex-center">
                    <div class="auth__item" v-if="!estaLogueado">
                        <router-link to="/auth" @click="toggleMenu">
                            <SecondaryButton label="Iniciar Sesión" />
                        </router-link>
                    </div>
                    <div class="auth__item" v-if="!estaLogueado">
                        <router-link to="/auth/register" @click="toggleMenu">
                            <SecondaryButton label="Registrarme" />
                        </router-link>
                    </div>
                </div>
                <div class="auth__item" v-if="estaLogueado">
                    <router-link to="/user" @click="toggleMenu">
                        <svg class="auth__item__avatar" xmlns="http://www.w3.org/2000/svg" height="34px" width="34px"
                            viewBox="0 -960 960 960" fill="#f5f5f5">
                            <path
                                d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z" />
                        </svg>
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Fondo para no interactuar con el resto de la página cuando se abre el menú -->
        <div v-if="menuAbierto" class="modal_backdrop" @click="toggleMenu"></div>

        <div v-if="isLandingPage" class="header__carrusel flex-column">
            <div class="carrusel__modal glass-effect flex-column">
                <div class="modal__text">
                    ¡Consigue tu nuevo look con solo un click!
                </div>
                <router-link to="/locals">
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
        border-bottom: 1px solid map-get($colores, "gris_claro");

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

        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 10px;

            .menu_icon {
                width: 24px;
                height: 24px;
            }
        }


        .navigation__nav {
            width: 60%;
            padding: 0 20px;

            .links__item {
                a {
                    width: 100%;
                    height: 100%;
                    padding: 10px 20px;
                    white-space: nowrap;
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
                .auth__item__avatar {
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                    width: 34px;
                    height: 34px;
                    border-radius: 50%;

                    &:hover {
                        fill: map-get($colores, "naranja");
                        transform: scale(1.02);
                    }
                }
            }
        }
    }

    .menu_modal {
        position: fixed;
        top: 0;
        right: 0;
        width: fit-content;
        max-width: 70vh;
        height: 100vh;
        background-color: map-get($colores, "azul_oscuro");
        z-index: 999;
        display: flex;
        transition: transform 0.3s ease-in-out;
        transform: translateX(100%);
        border-left: 2px solid map-get($colores, "blanco");

        &.visible {
            transform: translateX(0); // entra desde la derecha
        }

        &.hidden {
            transform: translateX(100%); // sale hacia la derecha
        }

        .menu_modal__content {
            padding: 2rem;
            padding-top: 4rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;

            .menu_close {
                position: absolute;
                top: 1rem;
                right: 1rem;
                width: 24px;
                height: 24px;
                cursor: pointer;
            }

            a {
                color: map-get($colores, "blanco");
                font-size: 1.25rem;
                font-weight: bold;

                &:hover {
                    color: map-get($colores, "naranja");
                }
            }
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(0);
        }
    }

    .modal_backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.2); // oscuro con transparencia
        z-index: 998; // justo por debajo del menú (999)
        backdrop-filter: blur(2px); // desenfoque opcional
        pointer-events: auto;
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

.landing_bg {
    background-image: url("/img/header/carrusel/barber_razor.webp");
    background-size: cover;
    background-position: center;
}

.header_fijo {
    position: sticky;
    top: 0;
    z-index: 1;
}

// Estilos para tablet
@media (max-width: 1024px) {
    header {
        .header__navigation {
            .hamburger {
                display: block;
                position: absolute;
                top: 10px;
                right: 20px;
            }

            .navigation__nav,
            .navigation__auth {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: map-get($colores, "azul_oscuro");
                padding: 1rem 0;
                text-align: center;
            }

            .navigation__nav.abierto,
            .navigation__auth.abierto {
                display: flex;
            }

            .header__navigation {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px 20px;

                .navigation__logo {
                    width: 100%;
                }

                .navigation__auth {
                    width: 100%;
                    justify-content: center;
                }
            }
        }

        .navigation__nav,
        .navigation__auth {
            display: none !important;
        }

    }
}

@media (max-width: 628px) {
    header {

        .header__navigation {
            padding: 10px;

            .navigation__logo {
                width: 30%;
            }
        }

        .menu_modal__auth {
            flex-direction: column;
            gap: 10px;
        }

        .carrusel__modal {
            width: 80%;
            text-align: center;
        }
    }
}
</style>
