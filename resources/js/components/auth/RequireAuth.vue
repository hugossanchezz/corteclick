<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";

export default {
    name: "RequireAuth",
    components: { PrimaryButton },
    setup() {
        const isLoggedIn = ref(false);
        const router = useRouter();

        const redirectToLogin = () => {
            router.push("/auth");
        };

        onMounted(() => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    JSON.parse(storedUser);
                    isLoggedIn.value = true;
                } catch (error) {
                    console.error(
                        "Error al parsear el objeto de usuario:",
                        error
                    );
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
    <div v-if="isLoggedIn">
        <slot />
    </div>
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
