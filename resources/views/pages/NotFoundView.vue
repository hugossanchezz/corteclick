<script>
export default {
    name: "NotFoundView",
};
</script>

<template>
    <main class="not-found__container flex-center">
        <div class="container__modal glass-effect">
            <h1>404 - Página No Encontrada</h1>
            <p>¡Oops! Parece que la página que estás buscando no existe.</p>
            <p>
                Puedes volver a la
                <router-link to="/" class="span">
                    página principal
                </router-link>.
            </p>
        </div>
    </main>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.not-found__container {
    background-color: map-get($colores, "azul_oscuro");
    width: 100%;
    height: 100%;

    .container__modal {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: fit-content;
        padding: 20px 40px;
        border-radius: 8px;

        text-align: center;

        h1 {
            color: map-get($colores, "rojo");
            margin-bottom: 10px;
        }

        p {
            color: map-get($colores, "blanco");
            margin-bottom: 20px;
        }

        a {
            color: map-get($colores, "naranja");
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    }
}
</style>
