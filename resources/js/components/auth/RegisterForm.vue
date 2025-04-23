<script>
import axios from "axios";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import { ref, computed, watch, onMounted } from "vue";

export default {
    name: "RegisterForm",
    components: { PrimaryButton },
    setup() {
        const router = useRouter();

        // Estados del formulario usando ref
        const nombre = ref("");
        const apellidos = ref("");
        const correo = ref("");
        const contrasenia = ref("");
        const confirmarContrasenia = ref("");
        const localidad = ref("");
        const telefono = ref(""); // Nuevo campo para el teléfono
        const aceptarTerminos = ref(false);

        // Estados de error usando ref
        const errorNombre = ref("");
        const errorApellidos = ref("");
        const errorCorreo = ref("");
        const errorContrasenia = ref("");
        const errorConfirmarContrasenia = ref("");
        const errorLocalidad = ref("");
        const errorTelefono = ref(""); // Nuevo estado de error para el teléfono
        const errorAceptarTerminos = ref("");
        const generalErrorMessage = ref(""); // Nuevo ref para el mensaje de error general

        const visibilidadContrasenia = ref(false);
        const visibilidadConfirmarContrasenia = ref(false);
        const registroExitoso = ref(false); // Nuevo estado para indicar registro exitoso
        const credencialesInvalidas = ref("");

        // Expresiones regulares para validación
        const correoPattern =
            /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const telefonoPattern = /^\+?[0-9]{9,15}$/; // Patrón para un número de teléfono internacional

        // Estados derivados (computados) para la contraseña
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

        const tieneErrores = computed(() => {
            return (
                errorNombre.value ||
                errorApellidos.value ||
                errorCorreo.value ||
                errorContrasenia.value ||
                errorConfirmarContrasenia.value ||
                errorLocalidad.value ||
                errorTelefono.value || // Incluir error de teléfono
                errorAceptarTerminos.value
            );
        });

        // Iconos de visibilidad de contraseña
        const iconoVisibilidadContrasenia = computed(() =>
            visibilidadContrasenia.value
                ? "/img/auth/visibility_off.svg"
                : "/img/auth/visibility_on.svg"
        );
        const tipoInputContrasenia = computed(() =>
            visibilidadContrasenia.value ? "text" : "password"
        );

        const iconoVisibilidadConfirmarContrasenia = computed(() =>
            visibilidadConfirmarContrasenia.value
                ? "/img/auth/visibility_off.svg"
                : "/img/auth/visibility_on.svg"
        );
        const tipoInputConfirmarContrasenia = computed(() =>
            visibilidadConfirmarContrasenia.value ? "text" : "password"
        );

        // Observadores (watch)
        watch(nombre, (newValue) => {
            errorNombre.value = !newValue
                ? "El nombre es requerido"
                : /[^a-zA-Z0-9]/.test(newValue)
                ? "No se permiten caracteres especiales"
                : "";
        });

        watch(correo, (newValue) => {
            errorCorreo.value = !newValue
                ? "El correo es requerido"
                : !correoPattern.test(newValue)
                ? "Correo no válido"
                : "";
        });

        watch(contrasenia, (newValue) => {
            errorContrasenia.value = !newValue
                ? "La contraseña es requerida"
                : !contraseniaValida.value
                ? "La contraseña no cumple con los requisitos"
                : "";
        });

        watch(confirmarContrasenia, (newValue) => {
            errorConfirmarContrasenia.value =
                newValue !== contrasenia.value
                    ? "Las contraseñas no coinciden"
                    : "";
        });

        watch(aceptarTerminos, (newValue) => {
            errorAceptarTerminos.value = !newValue
                ? "Debes aceptar los términos y condiciones"
                : "";
        });

        watch(telefono, (newValue) => {
            errorTelefono.value = !newValue
                ? "El teléfono es requerido"
                : !telefonoPattern.test(newValue)
                ? "Teléfono no válido"
                : "";
        });

        // Método para manejar el envío del formulario
        const submitForm = async () => {
            generalErrorMessage.value = ""; // Limpiar el mensaje de error general
            credencialesInvalidas.value = "";
            if (tieneErrores.value) {
                generalErrorMessage.value =
                    "Por favor, corrige los errores en el formulario.";
                return; // Detener el envío si hay errores
            }

            try {
                await axios.post("/api/register", {
                    // Ajusta la ruta de la API
                    name: nombre.value,
                    apellidos: apellidos.value,
                    email: correo.value,
                    password: contrasenia.value,
                    telefono: telefono.value,
                    localidad: localidad.value,
                });

                alert("¡Gracias por registrarte! Ahora puedes iniciar sesión.");

                // Redirigir al login
                router.push("/login");
            } catch (error) {
                console.error("Error de registro", error.response);
                if (error.response && error.response.status === 422) {
                    // Recorre los errores de validación y actualiza los refs de error correspondientes
                    const errores = error.response.data.errors;
                    if (errores.name) errorNombre.value = errores.name[0];
                    if (errores.surname)
                        errorApellidos.value = errores.surname[0];
                    if (errores.email) errorCorreo.value = errores.email[0];
                    if (errores.password)
                        errorContrasenia.value = errores.password[0];
                    if (errores.phone) errorTelefono.value = errores.phone[0];
                    if (errores.location)
                        errorLocalidad.value = errores.location[0];
                    if (errores.terms)
                        errorAceptarTerminos.value = errores.terms[0];
                    generalErrorMessage.value =
                        "Error en el registro. Por favor, revisa los campos."; // Establecer mensaje de error
                } else {
                    generalErrorMessage.value =
                        "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
                }
            }
        };

        // Verificar "Recordarme" al cargar el componente
        /* onMounted(() => {
          const storedToken = localStorage.getItem("token");
          const storedUser = localStorage.getItem("user");

          if (storedToken && storedUser) {
              try {
                  alert("Cierra la sesión actual para poder registrarte");
                  router.push("/");
              } catch (error) {
                  console.error("Error al parsear el usuario", error);
                  localStorage.removeItem("token");
                  localStorage.removeItem("user");
              }
          }
      });
      */

        return {
            nombre,
            apellidos,
            correo,
            contrasenia,
            confirmarContrasenia,
            localidad,
            telefono,
            aceptarTerminos,
            errorNombre,
            errorApellidos,
            errorCorreo,
            errorContrasenia,
            errorConfirmarContrasenia,
            errorLocalidad,
            errorTelefono,
            errorAceptarTerminos,
            visibilidadContrasenia,
            visibilidadConfirmarContrasenia,
            iconoVisibilidadContrasenia,
            tipoInputContrasenia,
            iconoVisibilidadConfirmarContrasenia,
            tipoInputConfirmarContrasenia,
            submitForm,
            tieneMinuscula,
            tieneMayuscula,
            tieneNumero,
            tieneCaracterEspecial,
            tieneLongitudMinima,
            registroExitoso,
            router,
            credencialesInvalidas,
            tieneErrores,
            generalErrorMessage,
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
                <label for="nombre"> Nombre * </label>
                <div class="inputForm flex">
                    <img
                        src="/img/auth/person.svg"
                        alt="Icono para input de nombre"
                    />
                    <input
                        v-model="nombre"
                        type="text"
                        placeholder="Sin caracteres especiales"
                        required
                    />
                </div>
                <div v-if="errorNombre" class="errorMensaje">
                    {{ errorNombre }}
                </div>
            </div>

            <div class="flex-column form__campo">
                <label for="apellidos"> Apellidos </label>
                <div class="inputForm flex">
                    <img
                        src="/img/auth/person.svg"
                        alt="Icono para input de apellidos"
                    />
                    <input
                        v-model="apellidos"
                        type="text"
                        placeholder="Sin caracteres especiales"
                    />
                </div>
                <div v-if="errorApellidos" class="errorMensaje">
                    {{ errorApellidos }}
                </div>
            </div>
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="correo"> Correo * </label>
            <div class="inputForm flex">
                <img src="/img/auth/at_sign.svg" alt="Icono de correo" />
                <input
                    v-model="correo"
                    type="email"
                    placeholder="ejemplo@email.com"
                    required
                />
            </div>
            <div v-if="errorCorreo" class="errorMensaje">{{ errorCorreo }}</div>
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="contrasenia"> Contraseña * </label>
            <div class="inputForm flex">
                <img src="/img/auth/lock.svg" alt="Icono de contraseña" />
                <input
                    v-model="contrasenia"
                    :type="tipoInputContrasenia"
                    placeholder="Contraseña"
                    required
                />
                <img
                    class="input-visibilidad"
                    :src="iconoVisibilidadContrasenia"
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
        </div>

        <div class="flex-column form__campo">
            <label class="label-form" for="confirmarContrasenia">
                Confirma tu Contraseña *
            </label>
            <div class="inputForm flex">
                <img src="/img/auth/lock.svg" alt="Icono de contraseña" />
                <input
                    v-model="confirmarContrasenia"
                    :type="tipoInputConfirmarContrasenia"
                    placeholder="Confirma tu contraseña"
                    required
                />
                <img
                    class="input-visibilidad"
                    :src="iconoVisibilidadConfirmarContrasenia"
                    alt="Mostrar y ocultar contraseña"
                    @click="
                        visibilidadConfirmarContrasenia =
                            !visibilidadConfirmarContrasenia
                    "
                />
            </div>
            <div v-if="errorConfirmarContrasenia" class="errorMensaje">
                {{ errorConfirmarContrasenia }}
            </div>
        </div>

        <div class="form__horizontal flex">
            <div class="flex-column form__campo">
                <label class="label-form" for="localidad"> Localidad * </label>
                <div class="inputForm flex">
                    <select v-model="localidad" required>
                        <option selected disabled value="">
                            Selecciona una localidad
                        </option>
                        <option value="Ciudad Real">Ciudad Real</option>
                        <option value="Toledo">Toledo</option>
                        <option value="Albacete">Albacete</option>
                        <option value="Cuenca">Cuenca</option>
                        <option value="Guadalajara">Guadalajara</option>
                    </select>
                </div>
                <div v-if="errorLocalidad" class="errorMensaje">
                    {{ errorLocalidad }}
                </div>
            </div>

            <div class="flex-column form__campo">
                <label class="label-form" for="telefono"> Teléfono * </label>
                <div class="inputForm flex">
                    <img src="/img/auth/phone.svg" alt="Icono de telefono" />
                    <input
                        v-model="telefono"
                        type="tel"
                        placeholder="+34 000 000 000"
                        required
                    />
                </div>
                <div v-if="errorTelefono" class="errorMensaje">
                    {{ errorTelefono }}
                </div>
            </div>
        </div>
        <div class="mg-tb-1">
            <div class="terminos flex">
                <div class="terminos__switch">
                    <label class="switch">
                        <input
                            v-model="aceptarTerminos"
                            type="checkbox"
                            required
                        />
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
            <div v-if="errorAceptarTerminos" class="errorMensaje">
                {{ errorAceptarTerminos }}
            </div>
        </div>
        <div v-if="generalErrorMessage" class="errorMensaje">
            {{ generalErrorMessage }}
        </div>
        <PrimaryButton label="Registrarse" />

        <p class="p">
            ¿Ya tienes una cuenta?
            <router-link to="/login" class="span"> Inicia sesión </router-link>
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

    gap: 10px;
    border-radius: 20px;

    .form__horizontal {
        gap: 10px;
    }

    .form__horizontal > div:nth-child(1) {
        width: 40%;
    }

    .form__horizontal > div:nth-child(2) {
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
