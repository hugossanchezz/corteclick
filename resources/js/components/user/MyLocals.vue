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

        /**
         * Carga el usuario desde el almacenamiento de sesi n en el navegador.
         * Si no hay un usuario almacenado, redirige al usuario a la p gina de inicio.
         * Si hay un error al parsear el objeto de usuario, muestra un mensaje de error
         * en la consola y redirige al usuario a la p gina de inicio.
         */
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

        /**
         * Carga los servicios disponibles para un local específico utilizando su ID.
         * Realiza una solicitud GET a la API y devuelve los datos de los servicios,
         * que incluyen el ID, nombre, precio y duración de cada servicio.
         * En caso de error, se registra en la consola y retorna un arreglo vacío.
         * 
         * @param {number} id_peluqueria - ID del local para el cual cargar los servicios.
         * @returns {Array} - Lista de servicios con sus detalles.
         */

        const cargarServiciosLocal = async (id_peluqueria) => {
            try {
                const response = await axios.get(`/api/locals/${id_peluqueria}/services`);
                return response.data; // contiene: id, nombre, precio, duracion
            } catch (error) {
                console.error(`Error al cargar servicios del local ${id_peluqueria}:`, error);
                return [];
            }
        };

        /**
         * Carga los servicios disponibles en la base de datos.
         * Realiza una solicitud GET a la API y devuelve los datos de los servicios,
         * que incluyen el ID, nombre, precio y duraci n de cada servicio.
         * En caso de error, se registra en la consola y no se hace nada.
         * 
         * @returns {Array} - Lista de servicios con sus detalles.
         */
        const cargarServiciosDisponibles = async () => {
            try {
                const response = await axios.get("/api/services");
                serviciosDisponibles.value = response.data;
            } catch (error) {
                console.error("❌ Error al cargar servicios disponibles:", error);
            }
        };

        /**
         * Filtra los servicios disponibles para un local específico. Devuelve una lista
         * de servicios que no est n asignados al local.
         * 
         * @param {number} id_peluqueria - ID del local para el cual filtrar los servicios.
         * @returns {Array} - Lista de servicios que pueden ser agregados al local.
         */
        const serviciosDisponiblesFiltrados = (id_peluqueria) => {
            const local = peluquerias.value.find(l => l.id === id_peluqueria);
            if (!local) return [];

            const idsActuales = local.servicios.map(s => s.id); // id del servicio ya asignado

            return serviciosDisponibles.value.filter(serv => !idsActuales.includes(serv.id));
        };

        /**
         * Carga las peluquerías asociadas al usuario actual.
         * Realiza una solicitud GET a la API para obtener la lista de locales
         * y por cada local, carga los servicios disponibles para ese local.
         * En caso de error, se registra en la consola y no se hace nada.
         */
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

        /**
         * Crea un nuevo servicio para un local específico.
         * Realiza una solicitud POST a la API para crear el servicio
         * y en caso de éxito, recarga los servicios del local y resetea
         * el formulario.
         * En caso de error, se registra en la consola y no se hace nada.
         * 
         * @param {number} id_peluqueria - ID del local para el cual crear el servicio.
         */
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

        /**
         * Alterna la visibilidad del formulario para crear un servicio para un local
         * específico. Si se está cerrando, limpia los datos del formulario. Si se
         * está abriendo, inicializa el formulario con los campos vacíos.
         * 
         * @param {number} id - ID del local para el cual se va a crear el servicio.
         */
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

        const eliminarServicio = async (id_peluqueria, id_servicio) => {
            try {
                await axios.delete(`/api/local/delete-service/${id_peluqueria}/${id_servicio}`);
                // Reload servicios del local
                const nuevosServicios = await cargarServiciosLocal(id_peluqueria);
                const local = peluquerias.value.find((l) => l.id === id_peluqueria);
                if (local) local.servicios = nuevosServicios;
            } catch (error) {
                console.error("❌ Error al eliminar servicio:", error);
            }
        }

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
            eliminarServicio,
        };
    },
};
</script>

<template>
    <div class="mis_locales_container">
        <h1>Mis Locales</h1>

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

                        <table class="tabla_servicios" v-if="local.servicios.length > 0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Precio (€)</th>
                                    <th>Duración (min)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(servicio, idx) in local.servicios" :key="idx">
                                    <td>{{ servicio.nombre }}</td>
                                    <td>{{ servicio.precio }}</td>
                                    <td>{{ servicio.duracion }}</td>
                                    <td>
                                        <button class="btn btn-cancel" @click="eliminarServicio(local.id, servicio.id)">
                                            <img src="/img/utils/delete_white.svg" alt="Eliminar servicio">
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p v-else>No hay servicios disponibles.</p>

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

    .no_locales {
        text-align: center;
        font-style: italic;
        margin-top: 2rem;
    }

    .tarjetas_container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 460px));
        gap: 1.5rem;
        margin-top: 1rem;

        .tarjeta_local {
            min-height: 400px;
            // max-width: 50%;
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

                    .tabla_servicios {
                        border: 1px solid map-get($colores, "gris_claro");
                        border-radius: 5px;
                        margin-top: 5px;

                        th {
                            background-color: map-get($colores, 'gris_claro');
                            color: map-get($colores, 'gris_oscuro');
                        }
                    }
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

                .btn {
                    align-self: flex-end;
                    margin: 0 auto;
                }

            }
        }
    }
}
</style>
