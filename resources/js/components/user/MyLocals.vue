<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
    name: "MyLocals",

    setup() {
        const router = useRouter();
        const user = ref(null);
        const peluquerias = ref([]);

        const cargarUsuario = () => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);
                    console.log(user.value.id);
                } catch (error) {
                    console.error("Error al parsear el objeto de usuario:", error);
                    sessionStorage.removeItem("user");
                    router.push("/");
                }
            } else {
                router.push("/");
            }
        };

        const cargarPeluquerias = async () => {
            try {
                const response = await axios.get(`/api/locals/user/${user.value.id}`);
                peluquerias.value = response.data;
            } catch (error) {
                console.error("❌ Error al cargar las peluquerías:", error);
            }
        };

        onMounted(async () => {
            cargarUsuario();
            if (user.value) {
                await cargarPeluquerias();
            }
        });

        return {
            user,
            peluquerias,
        };
    },
};
</script>

<template>
    <div class="mis_locales_container">
        <h2>Mis Locales</h2>

        <div v-if="peluquerias.length === 0" class="no_locales">
            No tienes locales registrados actualmente.
        </div>

        <div v-else class="tarjetas_container flex">
            <div v-for="local in peluquerias" :key="local.id" class="tarjeta_local flex">
                <div class="imagen_local">
                    <img v-if="local.imagen" :src="`data:image/jpeg;base64,${local.imagen}`" alt="Imagen del local" />
                    <img v-else src="/img/utils/corteclick.png" alt="Imagen por defecto" />
                </div>
                <div class="info_local">
                    <h3>{{ local.nombre }}</h3>
                    <p>{{ local.direccion }}</p>
                </div>
                <div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.mis_locales_container {
    padding: 1rem;

    .sin_datos_mensaje {
        text-align: center;
        font-style: italic;
        margin-top: 2rem;
    }

    .tarjetas_container {
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1rem;

        .tarjeta_local {
            flex: 0 0 calc(50% - 0.75rem); // 50% menos la mitad del gap
            background-color: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            .imagen_local {
                flex-shrink: 0;
                width: 120px;
                height: 120px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;

                img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            }

            .info_local {
                padding: 0.75rem 1rem;
                display: flex;
                flex-direction: column;
                justify-content: center;

                h3 {
                    margin: 0;
                    font-size: 1.2rem;
                }

                p {
                    margin: 0.25rem 0 0;
                    color: map-get($colores, 'gris_oscuro');
                }
            }
        }
    }
}

@media (max-width: 600px) {
    .tarjetas_container {
        .tarjeta_local {
            flex: 0 0 100%;
        }
    }
}
</style>
