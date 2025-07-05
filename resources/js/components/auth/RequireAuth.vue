<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";

export default {
    name: "RequireAuth",
    components: { PrimaryButton },
    setup() {
        const isLoggedIn = ref(false); // Variable reactiva para saber si el usuario está logueado
        const router = useRouter();    // Acceso al enrutador de Vue

        // Redirige al usuario a la página de login
        const redirectToLogin = () => {
            router.push("/auth");
        };

        // Al montar el componente, verifica si hay un usuario guardado en sessionStorage
        onMounted(() => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    JSON.parse(storedUser); // Verifica que el JSON esté bien formado
                    isLoggedIn.value = true;
                } catch (error) {
                    // Si hay error, se elimina el dato corrupto y se redirige al inicio
                    console.error("Error al parsear el objeto de usuario:", error);
                    sessionStorage.removeItem("user");
                    isLoggedIn.value = false;
                    router.push("/");
                }
            } else {
                isLoggedIn.value = false;
            }
        });

        return {
            isLoggedIn,
            redirectToLogin,
        };
    },
};
</script>

<template>
    <!-- Si el usuario está logueado, muestra el contenido hijo (slot) -->
    <div v-if="isLoggedIn">
        <slot />
    </div>

    <!-- Si NO está logueado, muestra un mensaje y un botón para ir al login -->
    <div v-else class="advert flex-center">
        <div class="modal flex-column glass-effect">
            <h1>Por favor, inicia sesión para ver esta página.</h1>
            <PrimaryButton @click="redirectToLogin" label="Iniciar sesión" />
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.advert {
    background-color: map-get($colores, "gris_claro");
    background-image: url("/img/utils/haircut.jpg");
    background-size: cover;
    background-position: center;

    .modal {

        border-radius: 10px;

        padding: 1rem;

        align-items: center;
        gap: 1rem;

        h1 {
            color: map-get($colores, "gris_claro");
        }
    }
}
</style>
