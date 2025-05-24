<script>
import { computed, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { isAuthenticated } from "@/js/auth/eventBus";
import DangerButton from "@/js/components/actions/DangerButton.vue";

export default {
    name: "Configuracion",
    components: { DangerButton },
    setup() {
        const user = ref(null); // Información del usuario logueado
        const nombreLocalidad = ref("Cargando..."); // Nombre de la localidad del usuario
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
                const token = sessionStorage.getItem("token");

                const response = await axios.post(
                    "/api/logout",
                    {},
                    {
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            Authorization: `Bearer ${token}`,
                        },
                    }
                );

                isAuthenticated.value = false;
                sessionStorage.removeItem("token");
                sessionStorage.removeItem("user");
                router.push("/");
            } catch (error) {
                console.error("Error al cerrar sesión:", error);
                if (error.response && error.response.status === 401) {
                    console.error("Token CSRF inválido o ausente.");
                }
            }
        };

        /**
         * Al montar el componente, intenta recuperar el usuario del sessionStorage.
         * Si existe, carga también el nombre de su localidad a través de la API.
         */
        onMounted(async () => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);

                    const res = await fetch(`/api/localities/${user.value.localidad}/name`);
                    if (res.ok) {
                        nombreLocalidad.value = await res.text();
                    } else {
                        nombreLocalidad.value = "Desconocido";
                    }
                } catch (error) {
                    console.error("Error al cargar usuario o localidad:", error);
                    sessionStorage.removeItem("user");
                    router.push("/");
                }
            }
        });

        return {
            user,
            nombreLocalidad,
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
            <p><strong>Localidad:</strong> {{ nombreLocalidad }}</p>
            <p><strong>Rol:</strong> {{ user.rol_id }}</p>
        </div>
        <div v-else>
            <p>No hay información de usuario disponible.</p>
        </div>

        <hr>
        <DangerButton @click="handleLogout" label="Cerrar sesión" />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

hr {
    background-color: map-get($colores, 'gris_claro');
    width: 50%;
    height: 2px;
    border: none;
    margin: 2rem 0;
}
</style>