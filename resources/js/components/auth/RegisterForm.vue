<script>
// Importamos las librerías
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, computed, watch, onMounted } from "vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
    name: "RegisterForm",

    components: { PrimaryButton, ModalConfirm },

    setup() {
        // Obtenemos la instancia del router para poder navegar entre las rutas.
        const router = useRouter();

        // Estados del formulario usando ref
        // ref crea una referencia reactiva a un valor. Aquí definimos referencias para cada campo del formulario.
        const nombre = ref("");
        const apellidos = ref("");
        const correo = ref("");
        const contrasenia = ref("");
        const confirmarContrasenia = ref("");
        const localidad = ref("");
        const localidades = ref([]);
        const telefono = ref("");
        const aceptarTerminos = ref(false);

        // Objeto para almacenar los errores de cada campo
        // Este objeto reactivo contendrá los mensajes de error para cada campo del formulario.
        const errores = ref({});
        // Este ref almacena un mensaje de error general para mostrar en caso de fallas en la validación del cliente o del servidor.
        const generalErrorMessage = ref("");
        // Este ref podría usarse para indicar si el registro fue exitoso (aunque actualmente no se usa en la plantilla).
        const registroExitoso = ref(false);
        // Este ref podría usarse para almacenar mensajes de error relacionados con las credenciales (aunque actualmente no se usa).
        const credencialesInvalidas = ref("");

        // Refs para controlar la visibilidad de las contraseñas.
        const visibilidadContrasenia = ref(false);
        const visibilidadConfirmarContrasenia = ref(false);

        // Expresiones regulares para validación
        // Definimos expresiones regulares para validar el formato del correo electrónico y el número de teléfono.
        const correoPattern =
            /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const telefonoPattern = /^\+?[0-9]{9,15}$/;

        // Modal 
        const showModal = ref(false);
        const modalMessage = ref("");
        const modalTitle = ref("");

        watch(showModal, (nuevoValor, valorAnterior) => {
            if (valorAnterior === true && nuevoValor === false) {
                // El modal fue visible y ahora ya no lo es → se ha cerrado
                router.push("/auth");
            }
        });

        const mostrarModal = (titulo, mensaje) => {
            modalTitle.value = titulo;
            modalMessage.value = mensaje;
            showModal.value = true;
        };

        // Estados derivados (computados) para la contraseña
        // Los computed properties se utilizan para derivar valores reactivos basados en otros estados reactivos.
        // Aquí, verificamos si la contraseña cumple con los criterios.
        const tieneMinuscula = computed(() => /[a-z]/.test(contrasenia.value));
        const tieneMayuscula = computed(() => /[A-Z]/.test(contrasenia.value));
        const tieneNumero = computed(() => /\d/.test(contrasenia.value));
        const tieneCaracterEspecial = computed(() =>
            /[!@#$%^&*]/.test(contrasenia.value)
        );
        const tieneLongitudMinima = computed(
            () => contrasenia.value.length >= 8
        );

        // Este computed property determina si la contraseña cumple con todos los criterios de validación.
        const contraseniaValida = computed(() => {
            return (
                tieneMinuscula.value &&
                tieneMayuscula.value &&
                tieneNumero.value &&
                tieneCaracterEspecial.value &&
                tieneLongitudMinima.value
            );
        });

        // Este computed property verifica si el objeto de errores tiene alguna propiedad, indicando si hay algún error.
        const tieneErrores = computed(
            () => Object.keys(errores.value).length > 0
        );

        // Iconos de visibilidad de contraseña
        // Computed properties para determinar la ruta del icono a mostrar según la visibilidad de la contraseña.
        const iconoVisibilidadContrasenia = computed(() =>
            visibilidadContrasenia.value
                ? "/img/auth/visibility_off.svg"
                : "/img/auth/visibility_on.svg"
        );
        // Computed property para determinar el tipo de input de la contraseña (text o password) según su visibilidad.
        const tipoInputContrasenia = computed(() =>
            visibilidadContrasenia.value ? "text" : "password"
        );

        // Iconos de visibilidad para la confirmación de contraseña (igual que la contraseña).
        const iconoVisibilidadConfirmarContrasenia = computed(() =>
            visibilidadConfirmarContrasenia.value
                ? "/img/auth/visibility_off.svg"
                : "/img/auth/visibility_on.svg"
        );
        const tipoInputConfirmarContrasenia = computed(() =>
            visibilidadConfirmarContrasenia.value ? "text" : "password"
        );

        // Función genérica para actualizar los errores
        // Esta función recibe el nombre del campo y el mensaje de error. Actualiza el objeto de errores.
        const actualizarError = (campo, mensaje) => {
            // Asigna el mensaje de error al campo especificado en el objeto de errores.
            errores.value[campo] = mensaje;
            // Si el mensaje está vacío (no hay error), elimina la propiedad del objeto de errores.
            if (!mensaje) {
                delete errores.value[campo];
            }
        };

        const cargarLocalidades = async () => {
            try {
                const response = await fetch('/api/localities'); // Asegúrate de que esta ruta exista
                if (!response.ok) {
                    throw new Error(`Error al cargar localidades: ${response.status}`);
                }
                const data = await response.json();

                localidades.value = data;
            } catch (error) {
                console.error("Error al obtener las localidades:", error);
            }
        };

        // Observadores (watch)
        // Los watchers se utilizan para reaccionar a los cambios en un estado reactivo.
        // Aquí, para cada campo del formulario, definimos un watcher que valida el valor y actualiza el error correspondiente.
        watch(nombre, (newValue) => {
            actualizarError(
                "nombre",
                !newValue
                    ? "El nombre es requerido"
                    : /[^a-zA-Z0-9\s]/.test(newValue)
                        ? "No se permiten caracteres especiales"
                        : ""
            );
        });

        watch(correo, (newValue) => {
            actualizarError(
                "correo",
                !newValue
                    ? "El correo es requerido"
                    : !correoPattern.test(newValue)
                        ? "Correo no válido"
                        : ""
            );
        });

        watch(contrasenia, (newValue) => {
            actualizarError(
                "contrasenia",
                !newValue
                    ? "La contraseña es requerida"
                    : !contraseniaValida.value
                        ? "La contraseña no cumple con los requisitos"
                        : ""
            );
        });

        watch(confirmarContrasenia, (newValue) => {
            actualizarError(
                "confirmarContrasenia",
                newValue !== contrasenia.value ? "Las contraseñas no coinciden" : ""
            );
        });

        watch(aceptarTerminos, (newValue) => {
            actualizarError(
                "aceptarTerminos",
                !newValue ? "Debes aceptar los términos y condiciones" : ""
            );
        });

        watch(telefono, (newValue) => {
            actualizarError(
                "telefono",
                !newValue
                    ? "El teléfono es requerido"
                    : !telefonoPattern.test(newValue)
                        ? "Teléfono no válido"
                        : ""
            );
        });

        // Método para manejar el envío del formulario
        const submitForm = async () => {
            // Reiniciamos los mensajes de error general al intentar enviar el formulario.
            generalErrorMessage.value = "";
            credencialesInvalidas.value = "";
            // Verificamos si hay errores en el formulario utilizando el computed property tieneErrores.
            if (tieneErrores.value) {
                generalErrorMessage.value =
                    "Por favor, corrige los errores en el formulario.";
                return; // Detenemos el envío si hay errores.
            }

            try {
                // Realizamos una petición POST a la API de registro con los datos del formulario.
                const response = await axios.post("/api/register", {
                    name: nombre.value,
                    apellidos: apellidos.value,
                    email: correo.value,
                    password: contrasenia.value,
                    telefono: telefono.value,
                    localidad: localidad.value,
                    aceptarTerminos: aceptarTerminos.value,
                });
                // Si la respuesta tiene un estado 200 (éxito).
                if (response.status === 200) {
                    mostrarModal(
                        "Registro exitoso",
                        "Tu cuenta ha sido creada correctamente. Ya puedes iniciar sesión."
                    );

                    if (showModal.value === false) {
                        router.push("/auth");
                    }
                }
            } catch (error) {
                // En caso de error en la petición.
                console.error("Error de registro", error.response);
                // Si el error de respuesta tiene un estado 422 (Unprocessable Entity), indica errores de validación del servidor.
                if (error.response && error.response.status === 422) {
                    const erroresServidor = error.response.data.errors;
                    // Limpiamos los errores anteriores del cliente.
                    errores.value = {};
                    // Actualizamos el objeto de errores con los errores proporcionados por el servidor.
                    for (const campo in erroresServidor) {
                        actualizarError(campo, erroresServidor[campo][0]);
                    }
                    // Mostramos un mensaje de error general indicando que hay errores en los campos.
                    generalErrorMessage.value =
                        "Error en el registro. Por favor, revisa los campos o prueba con otro correo o teléfono.";
                } else {
                    // Si el error es diferente a un error de validación del servidor, mostramos un mensaje de error genérico.
                    generalErrorMessage.value =
                        "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
                }
            }
        };

        onMounted(() => {
            cargarLocalidades();
        });

        // Retornamos todos los estados y métodos que queremos exponer en la plantilla del componente.
        return {
            nombre,
            apellidos,
            correo,
            contrasenia,
            confirmarContrasenia,
            localidad,
            localidades,
            telefono,
            aceptarTerminos,

            errores, // Objeto de errores para la plantilla
            generalErrorMessage, // Mensaje de error general para la plantilla

            visibilidadContrasenia,
            visibilidadConfirmarContrasenia,
            iconoVisibilidadContrasenia,
            tipoInputContrasenia,
            iconoVisibilidadConfirmarContrasenia,
            tipoInputConfirmarContrasenia,

            tieneMinuscula,
            tieneMayuscula,
            tieneNumero,
            tieneCaracterEspecial,
            tieneLongitudMinima,
            registroExitoso,
            router,
            credencialesInvalidas,
            tieneErrores,
            submitForm,

            showModal,
            modalTitle,
            modalMessage,
            mostrarModal
        };
    },
};
</script>

