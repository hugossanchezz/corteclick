<script>
import { computed, ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { isAuthenticated } from "@/js/auth/eventBus";
import DangerButton from "@/js/components/actions/DangerButton.vue";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "Configuracion",
    components: { DangerButton, ModalConfirm },
    setup() {
        const user = ref(null); // Información del usuario logueado
        const nombreLocalidad = ref("Cargando..."); // Nombre de la localidad del usuario
        const localidad = ref(""); // ID de la localidad seleccionada
        const localidades = ref([]); // Lista de localidades desde la API
        const router = useRouter();
        const isEditing = ref(false); // Controla el modo de edición
        const editedUser = ref({}); // Almacena los datos editados
        const errorMessage = ref(""); // Mensaje de error para el usuario
        const showModal = ref(false); // Controla la visibilidad del modal
        const modalMessage = ref(""); // Mensaje a mostrar en el modal
        const isSuccess = ref(false); // Indica si el modal es de éxito o error

        /**
         * Cierra la sesión del usuario y redirige a la página de inicio.
         * Limpia los tokens de autenticación y csrf en el almacenamiento local.
         */
        const handleLogout = async () => {
            try {
                const token = sessionStorage.getItem("token");

                const response = await axios.post(
                    "/api/logout",
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
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
                    console.error("Token inválido o ausente.");
                }
            }
        };

        /**
         * Al montar el componente, intenta recuperar el usuario del sessionStorage.
         * Si existe, carga también el nombre de su localidad a través de la API.
         */
        onMounted(async () => {
            cargarLocalidades();

            const storedUser = sessionStorage.getItem("user");
            if (storedUser) {
                try {
                    user.value = JSON.parse(storedUser);
                    editedUser.value = { ...user.value }; // Inicializa los datos editables
                    localidad.value = user.value.localidad || ""; // Inicializa el ID de la localidad

                    if (user.value.localidad) {
                        const res = await fetch(`/api/localities/${user.value.localidad}/name`);
                        if (res.ok) {
                            nombreLocalidad.value = await res.text();
                        } else {
                            nombreLocalidad.value = "Desconocido";
                        }
                    } else {
                        nombreLocalidad.value = "";
                    }
                } catch (error) {
                    console.error("Error al cargar usuario o localidad:", error);
                    sessionStorage.removeItem("user");
                    router.push("/");
                }
            }
        });

        /**
         * Activa el modo de edición y prepara los datos del usuario.
         */
        const startEditing = () => {
            isEditing.value = true;
            errorMessage.value = ""; // Limpia cualquier mensaje de error previo
        };

        /**
         * Cancela la edición y restaura los datos originales.
         */
        const cancelEditing = () => {
            isEditing.value = false;
            editedUser.value = { ...user.value }; // Restaura los datos originales
            localidad.value = user.value.localidad || ""; // Restaura la localidad original
            errorMessage.value = ""; // Limpia el mensaje de error
        };

        /**
         * Valida los datos antes de enviarlos al backend.
         */
        const validateForm = () => {
            // Aplicar trim a todos los campos
            editedUser.value.name = editedUser.value.name.trim();
            editedUser.value.email = editedUser.value.email.trim();
            editedUser.value.apellidos = editedUser.value.apellidos.trim();
            editedUser.value.telefono = editedUser.value.telefono.replace(/\s+/g, "").trim(); // Quitar todos los espacios

            if (!editedUser.value.name || editedUser.value.name.length > 100) {
                errorMessage.value = "El nombre es obligatorio y no debe exceder 100 caracteres.";
                return false;
            }
            if (!editedUser.value.email || !/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(editedUser.value.email)) {
                errorMessage.value = "El correo electrónico no es válido.";
                return false;
            }
            if (editedUser.value.apellidos && editedUser.value.apellidos.length > 200) {
                errorMessage.value = "Los apellidos no deben exceder 200 caracteres.";
                return false;
            }
            if (!editedUser.value.telefono || editedUser.value.telefono.length !== 9) {
                errorMessage.value = "El teléfono debe tener exactamente 9 dígitos.";
                return false;
            }
            if (localidad.value && isNaN(parseInt(localidad.value))) {
                errorMessage.value = "La localidad seleccionada no es válida.";
                return false;
            }
            return true;
        };

        /**
         * Guarda los cambios enviando una solicitud PATCH a la API para actualizar el usuario.
         */
        const saveChanges = async () => {
            if (!validateForm()) {
                modalMessage.value = errorMessage.value;
                isSuccess.value = false;
                showModal.value = true;
                return;
            }

            try {
                const token = sessionStorage.getItem("token");

                const updatedData = {
                    name: editedUser.value.name,
                    email: editedUser.value.email,
                    apellidos: editedUser.value.apellidos || null, // Nullable en la tabla
                    telefono: editedUser.value.telefono,
                    localidad: localidad.value ? parseInt(localidad.value) : null, // Nullable en la tabla
                    foto: null, // Nullable, no se edita en la UI
                };

                const response = await axios.patch(
                    `/api/user/update/${user.value.id}`,
                    updatedData,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                    }
                );

                // Actualiza los datos del usuario en sessionStorage y en la vista
                user.value = { ...editedUser.value, id: user.value.id, localidad: localidad.value ? parseInt(localidad.value) : null };
                sessionStorage.setItem("user", JSON.stringify(user.value));

                // Actualiza el nombre de la localidad
                const selectedLocalidad = localidades.value.find(loc => loc.id === parseInt(localidad.value));
                nombreLocalidad.value = selectedLocalidad ? `${selectedLocalidad.nombre} - ${selectedLocalidad.codigo_postal}` : "";

                isEditing.value = false;
                errorMessage.value = "";
                modalMessage.value = "Datos actualizados correctamente.";
                isSuccess.value = true;
                showModal.value = true;
            } catch (error) {
                console.error("Error al actualizar usuario:", error);
                if (error.response) {
                    if (error.response.status === 401) {
                        errorMessage.value = "Sesión inválida. Por favor, inicia sesión nuevamente.";
                        sessionStorage.removeItem("token");
                        sessionStorage.removeItem("user");
                        router.push("/");
                    } else if (error.response.status === 403) {
                        errorMessage.value = "No tienes permiso para actualizar este usuario.";
                    } else if (error.response.status === 404) {
                        errorMessage.value = "Usuario no encontrado.";
                    } else if (error.response.status === 422) {
                        const errors = error.response.data.errors;
                        errorMessage.value = Object.values(errors).flat().join(" ") || "Datos inválidos. Por favor, verifica los campos.";
                    } else {
                        errorMessage.value = error.response.data.message || "Error al actualizar los datos. Inténtalo de nuevo.";
                    }
                } else {
                    errorMessage.value = "Error de conexión. Inténtalo de nuevo más tarde.";
                }
                modalMessage.value = errorMessage.value;
                isSuccess.value = false;
                showModal.value = true;
            }
        };

        /**
         * Maneja la confirmación del modal.
         */
        const handleModalConfirm = () => {
            showModal.value = false;
            if (isSuccess.value) {
                isEditing.value = false; // Cierra el modo de edición tras un éxito
            }
            errorMessage.value = ""; // Limpia el mensaje de error tras cerrar
        };

        /**
         * Maneja la cancelación del modal.
         */
        const handleModalCancel = () => {
            showModal.value = false;
            errorMessage.value = ""; // Limpia el mensaje de error si se cancela
        };

        const cargarLocalidades = async () => {
            try {
                const response = await fetch('/api/localities');
                if (!response.ok) {
                    throw new Error(`Error al cargar localidades: ${response.status}`);
                }
                const data = await response.json();
                localidades.value = data;
            } catch (error) {
                console.error("Error al obtener las localidades:", error);
            }
        };

        return {
            user,
            nombreLocalidad,
            localidad,
            localidades,
            handleLogout,
            router,
            isEditing,
            editedUser,
            startEditing,
            cancelEditing,
            saveChanges,
            errorMessage,
            showModal,
            modalMessage,
            isSuccess,
            handleModalConfirm,
            handleModalCancel,
        };
    },
};
</script>

