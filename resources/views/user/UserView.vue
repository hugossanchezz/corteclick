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
        const menuAbierto = ref(false);
        const toggleMenu = () => {
            menuAbierto.value = !menuAbierto.value;
        };
        const cerrarMenu = () => {
            menuAbierto.value = false;
        };
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
            menuAbierto,
            toggleMenu,
            cerrarMenu,
        };
    },
};
</script>

<template>
    <RequireAuth>
        <section class="profile flex">

            <!-- Menú lateral -->
            <aside class="flex-column" :class="{ 'aside--mobile-open': menuAbierto }">
                <!-- Botón para abrir el menú visible en móviles -->
                <img class="aside_btn" @click="toggleMenu"
                    :src="menuAbierto ? '/img/utils/arrow_back.svg' : '/img/utils/arrow_forward.svg'"
                    :alt="menuAbierto ? 'Cerrar menú' : 'Abrir menú'" />


                <router-link to="/user" :class="{ 'route--active': isActiveRoute('/user') }">Mis citas</router-link>
                <router-link to="/user/my-locals" :class="{ 'route--active': isActiveRoute('/user/my-locals') }">Mis
                    locales</router-link>
                <router-link v-if="user.rol_id == 1 || user.rol_id == 2" to="/admin/requests"
                    :class="{ 'route--active': isActiveRoute('/admin/requests') }">Solicitudes de locales</router-link>

                <hr>
                <router-link to="/user/settings" class="flex aside__config"
                    :class="{ 'route--active': isActiveRoute('/user/settings') }">
                    Configuración <img src="/img/utils/settings.svg" alt="">
                </router-link>
            </aside>

            <!-- Contenido -->
            <main class="profile__main">
                <router-view />
            </main>
        </section>
    </RequireAuth>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.profile {
    height: 100%;
    display: flex;

    aside {
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        height: 100%;
        width: fit-content;
        padding: 3rem 2rem;
        gap: 5px;
        background-color: white;

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

/* ==== MODO MÓVIL ==== */
@media (max-width: 1024px) {
    .profile {
        position: relative;
        flex-direction: column;

        aside {
            position: absolute;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            padding: 3rem 1rem;
            transition: left 0.3s ease-in-out;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);

            .aside_btn {
                background-color: white;
                position: absolute;
                top: 1rem;
                right: -40px;
                width: 40px;
                height: 40px;
                padding: 0.5rem;
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                border: 2px solid map-get($colores, "gris_claro");
                cursor: pointer;
            }
        }

        .aside--mobile-open {
            left: 0;
        }
    }
}

@media (min-width: 1025px) {
    .aside_btn {
        display: none;
    }
}
</style>