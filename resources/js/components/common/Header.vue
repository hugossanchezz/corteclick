<script>
export default {
    name: "Header",

    data() {
        return {
            isLandingPage: false,
        };
    },
};
</script>

<template>
    <header class="flex-column">
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
                        <router-link to="/appointment">Pedir cita</router-link>
                    </div>
                    <div class="links__item flex-center">
                        <router-link to="/new-local"
                            >Registrar mi local</router-link
                        >
                    </div>
                </div>
            </nav>
            <div class="navigation__auth flex">
                <div class="auth__item flex-center">
                    <router-link to="/login" class="login-button"
                        >Iniciar Sesión</router-link
                    >
                </div>
                <div class="auth__item flex-center">
                    <router-link to="/register" class="register-button"
                        >Registrarse</router-link
                    >
                </div>
            </div>
        </div>
        <div v-if="isLandingPage" class="carrusel">
            carrusel de imagenes en la landing
        </div>
    </header>
</template>

<style scoped lang="scss">
@use '@/sass/variables' as *;

header {
    background-color: map-get($colores, "azul_oscuro");
    padding: 10px 50px;

    a {
        color: map-get($colores, "blanco");
        @include fuente("titulo_secundario");
        text-decoration: none;

        &:hover {
            color: map-get($colores, "naranja");
        }
    }

    .navigation__logo {
        a {
            @include fuente("logo");
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
        width: 100%;
        justify-content: space-around;
    }

    .navigation__auth {
        width: 30%;
    }

    .links__item,
    .auth__item {
        padding: 10px 20px;
    }

    .carrusel {
        color: map-get($colores, "blanco");
    }
}
</style>
