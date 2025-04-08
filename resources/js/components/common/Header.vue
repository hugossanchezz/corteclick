<template>
    <header>
        <div class="container">
            <div class="logo">
                <router-link to="/">
                    <!-- <img src="/images/logo.png" alt="Corteclick" /> -->
                    LOGO
                </router-link>
            </div>

            <nav class="main-navigation">
                <ul>
                    <li><router-link to="/" exact>Inicio</router-link></li>
                    <li><router-link to="/locals">Locales</router-link></li>
                    <li>
                        <router-link to="/new-local"
                            >Registrar mi local</router-link
                        >
                    </li>
                </ul>

                <div class="auth-buttons">
                    <router-link to="/login" class="login-button"
                        >Iniciar Sesión</router-link
                    >
                    <router-link to="/register" class="register-button"
                        >Registrarse</router-link
                    >
                </div>
            </nav>
        </div>
        <div class="carrusel"></div>
    </header>
</template>

<script>
export default {
    name: "Header",
};
</script>

<style scoped>
</style>