<template>
    <form class="flex-column" @submit.prevent="submitForm">
        <h1 class="flex-center">Crea tu nueva cuenta</h1>
        <hr />

        <div class="form__horizontal flex">
            <div class="flex-column form__campo">
                <label for="nombre"> Nombre </label>
                <div class="inputForm flex">
                    <img src="/img/auth/person.svg" alt="Icono para input de nombre" />
                    <input v-model="nombre" type="text" placeholder="Sin caracteres especiales" required />
                </div>
                <div v-if="errores.nombre" class="errorMensaje">
                    {{ errores.nombre }}
                </div>
            </div>

            <div class="flex-column form__campo">
                <label for="apellidos"> Apellidos <span class="span__form_opcional">(Opcional)</span></label>
                <div class="inputForm flex">
                    <img src="/img/auth/person.svg" alt="Icono para input de apellidos" />
                    <input v-model="apellidos" type="text" placeholder="Sin caracteres especiales" />
                </div>
                <div v-if="errores.apellidos" class="errorMensaje">
                    {{ errores.apellidos }}
                </div>
            </div>
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="correo"> Correo </label>
            <div class="inputForm flex">
                <img src="/img/auth/at_sign.svg" alt="Icono de correo" />
                <input v-model="correo" type="email" placeholder="ejemplo@email.com" required />
            </div>
            <div v-if="errores.correo" class="errorMensaje">
                {{ errores.correo }}
            </div>
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="contrasenia"> Contraseña </label>
            <div class="inputForm flex">
                <img src="/img/auth/lock.svg" alt="Icono de contraseña" />
                <input v-model="contrasenia" :type="tipoInputContrasenia" placeholder="Contraseña" required />
                <img class="input-visibilidad" :src="iconoVisibilidadContrasenia" alt="Mostrar y ocultar contraseña"
                    @click="visibilidadContrasenia = !visibilidadContrasenia" />
            </div>
            <ul v-if="contrasenia.length" class="errorMensaje">
                <li :class="{ correcto: tieneMinuscula }">
                    Debe tener al menos una letra minúscula
                </li>
                <li :class="{ correcto: tieneMayuscula }">
                    Debe tener al menos una letra mayúscula
                </li>
                <li :class="{ correcto: tieneNumero }">
                    Debe tener al menos un número
                </li>
                <li :class="{ correcto: tieneCaracterEspecial }">
                    Debe tener al menos un carácter especial (!@#$%^&*)
                </li>
                <li :class="{ correcto: tieneLongitudMinima }">
                    Debe tener al menos 8 caracteres
                </li>
            </ul>
            <div v-if="errores.contrasenia" class="errorMensaje">
                {{ errores.contrasenia }}
            </div>
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="confirmarContrasenia">
                Confirma tu Contraseña
            </label>
            <div class="inputForm flex">
                <img src="/img/auth/lock.svg" alt="Icono de contraseña" />
                <input v-model="confirmarContrasenia" :type="tipoInputConfirmarContrasenia"
                    placeholder="Confirma tu contraseña" required />
                <img class="input-visibilidad" :src="iconoVisibilidadConfirmarContrasenia"
                    alt="Mostrar y ocultar contraseña" @click="
                        visibilidadConfirmarContrasenia =
                        !visibilidadConfirmarContrasenia
                        " />
            </div>
            <div v-if="errores.confirmarContrasenia" class="errorMensaje">
                {{ errores.confirmarContrasenia }}
            </div>
        </div>

        <div class="form__horizontal flex">
            <div class="flex-column form__campo">
                <label class="label-form" for="localidad"> Localidad <span
                        class="span__form_opcional">(Opcional)</span></label>
                <div class="inputForm flex">
                    <select v-model="localidad">
                        <option selected disabled value="">
                            Selecciona una localidad
                        </option>
                        <option v-for="localidad in localidades" :key="localidad.id" :value="localidad.id">
                            {{ localidad.nombre }}
                        </option>
                    </select>
                </div>
                <div v-if="errores.localidad" class="errorMensaje">
                    {{ errores.localidad }}
                </div>
            </div>

            <div class="flex-column form__campo">
                <label class="label-form" for="telefono"> Teléfono <span
                        class="span__form_opcional">(Opcional)</span></label>
                <div class="inputForm flex">
                    <img src="/img/auth/phone.svg" alt="Icono de telefono" />
                    <input v-model="telefono" type="tel" placeholder="+34 000 000 000" />
                </div>
                <div v-if="errores.telefono" class="errorMensaje">
                    {{ errores.telefono }}
                </div>
            </div>
        </div>
        <div class="mg-tb-1">
            <div class="terminos flex">
                <div class="terminos__switch">
                    <label class="switch">
                        <input v-model="aceptarTerminos" type="checkbox" required />
                        <span class="slider"></span>
                    </label>
                </div>
                <label for="aceptarTerminos">
                    Acepto los
                    <router-link to="/auth/register/terms" class="span">
                        términos y condiciones
                    </router-link>
                </label>
            </div>
            <div v-if="errores.aceptarTerminos" class="errorMensaje">
                {{ errores.aceptarTerminos }}
            </div>
        </div>
        <div v-if="generalErrorMessage" class="errorMensaje">
            {{ generalErrorMessage }}
        </div>
        <div class="form__bottom flex-column mg-tb-1">
            <PrimaryButton label="Registrarse" />
            <p class="p">
                ¿Ya tienes una cuenta?
                <router-link to="/auth" class="span"> Inicia sesión </router-link>
            </p>
        </div>

    </form>
    <ModalConfirm v-model:show="showModal" :title="modalTitle" :message="modalMessage" :showCancel="false"
        confirmText="Aceptar" />
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

* {
    @include fuente("parrafo");
}

form {
    width: 40%;
    padding: 20px 30px;

    gap: 10px;
    border-radius: 20px;

    .form__horizontal {
        gap: 10px;
    }

    .form__horizontal>div:nth-child(1) {
        width: 40%;
    }

    .form__horizontal>div:nth-child(2) {
        width: 60%;
    }

    .form__campo {
        gap: 2px;

        .inputForm {
            border: 1.5px solid map-get($colores, "gris_claro");
            height: 46px;
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
            }
        }
    }

    .form__bottom {
        align-items: center;
        gap: 1rem;
    }

    label {
        margin-left: 5px;
    }


}

// Estilos para .span
.span {
    color: map-get($colores, "naranja");
    font-weight: 500;

    cursor: pointer;
}

.errorMensaje {
    color: map-get($colores, "rojo");
}

.correcto {
    color: map-get($colores, "verde");
}

// Icono de mostrar/ocultar la contraseña
.input-visibilidad {
    cursor: pointer;
}

// Ajustes para portátiles o pantallas más pequeñas
@include responsive-layout(1440px) {
    .contenedor-inicio-sesion {
        width: 80%;
    }

    .form-container {
        margin-bottom: 1rem;
    }
}

// Estilos para tabletas (≤ 1024px)
@include responsive-layout(1024px) {
    .form {
        width: 100%;
        padding: 0;
    }

    .input {
        margin-left: 0;
    }
}
</style>
