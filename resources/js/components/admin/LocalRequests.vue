<script>
import { ref, onMounted, watch, computed } from "vue"; // Añadimos computed para manejar la caché
import axios from "axios";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "LocalRequests",
    components: {
        ModalConfirm,
    },
    setup() {
        const solicitudes = ref([]);
        const estadoSeleccionado = ref("PENDIENTE");
        const estados = ref(["PENDIENTE", "APROBADA", "RECHAZADA"]);

        // Estado para el modal
        const showModal = ref(false);
        const modalMessage = ref("");
        const modalAction = ref(null); // Guardará la acción a realizar (aprobar, denegar, etc.)
        const modalSolicitudId = ref(null); // Guardará el ID de la solicitud

        // Caché para los nombres de las localidades
        const localidadNames = ref({});

        const cargarSolicitudes = async (estado) => {
            try {
                const response = await axios.get(`/api/admin/requests?estado=${estado}`, {
                    headers: { Authorization: `Bearer ${sessionStorage.getItem('token')}` }
                });
                solicitudes.value = response.data;

                // Cargar nombres de localidades para las solicitudes obtenidas
                await cargarNombresLocalidades();
            } catch (error) {
                console.error("Error fetching local requests:", error);
                solicitudes.value = [];
                // Mostrar mensaje de error con el modal
                modalMessage.value = "Error al cargar las solicitudes. Por favor, intenta de nuevo más tarde.";
                modalAction.value = null; // No hay acción, solo mensaje
                showModal.value = true;
            }
        };

        // Función para cargar los nombres de las localidades
        const cargarNombresLocalidades = async () => {
            const uniqueLocalityIds = [...new Set(solicitudes.value.map(s => s.localidad))];
            for (const id of uniqueLocalityIds) {
                if (!localidadNames.value[id]) {
                    try {
                        const response = await axios.get(`/api/localities/${id}/name`);
                        localidadNames.value[id] = response.data;
                    } catch (error) {
                        console.error(`Error fetching locality name for ID ${id}:`, error);
                        localidadNames.value[id] = "Desconocido"; // Valor por defecto en caso de error
                    }
                }
            }
        };

        onMounted(() => {
            cargarSolicitudes(estadoSeleccionado.value);
        });

        watch(estadoSeleccionado, (nuevoEstado) => {
            cargarSolicitudes(nuevoEstado);
        });

        const abrirModal = (message, action, solicitudId) => {
            modalMessage.value = message;
            modalAction.value = action;
            modalSolicitudId.value = solicitudId;
            showModal.value = true;
        };

        const ejecutarAccion = async () => {
            const action = modalAction.value;
            const solicitudId = modalSolicitudId.value;
            const estadoMap = {
                aprobar: 'APROBADA',
                denegar: 'RECHAZADA',
                devolverAPendiente: 'PENDIENTE',
            };
            const mensajesExito = {
                aprobar: "Solicitud APROBADA exitosamente.",
                denegar: "Solicitud DENEGADA exitosamente.",
                devolverAPendiente: "Solicitud devuelta a PENDIENTE exitosamente.",
            };

            try {
                await axios.post(`/api/admin/requests/${solicitudId}/cambiarEstado`, { estado: estadoMap[action] });
                solicitudes.value = solicitudes.value.filter(s => s.id !== solicitudId);
                abrirModal(mensajesExito[action], null, null); // Mostrar mensaje de éxito
            } catch (error) {
                console.error(`Error al ${action}:`, error);
                abrirModal(`Error al ${action === 'aprobar' ? 'aprobar' : action === 'denegar' ? 'denegar' : 'devolver a pendiente'} la solicitud. Por favor, intenta de nuevo.`, null, null);
            }
        };

        const aprobarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de aprobar esta solicitud?", "aprobar", solicitudId);
        };

        const denegarSolicitud = (solicitudId) => {
            abrirModal("¿Estás seguro de denegar esta solicitud?", "denegar", solicitudId);
        };

        const devolverAPendiente = (solicitudId) => {
            abrirModal("¿Estás seguro de devolver esta solicitud a pendiente?", "devolverAPendiente", solicitudId);
        };

        return {
            solicitudes,
            estadoSeleccionado,
            estados,
            showModal,
            modalMessage,
            modalAction,
            aprobarSolicitud,
            denegarSolicitud,
            devolverAPendiente,
            ejecutarAccion,
            localidadNames, // Devolver la caché de nombres de localidades
        };
    },
};
</script>

<template>
    <div>
        <div class="filter-section">
            <label for="estado-select">Filtrar por estado:</label>
            <select id="estado-select" v-model="estadoSeleccionado">
                <option v-for="estado in estados" :key="estado" :value="estado">
                    {{ estado }}
                </option>
            </select>
        </div>
        <h2>Solicitudes de locales</h2>
        <div v-if="solicitudes.length === 0" class="no-data-message">
            No hay solicitudes con el estado "{{ estadoSeleccionado }}".
        </div>
        <table v-else class="requests-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Fecha de solicitud</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Localidad</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="solicitud in solicitudes" :key="solicitud.id">
                    <td>{{ solicitud.id }}</td>
                    <td>{{ solicitud.estado }}</td>
                    <td>{{ solicitud.fecha }}</td>
                    <td>{{ solicitud.nombre }}</td>
                    <td>{{ solicitud.descripcion }}</td>
                    <td>{{ solicitud.direccion }}</td>
                    <td>{{ localidadNames[solicitud.localidad] || "Cargando..." }}</td>
                    <td>{{ solicitud.email }}</td>
                    <td>{{ solicitud.telefono }}</td>
                    <td>{{ solicitud.tipo }}</td>
                    <td>{{ solicitud.user_id }}</td>
                    <td>
                        <div v-if="solicitud.estado === 'PENDIENTE'" class="action-buttons flex-center">
                            <button class="approve-btn" @click="aprobarSolicitud(solicitud.id)">Aprobar</button>
                            <button class="deny-btn" @click="denegarSolicitud(solicitud.id)">Denegar</button>
                        </div>
                        <div v-else-if="solicitud.estado === 'APROBADA' || solicitud.estado === 'RECHAZADA'"
                            class="action-buttons flex-center">
                            <button class="revert-btn" @click="devolverAPendiente(solicitud.id)">Devolver a
                                pendiente</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal reutilizable -->
        <ModalConfirm v-model:show="showModal" :message="modalMessage" :show-cancel="modalAction !== null"
            @confirm="modalAction ? ejecutarAccion() : null" @cancel="modalAction = null" />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

* {
    @include fuente("parrafo");
}

.filter-section {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;

    &:focus {
        outline: none;
        border-color: map-get($colores, "naranja");
    }
}

.requests-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    padding: 8px;
    text-align: center;
}

th {
    background-color: map-get($colores, "azul_oscuro");
    color: map-get($colores, "blanco");
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.action-buttons {
    gap: 10px;
}

.approve-btn,
.deny-btn,
.revert-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.approve-btn {
    background-color: #4CAF50;
    color: white;
}

.approve-btn:hover {
    background-color: #45a049;
}

.deny-btn {
    background-color: #f44336;
    color: white;
}

.deny-btn:hover {
    background-color: #da190b;
}

.revert-btn {
    background-color: #ff9800;
    color: white;
}

.revert-btn:hover {
    background-color: #f57c00;
}

.no-data-message {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-style: italic;
}
</style>