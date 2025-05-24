<script>
// Importamos las librerías necesarias para el componente.
import axios from "axios";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import { ref, computed, watch, onMounted } from "vue";
import { isAuthenticated } from "@/js/auth/eventBus";

export default {
    name: "LoginForm",

    components: { PrimaryButton },

    setup() {
        // Obtenemos la instancia del router para poder navegar entre las rutas.
        const router = useRouter();

        // Estados del formulario usando ref
        const correo = ref("");
        const contrasenia = ref("");
        const errorCorreo = ref("");
        const visibilidadContrasenia = ref(false);
        const credencialesInvalidas = ref("");

        // Expresión regular para la validación del correo
        const correoPattern =
            /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Estados derivados (computados)
        // Los computed properties se actualizan automáticamente cuando sus dependencias reactivas cambian.
        const tieneMinuscula = computed(() => /[a-z]/.test(contrasenia.value)); // Comprueba si la contraseña contiene al menos una minúscula.
        const tieneMayuscula = computed(() => /[A-Z]/.test(contrasenia.value)); // Comprueba si la contraseña contiene al menos una mayúscula.
        const tieneNumero = computed(() => /\d/.test(contrasenia.value)); // Comprueba si la contraseña contiene al menos un número.
        const tieneCaracterEspecial = computed(
            () => /[!@#$%^&*]/.test(contrasenia.value) // Comprueba si la contraseña contiene al menos un carácter especial.
        );
        const tieneLongitudMinima = computed(
            () => contrasenia.value.length >= 8 // Comprueba si la contraseña tiene al menos 8 caracteres de longitud.
        );

        const contraseniaValida = computed(() => {
            // Determina si la contraseña cumple con todos los requisitos definidos.
            return (
                tieneMinuscula.value &&
                tieneMayuscula.value &&
                tieneNumero.value &&
                tieneCaracterEspecial.value &&
                tieneLongitudMinima.value
            );
        });

        const tieneErrores = computed(
            () => errorCorreo.value || !contraseniaValida.value // Determina si hay algún error en el formulario (error de correo o contraseña no válida).
        );

        const iconoVisibilidad = computed(
            () =>
                visibilidadContrasenia.value
                    ? "/img/auth/visibility_off.svg" // Si la contraseña es visible, muestra el icono de ocultar.
                    : "/img/auth/visibility_on.svg" // Si la contraseña no es visible, muestra el icono de mostrar.
        );

        const tipoInput = computed(
            () => (visibilidadContrasenia.value ? "text" : "password") // Determina el tipo de input de la contraseña según su visibilidad.
        );

        // Observadores (watch)
        // Los watchers permiten ejecutar una función en respuesta a los cambios de una referencia reactiva.
        watch(correo, (newValue) => {
            // Observa los cambios en el campo de correo electrónico para validar su formato.
            errorCorreo.value = correoPattern.test(newValue)
                ? "" // Si el formato es válido, el mensaje de error se vacía.
                : "Correo electrónico inválido"; // Si el formato no es válido, se establece un mensaje de error.
        });

        // Método para manejar el envío del formulario
        const submitForm = async () => {
            credencialesInvalidas.value = ""; // Resetea el mensaje de error de credenciales al intentar iniciar sesión.
            try {
                // Realiza una petición POST a la API de inicio de sesión con las credenciales del usuario.
                const response = await axios.post("/api/login", {
                    email: correo.value,
                    password: contrasenia.value,
                });

                // Almacenar el token y la información del usuario en el almacenamiento local del navegador.
                sessionStorage.setItem("token", response.data.access_token);
                sessionStorage.setItem(
                    "user",
                    JSON.stringify(response.data.user)
                );

                // Cambiar varible global de estar logueado a true
                isAuthenticated.value = true;
                router.push("/"); // Redirige al usuario a la página principal después del inicio de sesión exitoso.
            } catch (error) {
                // Captura cualquier error ocurrido durante la petición de inicio de sesión.
                if (error.response && error.response.status === 401) {
                    // Error de credenciales incorrectas (código de estado 401 no autorizado).
                    credencialesInvalidas.value =
                        "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
                } else if (error.response && error.response.status === 422) {
                    // Error de validación del servidor (código de estado 422 entidad no procesable).
                    // Intenta obtener el mensaje de error específico del campo 'email' desde la respuesta del servidor.
                    errorCorreo.value =
                        error.response.data.errors?.email?.[0] || "";
                } else {
                    // Otro tipo de error inesperado.
                    alert(
                        "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde."
                    );
                }
            }
        };

        // Verificar si ya iniciaste sesión al cargar el componente
        onMounted(() => {
            const storedToken = sessionStorage.getItem("token"); // Intenta obtener el token del almacenamiento local.
            const storedUser = sessionStorage.getItem("user"); // Intenta obtener la información del usuario del almacenamiento local.

            // Si "rememberMe" es true y hay un token y un usuario almacenados.
            if (storedToken && storedUser) {
                try {
                    alert(
                        "Ya iniciaste sesión anteriormente. Cierra la sesión para iniciar una nueva."
                    );
                    router.push("/"); // Redirige al usuario a la página principal sin necesidad de iniciar sesión de nuevo.
                } catch (error) {
                    // Si hay un error al parsear el usuario (puede que esté corrupto), limpia el almacenamiento local.
                    console.error("Error al parsear el usuario", error);
                    sessionStorage.removeItem("rememberMe");
                    sessionStorage.removeItem("token");
                    sessionStorage.removeItem("user");
                }
            }
        });

        // Retorna los estados y métodos que se utilizarán en la plantilla del componente.
        return {
            correo,
            contrasenia,
            errorCorreo,
            visibilidadContrasenia,
            tieneMinuscula,
            tieneMayuscula,
            tieneNumero,
            tieneCaracterEspecial,
            tieneLongitudMinima,
            tieneErrores,
            contraseniaValida,
            iconoVisibilidad,
            tipoInput,
            submitForm,
            router,
            credencialesInvalidas,
        };
    },
};
</script>

<template>
    <form class="flex" @submit.prevent="submitForm">
        <h1 class="flex-center">Inicia sesión en tu cuenta</h1>
        <hr />

        <div class="flex-column">
            <label class="label-form" for="correo"> Correo </label>
        </div>
        <div class="inputForm flex">
            <img src="/img/auth/at_sign.svg" alt="Icono de arroba" />
            <input v-model="correo" type="text" placeholder="Correo electrónico" required />
        </div>
        <div v-if="errorCorreo" class="errorMensaje">{{ errorCorreo }}</div>

        <div class="flex-column">
            <label class="label-form" for="contrasenia"> Contraseña </label>
        </div>
        <div class="inputForm flex">
            <img src="/img/auth/lock.svg" alt="Icono de candado de contraseña" />
            <input v-model="contrasenia" :type="tipoInput" placeholder="Contraseña" required />
            <img class="input-visibilidad" :src="iconoVisibilidad" alt="Mostrar y ocultar contraseña"
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

        <div v-if="credencialesInvalidas" class="errorMensaje mg-tb-1">
            {{ credencialesInvalidas }}
        </div>

        <div class="form__bottom flex-column mg-tb-1">
            <PrimaryButton label="Iniciar Sesión" />
            <p class="p">
                ¿No tienes una cuenta?
                <router-link to="/auth/register" class="span"> Regístrate </router-link>
            </p>
        </div>

    </form>
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

    flex-direction: column;
    gap: 10px;
    border-radius: 20px;
}

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
}

label {
    margin-left: 5px;
}

// Estilos para .span
.span {
    color: map-get($colores, "naranja");
    font-weight: 500;

    cursor: pointer;
}

// Estilos para la template de términos y condiciones
.content {
    border: 1px solid map-get($colores, "gris_claro");
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    height: 40rem;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    overflow-y: auto;

    a {
        color: map-get($colores, "naranja");
        text-decoration: none;

        &:hover {
            text-decoration: underline;
        }
    }

    .final {
        color: map-get($colores, "gris");
        text-align: center;
        margin-top: 20px;
    }

    /* Asegurarse de que el contenido no se solape */
    p,
    h2,
    ul {
        margin-bottom: 20px;
    }
}

.form__bottom {
    align-items: center;
    gap: 1rem;
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
