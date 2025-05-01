<script>
import { computed, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { isAuthenticated } from "@/js/auth/eventBus";
import DangerButton from "@/js/components/actions/DangerButton.vue";

export default {
    name: "Profile",
    components: { DangerButton },
    setup() {
        const user = ref(null);
        const router = useRouter();

        /**
         * Cierra la sesión del usuario y redirige a la página de inicio.
         * Limpia los tokens de autenticación y csrf en el almacenamiento local.
         * Si se produce un error, muestra un mensaje de depuración en la consola.
         */
        const handleLogout = async () => {
            try {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");
                const token = localStorage.getItem("token"); // Recuperamos el token de autenticación

                const response = await axios.post(
                    "/api/logout",
                    {},
                    {
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            Authorization: `Bearer ${token}`, // Añadimos también el token de autenticación
                        },
                    }
                );

                console.log("Sesión cerrada:", response.data);

                // Cambiar varible global de estar logueado a false
                isAuthenticated.value = false;

                // Eliminar tokens
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                router.push("/");
            } catch (error) {
                console.error("Error al cerrar sesión:", error);
                if (error.response && error.response.status === 401) {
                    console.error(
                        "Error de autenticación: El token CSRF es inválido o no se ha proporcionado."
                    );
                }
            }
        };

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
            user,
            handleLogout,
            router,
        };
    },
};
</script>

<template>
    <div>
        <div v-if="user">
            <h1>Bienvenido {{ user.name }}</h1>
            <p><strong>Email:</strong> {{ user.email }}</p>
            <p><strong>Apellidos:</strong> {{ user.apellidos }}</p>
            <p><strong>Teléfono:</strong> {{ user.telefono }}</p>
            <p><strong>Localidad:</strong> {{ user.localidad }}</p>
        </div>
        <div v-else>
            <p>No hay información de usuario disponible.</p>
        </div>
        <!-- <button @click="handleLogout">Cerrar sesión</button> -->
        <DangerButton @click="handleLogout" label="Cerrar sesión" />
    </div>
</template>
