<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import RequireAuth from "@/js/components/auth/RequireAuth.vue";

export default {
    name: "ProfileView",
    components: { RequireAuth },
    setup() {
        const user = ref(null);
        const router = useRouter();

        onMounted(() => {
            const storedUser = localStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);
                } catch (error) {
                    console.error(
                        "Error al parsear el objeto de usuario:",
                        error
                    );
                    localStorage.removeItem("user");
                    router.push("/");
                }
            }
        });

        return {
            router,
        };
    },
};
</script>

<template>
    <RequireAuth>
        <div class="profile flex">
            <section class="profile__aside flex-column">
                <router-link to="/user"> Perfil </router-link>
                <router-link to="/user/settings"> Configuración </router-link>
            </section>
            <section class="profile__main">
                <router-view />
            </section>
        </div>
    </RequireAuth>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.profile {
    height: 100%;

    .profile__aside {
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        height: 100%;
        width: fit-content;
        padding: 3rem 2rem;

        gap: 5px;

        a {
            color: map-get($colores, "naranja");
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;

            &:hover {
                background-color: map-get($colores, "gris_claro");
            }
        }
    }

    .profile__main {
        width: 100%;

        padding: 3rem 2rem;
    }
}
</style>
