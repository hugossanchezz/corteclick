<script>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import RequireAuth from "@/js/components/auth/RequireAuth.vue";

export default {
    name: "ProfileView",
    components: { RequireAuth },
    setup() {
        const user = ref(null);
        const router = useRouter();
        const route = useRoute(); // Get the current route

        onMounted(() => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);
                } catch (error) {
                    console.error(
                        "Error al parsear el objeto de usuario:",
                        error
                    );
                    sessionStorage.removeItem("user");
                    router.push("/");
                }
            }
        });

        // Function to check if a route is active
        const isActiveRoute = (routeName) => {
            return route.path.endsWith(routeName);
        };

        return {
            user,
            router,
            isActiveRoute,
        };
    },
};
</script>

<template>
    <RequireAuth>
        <div class="profile flex">
            <aside class="flex-column">
                <router-link :to="'/user'"
                    :class="{ 'route--active': isActiveRoute('/user') }">
                    Mis citas
                </router-link>

                <!-- Para usuarios con rol de empresario o admin -->
                <router-link :to="'/user/my-locals'"
                    :class="{ 'route--active': isActiveRoute('/user/my-locals') }">
                    Mis locales
                </router-link>

                <!-- Para usuarios con rol de admin -->
                <router-link v-if="user.rol_id == 1 || user.rol_id == 2" :to="'/admin/requests'"
                    :class="{ 'route--active': isActiveRoute('/admin/requests') }">
                    Solicitudes de locales
                </router-link>
                <router-link v-if="user.rol_id == 1 || user.rol_id == 2" :to="'/admin/dashboard'"
                    :class="{ 'route--active': isActiveRoute('/admin/dashboard') }">
                    Control de usuarios
                </router-link>

                <hr>
                <router-link :to="'/user/settings'" class="flex aside__config"
                    :class="{ 'route--active': isActiveRoute('/user/settings') }">
                    Configuración <img src="/img/utils/settings.svg" alt="">
                </router-link>
                
            </aside>
            <section class="profile__main">
                <router-view />
            </section>
        </div>
    </RequireAuth>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

hr {
    background-color: map-get($colores, 'gris_claro');
    height: 2px;
    border: none;
    margin: 1rem 0;
}

.profile {
    height: 100%;

    aside {
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        height: 100%;
        width: fit-content;
        padding: 3rem 2rem;

        gap: 5px;

        a {
            color: map-get($colores, "naranja");
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            border: 2px solid transparent;

            align-items: center;
            gap: 10px;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

            &:hover {
                border: 2px solid map-get($colores, "gris_claro");
            }
        }

        .route--active {
            border: 2px solid map-get($colores, "gris_claro");
        }

        .aside__config {
            color: map-get($colores, 'azul_oscuro');
            align-items: center;
            display: flex;

            img {
                transition: transform 0.85s ease-in-out;
            }

            &:hover img {
                transform: rotate(180deg);
            }
        }
    }

    .profile__main {
        width: 100%;
        padding: 3rem 2rem;
    }
}
</style>