<script setup>
import { ref, computed, watch, onMounted } from "vue";
import DangerButton from "@/js/components/actions/DangerButton.vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import { useRouter } from "vue-router";

// Props
const props = defineProps({
    datos: {
        type: Object,
        default: () => ({}),
    },
    soloLectura: {
        type: Boolean,
        default: false,
    },
    ocultarContrasenia: {
        type: Boolean,
        default: false,
    },
});


// Emits
const emit = defineEmits(["guardar"]);

// Estado del formulario
const form = ref({
    nombre: "",
    descripcion: "",
    direccion: "",
    localidad: null,
    email: "",
    telefono: "",
    tipo: "",
    contrasenia: "",
});

// Errores
const errores = ref({});

// Visibilidad contraseña
const visibilidadContrasenia = ref(false);
const tipoInputContrasenia = computed(() =>
    visibilidadContrasenia.value ? "text" : "password"
);
const iconoVisibilidadContrasenia = computed(() =>
    visibilidadContrasenia.value ? "/img/auth/visibility_off.svg" : "/img/auth/visibility_on.svg"
);

// Validaciones para la contraseña
const tieneMinuscula = computed(() => /[a-z]/.test(form.value.contrasenia));
const tieneMayuscula = computed(() => /[A-Z]/.test(form.value.contrasenia));
const tieneNumero = computed(() => /\d/.test(form.value.contrasenia));
const tieneEspecial = computed(() => /[!@#$%^&*]/.test(form.value.contrasenia));
const tieneLongitud = computed(() => form.value.contrasenia.length >= 8);

const contraseniaValida = computed(() =>
    tieneMinuscula.value &&
    tieneMayuscula.value &&
    tieneNumero.value &&
    tieneEspecial.value &&
    tieneLongitud.value
);

// Watch para cargar datos
watch(
    () => props.datos,
    (nuevos) => {
        form.value = { ...form.value, ...nuevos };
    },
    { immediate: true }
);

// Enviar
const handleSubmit = () => {
    if (!soloLectura && !ocultarContrasenia && !contraseniaValida.value) {
        errores.value.contrasenia = "La contraseña no cumple con los requisitos.";
        return;
    }
    errores.value = {};
    emit("guardar", { ...form.value });
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="">
        <div class="flex-column form__campo">
            <label>Nombre:</label>
            <div class="inputForm flex">
                <input v-model="form.nombre" :readonly="soloLectura" required />
            </div>
        </div>

        <div class="flex-column form__campo">
            <label>Descripción:</label>
            <div class="inputForm flex">
                <input v-model="form.descripcion" :readonly="soloLectura" />
            </div>
        </div>

        <div class="flex-column form__campo">
            <label>Dirección:</label>
            <div class="inputForm flex">
                <input v-model="form.direccion" :readonly="soloLectura" required />
            </div>
        </div>

        <div class="flex-column form__campo">
            <label>Email:</label>
            <div class="inputForm flex">
                <input v-model="form.email" type="email" :readonly="soloLectura" required />
            </div>
        </div>

        <div class="flex-column form__campo">
            <label>Teléfono:</label>
            <div class="inputForm flex">
                <input v-model="form.telefono" :readonly="soloLectura" />
            </div>
        </div>

        <div class="flex-column form__campo">
            <label>Tipo:</label>
            <div class="inputForm flex">
                <select v-model="form.tipo" :disabled="soloLectura">
                    <option value="">Seleccione</option>
                    <option value="BARBERIA">Barbería</option>
                    <option value="PELUQUERIA">Peluquería</option>
                    <option value="UNISEX">Unisex</option>
                </select>
            </div>
        </div>

        <div v-if="!soloLectura && !ocultarContrasenia" class="flex-column form__campo">
            <label>Contraseña:</label>
            <div class="inputForm flex">
                <input :type="tipoInputContrasenia" v-model="form.contrasenia" />
                <img :src="iconoVisibilidadContrasenia" class="input-visibilidad" alt="Ver y ocultar contraseña"
                    @click="visibilidadContrasenia = !visibilidadContrasenia" />
            </div>
            <p v-if="errores.contrasenia" class="errorMensaje">{{ errores.contrasenia }}</p>

            <!-- Validaciones visuales -->
            <ul v-if="form.contrasenia" style="font-size: 0.9em;">
                <li :style="{ color: tieneMinuscula ? 'green' : 'red' }">Una minúscula</li>
                <li :style="{ color: tieneMayuscula ? 'green' : 'red' }">Una mayúscula</li>
                <li :style="{ color: tieneNumero ? 'green' : 'red' }">Un número</li>
                <li :style="{ color: tieneEspecial ? 'green' : 'red' }">Un carácter especial (!@#$...)</li>
                <li :style="{ color: tieneLongitud ? 'green' : 'red' }">Mínimo 8 caracteres</li>
            </ul>

        </div>

        <PrimaryButton type="submit" v-if="!soloLectura" label="Mandar solicitud" />
    </form>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;
@use "@/sass/common" as *;

* {
    @include fuente("parrafo");
}

form {
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
</style>