<template>
    <div class="settings-container">
        <!-- Datos Personales -->
        <section class="personal-info">
            <h1>Datos Personales</h1>
            <div v-if="user">
                <div class="form-group">
                    <label for="name"><strong>Nombre:</strong></label>
                    <input v-model="editedUser.name" id="name" type="text" :disabled="!isEditing"
                        :class="{ 'input-disabled': !isEditing }" />
                </div>
                <div class="form-group">
                    <label for="apellidos"><strong>Apellidos:</strong></label>
                    <input v-model="editedUser.apellidos" id="apellidos" type="text" :disabled="!isEditing"
                        :class="{ 'input-disabled': !isEditing }" />
                </div>
                <div class="form-group">
                    <label for="email"><strong>Email:</strong></label>
                    <input v-model="editedUser.email" id="email" type="email" :disabled="!isEditing"
                        :class="{ 'input-disabled': !isEditing }" />
                </div>
                <div class="form-group">
                    <label for="telefono"><strong>Teléfono:</strong></label>
                    <input v-model="editedUser.telefono" id="telefono" type="tel" :disabled="!isEditing"
                        :class="{ 'input-disabled': !isEditing }" />
                </div>
                <div class="form-group">
                    <label for="localidad"><strong>Localidad:</strong></label>
                    <input v-if="!isEditing" :value="nombreLocalidad" id="localidad" type="text" disabled
                        class="input-disabled" />
                    <select v-else v-model="localidad" id="localidad">
                        <option value="" disabled>Elige una localidad</option>
                        <option v-for="localidad in localidades" :key="localidad.id" :value="localidad.id">
                            {{ localidad.nombre }} - {{ localidad.codigo_postal }}
                        </option>
                    </select>
                </div>
                <div class="form-actions">
                    <button v-if="!isEditing" class="btn btn-edit flex-center" @click="startEditing">
                        Editar datos
                        <img src="/img/utils/pencil.svg" alt="Editar datos">
                    </button>
                    <div v-else class="flex edit-buttons">
                        <button class="btn btn-confirm flex-center" @click="saveChanges">
                            Guardar
                            <img src="/img/utils/tick.svg" alt="Guardar cambios">
                        </button>
                        <button class="btn btn-cancel flex-center" @click="cancelEditing">
                            Cancelar
                            <img src="/img/utils/close.svg" alt="Cancelar cambios">
                        </button>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>No hay información de usuario disponible.</p>
            </div>
        </section>

        <hr>
        <!-- Gestión de Cuenta -->
        <section class="account-management">
            <h1>Gestión de Cuenta</h1>
            <DangerButton @click="handleLogout"
                label="Cerrar sesión <img src='/img/utils/logout.svg' alt='Cerrar sesión'>" />
        </section>

        <!-- Modal para errores o éxito -->
        <ModalConfirm
            v-if="showModal"
            v-model:show="showModal"
            :message="modalMessage"
            :show-cancel="isSuccess"
            confirm-text="Aceptar"
            cancel-text="Cancelar"
            @confirm="handleModalConfirm"
            @cancel="handleModalCancel"
        />
    </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

*:not(h1) {
    @include fuente("parrafo");
}

h1 {
    margin-bottom: 1rem;
}

hr {
    background-color: map-get($colores, 'gris_claro');
    height: 2px;
    border: none;
    margin: 2rem 0;
}

.form-group {
    border: 2px solid map-get($colores, 'gris_claro');
    width: 40%;
    height: 2rem;
    margin-bottom: 1rem;
    border-radius: 5px;
    position: relative;

    label {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        padding: 0 5px;
    }

    input,
    select {
        background-color: transparent;
        width: 100%;
        height: 100%;
        padding-left: 20%;
        border: 0;

        &:focus {
            outline-color: map-get($colores, 'naranja');
        }
    }

    select {
        cursor: pointer;
        appearance: none;
    }
}

.form-group .input-disabled {
    background-color: map-get($colores, 'gris_claro');
}

.form-actions {
    margin: 5px 0;

    .btn {
        gap: 5px;
    }
}

.edit-buttons {
    gap: 1rem;
}
</style>