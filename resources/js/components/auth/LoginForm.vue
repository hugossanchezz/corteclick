<script>
import axios from "axios";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import { ref, computed, watch, onMounted } from "vue";

export default {
    name: "LoginForm",
    components: { PrimaryButton },
    setup() {
        const router = useRouter();
        const correo = ref("");
        const contrasenia = ref("");
        const recordarme = ref(false);
        const errorCorreo = ref("");
        const visibilidadContrasenia = ref(false);
        const credencialesInvalidas = ref(""); // Nueva variable para credenciales incorrectas

        // Expresión regular para la validación del correo
        const correoPattern =
            /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Estados derivados (computados)
        const tieneMinuscula = computed(() => /[a-z]/.test(contrasenia.value));
        const tieneMayuscula = computed(() => /[A-Z]/.test(contrasenia.value));
        const tieneNumero = computed(() => /\d/.test(contrasenia.value));
        const tieneCaracterEspecial = computed(() =>
            /[!@#$%^&*]/.test(contrasenia.value)
        );
        const tieneLongitudMinima = computed(
            () => contrasenia.value.length >= 8
        );

        const contraseniaValida = computed(() => {
            return (
                tieneMinuscula.value &&
                tieneMayuscula.value &&
                tieneNumero.value &&
                tieneCaracterEspecial.value &&
                tieneLongitudMinima.value
            );
        });

        const tieneErrores = computed(
            () => errorCorreo.value || !contraseniaValida.value
        );

        const iconoVisibilidad = computed(() =>
            visibilidadContrasenia.value
                ? "/img/auth/visibility_off.svg"
                : "/img/auth/visibility_on.svg"
        );

        const tipoInput = computed(() =>
            visibilidadContrasenia.value ? "text" : "password"
        );

        // Observadores (watch)
        watch(correo, (newValue) => {
            errorCorreo.value = correoPattern.test(newValue)
                ? ""
                : "Correo electrónico inválido";
        });

        // Método para manejar el envío del formulario
        const submitForm = async () => {
            credencialesInvalidas.value = ""; // Resetea el mensaje de error de credenciales
            try {
                const response = await axios.post("/api/login", {
                    email: correo.value,
                    password: contrasenia.value,
                    remember: recordarme.value,
                });
                //console.log("Inicio de sesión exitoso");

                // Almacenar el token y la información del usuario
                localStorage.setItem("token", response.data.access_token);
                localStorage.setItem(
                    "user",
                    JSON.stringify(response.data.user)
                );
                if (recordarme.value) {
                    localStorage.setItem("rememberMe", "true");
                }

                router.push("/"); // Redirigir a la página principal
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    // Error de credenciales incorrectas
                    credencialesInvalidas.value =
                        "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
                } else if (error.response && error.response.status === 422) {
                    errorCorreo.value =
                        error.response.data.errors?.email?.[0] || "";
                } else {
                    alert(
                        "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde."
                    );
                }
            }
        };

        // Verificar "Recordarme" al cargar el componente
        onMounted(() => {
            const rememberMe = localStorage.getItem("rememberMe");
            const storedToken = localStorage.getItem("token");
            const storedUser = localStorage.getItem("user");

            if (rememberMe === "true" && storedToken && storedUser) {
                try {
                    const user = JSON.parse(storedUser);
                    recordarme.value = true;
                    router.push("/");
                } catch (error) {
                    console.error("Error al parsear el usuario", error);
                    localStorage.removeItem("rememberMe");
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                }
            }
        });

        return {
            correo,
            contrasenia,
            recordarme,
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
            <input
                v-model="correo"
                type="text"
                placeholder="Correo electrónico"
                required
            />
        </div>
        <div v-if="errorCorreo" class="errorMensaje">{{ errorCorreo }}</div>

        <div class="flex-column">
            <label class="label-form" for="contrasenia"> Contraseña </label>
        </div>
        <div class="inputForm flex">
            <img
                src="/img/auth/lock.svg"
                alt="Icono de candado de contraseña"
            />
            <input
                v-model="contrasenia"
                :type="tipoInput"
                placeholder="Contraseña"
                required
            />
            <img
                class="input-visibilidad"
                :src="iconoVisibilidad"
                alt="Mostrar y ocultar contraseña"
                @click="visibilidadContrasenia = !visibilidadContrasenia"
            />
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

        <div class="flex mg-tb-1">
            <input v-model="recordarme" type="checkbox" />
            <label for="recordarme"> Recordarme </label>
        </div>

        <PrimaryButton label="Iniciar Sesión" />

        <p class="p">
            ¿No tienes una cuenta?
            <router-link to="/register" class="span"> Regístrate </router-link>
        </p>
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
