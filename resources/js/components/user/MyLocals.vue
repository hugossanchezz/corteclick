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

        // Crear nuevo servicio
        const serviciosDisponibles = ref([]);
        const formularioVisible = ref({}); // objeto
        const nuevoServicio = ref({});

        const cargarUsuario = () => {
            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);
                } catch (error) {
                    console.error("Error al parsear el objeto de usuario:", error);
                    sessionStorage.removeItem("user");
                    router.push("/");
                }
            } else {
                router.push("/");
            }
        };

        const cargarServiciosLocal = async (id_peluqueria) => {
            try {
                const response = await axios.get(`/api/locals/${id_peluqueria}/services`);
                return response.data; // contiene: id, nombre, precio, duracion
            } catch (error) {
                console.error(`Error al cargar servicios del local ${id_peluqueria}:`, error);
                return [];
            }
        };

        const cargarServiciosDisponibles = async () => {
            try {
                const response = await axios.get("/api/services");
                serviciosDisponibles.value = response.data;
            } catch (error) {
                console.error("❌ Error al cargar servicios disponibles:", error);
            }
        };

        const serviciosDisponiblesFiltrados = (id_peluqueria) => {
            const local = peluquerias.value.find(l => l.id === id_peluqueria);
            if (!local) return [];

            const idsActuales = local.servicios.map(s => s.id); // id del servicio ya asignado

            return serviciosDisponibles.value.filter(serv => !idsActuales.includes(serv.id));
        };

        const cargarPeluquerias = async () => {
            try {
                const response = await axios.get(`/api/locals/user/${user.value.id}`);
                const locales = response.data;

                for (const local of locales) {
                    local.servicios = await cargarServiciosLocal(local.id);
                }

                peluquerias.value = locales;
            } catch (error) {
                console.error("❌ Error al cargar las peluquerías:", error);
            }
        };

        const crearServicio = async (id_peluqueria) => {
            const datos = nuevoServicio.value[id_peluqueria];

            if (!datos.id_servicio || datos.precio <= 0 || !datos.duracion) {
                alert("Completa todos los campos correctamente.");
                return;
            }

            try {
                await axios.post("/api/local/new-service", {
                    id_peluqueria,
                    id_servicio: datos.id_servicio,
                    precio: datos.precio,
                    duracion: datos.duracion,
                });

                // Reload servicios del local
                const nuevosServicios = await cargarServiciosLocal(id_peluqueria);
                const local = peluquerias.value.find((l) => l.id === id_peluqueria);
                if (local) local.servicios = nuevosServicios;

                // Reset
                toggleFormulario(id_peluqueria);
            } catch (error) {
                console.error("❌ Error al crear servicio:", error);
            }
        };

        const toggleFormulario = (id) => {
            const visible = formularioVisible.value[id];
            formularioVisible.value[id] = !visible;

            if (visible) {
                // Si se está cerrando, limpiar los datos
                nuevoServicio.value[id] = {
                    id_servicio: "",
                    precio: "",
                    duracion: 30,
                };
            } else {
                // Si se está abriendo, inicializar si no existe
                if (!nuevoServicio.value[id]) {
                    nuevoServicio.value[id] = {
                        id_servicio: "",
                        precio: "",
                        duracion: 30,
                    };
                }
            }
        };

        onMounted(async () => {
            cargarUsuario();
            if (user.value) {
                await cargarPeluquerias();
                await cargarServiciosDisponibles();
            }
        });

        return {
            user,
            peluquerias,
            serviciosDisponibles,
            serviciosDisponiblesFiltrados,
            formularioVisible,
            nuevoServicio,
            toggleFormulario,
            crearServicio,
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

        <div v-else class="tarjetas_container">
            <div v-for="local in peluquerias" :key="local.id" class="tarjeta_local flex-column">
                <div class="imagen_local">
                    <img v-if="local.imagen" :src="`data:image/jpeg;base64,${local.imagen}`" alt="Imagen del local" />
                    <img v-else src="/img/utils/corteclick.png" alt="Imagen por defecto" />
                </div>
                <div class="info_local flex-column">
                    <div>
                        <h3>{{ local.nombre }}</h3>
                        <p>{{ local.direccion }}</p>
                    </div>
                    <br>
                    <div class="servicios_disponibles">
                        <p><strong>Servicios disponibles:</strong></p>
                        <ul class="lista_servicios">
                            <li v-if="local.servicios.length === 0">No hay servicios disponibles.</li>
                            <li v-else v-for="(servicio, idx) in local.servicios" :key="idx">
                                {{ servicio.nombre }} --- {{ servicio.precio }} € --- {{ servicio.duracion }} min
                            </li>
                        </ul>
                        <br>

                    </div>
                    <button v-if="!formularioVisible[local.id]" class="btn btn-edit"
                        @click="toggleFormulario(local.id)">
                        <img src="/img/utils/plus.svg" alt="Añadir servicio"> Añadir nuevo servicio
                    </button>

                    <div v-if="formularioVisible[local.id]" class="formulario_servicio">
                        <br>
                        <div class="inputForm">
                            <select v-model="nuevoServicio[local.id].id_servicio">
                                <option value="" disabled selected>Seleccione un servicio</option>
                                <option v-for="serv in serviciosDisponiblesFiltrados(local.id)" :key="serv.id"
                                    :value="serv.id">
                                    {{ serv.nombre }}
                                </option>
                            </select>
                        </div>
                        <br>
                        <div class="inputForm">
                            <input type="number" min="1" step="0.01" placeholder="Precio (€)"
                                v-model="nuevoServicio[local.id].precio" />
                        </div>
                        <br>
                        <div class="inputForm">
                            <select v-model="nuevoServicio[local.id].duracion">
                                <option v-for="dur in [30, 60, 90, 120, 150, 180]" :key="dur" :value="dur">
                                    {{ dur }} minutos
                                </option>
                            </select>
                        </div>
                        <br>
                        <div class="flex">
                            <button class="btn btn-confirm" @click="crearServicio(local.id)">
                                <img src="/img/utils/tick.svg" alt="Guardar nuevo servicio">
                                Guardar
                            </button>
                            <button class="btn btn-cancel" @click="toggleFormulario(local.id)">
                                <img src="/img/utils/close.svg" alt="Cancelar nuevo servicio">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.mis_locales_container {
    padding: 1rem;

    .no_locales {
        text-align: center;
        font-style: italic;
        margin-top: 2rem;
    }

    .tarjetas_container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;

        .tarjeta_local {
            min-height: 400px;
            background-color: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            .imagen_local {
                width: 100%;
                flex-shrink: 0;
                height: 150px;
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
                height: 100%;
                width: 100%;
                padding: 0.75rem 1rem;
                justify-content: space-between;

                h3 {
                    margin: 0;
                    font-size: 1.2rem;
                }

                p {
                    margin: 0.25rem 0 0;
                    color: map-get($colores, 'gris_oscuro');
                }

                .lista_servicios {
                    margin-top: 0.5rem;
                    padding-left: 1rem;
                    list-style: disc;
                    font-size: 0.95rem;
                    color: map-get($colores, 'gris_oscuro');
                }

                .servicios_disponibles {
                    height: 100%;
                }

                .formulario_servicio {

                    .inputForm {
                        border: 1.5px solid map-get($colores, "gris_claro");
                        padding: 10px;
                        border-radius: 10px;
                        align-items: center;
                        transition: 0.2s ease-in-out;

                        &:focus-within {
                            border: 1.5px solid map-get($colores, "naranja");
                        }

                        input {
                            width: 100%;
                            margin-left: 10px;
                            border: none;
                            align-items: center;

                            &:focus {
                                outline: none;
                            }
                        }

                        select {
                            width: 100%;
                            height: 100%;
                            background-color: transparent;
                            border: 0;
                            cursor: pointer;

                            &:focus {
                                outline: none;
                            }
                        }

                        textarea {
                            width: 100%;
                            height: 100%;
                            background-color: transparent;
                            border: 0;
                            resize: none;
                            margin-left: 10px;

                            &:focus {
                                outline: none;
                            }
                        }
                    }

                    button {
                        margin: 0 auto;
                    }
                }

                .btn{
                    align-self: flex-end;
                    margin: 0 auto;
                }

            }
        }
    }
}

@media (max-width: 628px) {}
</style>
