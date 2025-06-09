<script>
import { ref, onMounted, watch } from "vue";
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

        const showModal = ref(false);
        const modalMessage = ref("");
        const modalAction = ref(null);
        const modalSolicitudId = ref(null);

        const localidadNames = ref({});

        const cargarSolicitudes = async (estado) => {
            try {
                const response = await axios.get(`/api/admin/requests?estado=${estado}`, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });
                solicitudes.value = response.data;
                await cargarNombresLocalidades();
            } catch (error) {
                console.error("Error fetching local requests:", error);
                solicitudes.value = [];
                modalMessage.value = "Error al cargar las solicitudes. Por favor, intenta de nuevo más tarde.";
                modalAction.value = null;
                showModal.value = true;
            }
        };

        const cargarNombresLocalidades = async () => {
            const uniqueLocalityIds = [...new Set(solicitudes.value.map(s => s.localidad))];
            for (const id of uniqueLocalityIds) {
                if (!localidadNames.value[id]) {
                    try {
                        const response = await axios.get(`/api/localities/${id}/name`, {
                            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                        });
                        localidadNames.value[id] = response.data;
                    } catch (error) {
                        console.error(`Error fetching locality name for ID ${id}:`, error);
                        localidadNames.value[id] = "Desconocido";
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
                aprobar: "Solicitud APROBADA exitosamente y peluquería creada.",
                denegar: "Solicitud DENEGADA exitosamente.",
                devolverAPendiente: "Solicitud devuelta a PENDIENTE exitosamente y peluquería eliminada si existía.",
            };

            try {
                const solicitud = solicitudes.value.find(s => s.id === solicitudId);
                if (!solicitud) {
                    throw new Error("Solicitud no encontrada");
                }

                if (action === 'aprobar') {
                    // let normalizedTipo = solicitud.tipo;
                    // if (normalizedTipo) {
                    //     normalizedTipo = normalizedTipo.toLowerCase();
                    //     if (!['peluqueria', 'barberia', 'estetica'].includes(normalizedTipo)) {
                    //         throw new Error("El tipo de solicitud no es válido. Debe ser 'peluqueria', 'barberia' o 'estetica'.");
                    //     }
                    // } else {
                    //     throw new Error("El campo 'tipo' está vacío o no definido.");
                    // }

                    try {
                        const response = await axios.post('/api/new-local', {
                            nombre: solicitud.nombre,
                            descripcion: solicitud.descripcion,
                            direccion: solicitud.direccion,
                            localidad: solicitud.localidad,
                            email: solicitud.email,
                            telefono: solicitud.telefono,
                            tipo: solicitud.tipo,
                            user_id: solicitud.user_id,
                        });
                    } catch (newLocalError) {
                        console.error("Error al crear el nuevo local:", newLocalError.response ? newLocalError.response.data : newLocalError.message);
                        throw newLocalError;
                    }
                }

                // Si la acción es devolver a pendiente y el estado actual es APROBADA, eliminar la peluquería por email
                if (action === 'devolverAPendiente' && solicitud.estado === 'APROBADA') {
                    try {
                        const response = await axios.delete(`/api/delete-local/${encodeURIComponent(solicitud.email)}`);
                    } catch (deleteError) {
                        // Si la peluquería no existe (404), continuamos con el cambio de estado
                        if (deleteError.response && deleteError.response.status !== 404) {
                            console.error("Error al eliminar la peluquería:", deleteError.response ? deleteError.response.data : deleteError.message);
                            throw deleteError;
                        } else {
                            console.warn("No se encontró una peluquería para eliminar con el email:", solicitud.email);
                        }
                    }
                }

                // Cambiar el estado de la solicitud
                await axios.post(`/api/admin/requests/${solicitudId}/cambiarEstado`, { estado: estadoMap[action] }, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
                });

                // Filtrar la solicitud de la lista
                solicitudes.value = solicitudes.value.filter(s => s.id !== solicitudId);
                abrirModal(mensajesExito[action], null, null);
            } catch (error) {
                console.error(`Error al ${action}:`, error.response ? error.response.data : error.message);
                abrirModal(
                    `Error al ${action === 'aprobar' ? 'aprobar' : action === 'denegar' ? 'denegar' : 'devolver a pendiente'} la solicitud. ` +
                    (error.message || (error.response && error.response.data && error.response.data.error ? error.response.data.error : 'Por favor, intenta de nuevo.')),
                    null,
                    null
                );
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
            localidadNames,
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
                    <th>Estado</th>
                    <th>Fecha de solicitud</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Dirección</th>
                    <th>Localidad</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Tipo</th>
                    <th>ID Usuario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="solicitud in solicitudes" :key="solicitud.id">
                    <td><strong>{{ solicitud.estado }}</strong></td>
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
                            <button class="btn btn-confirm" @click="aprobarSolicitud(solicitud.id)">Aprobar</button>
                            <button class="btn btn-cancel" @click="denegarSolicitud(solicitud.id)">Denegar</button>
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
    border: 1px solid map-get($colores, "gris_claro");
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

.revert-btn {
    background-color: map-get($colores, 'naranja');
    color: map-get($colores, 'blanco');
}

.revert-btn:hover {
    background-color: map-get($colores, 'naranja_oscuro');
}

.no-data-message {
    text-align: center;
    margin-top: 20px;
    color: map-get($colores, 'gris_oscuro');
    font-style: italic;
}
</style>