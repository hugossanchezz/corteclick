<script>
import axios from "axios";
import { useRouter } from "vue-router";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import { ref, computed, watch, onMounted } from "vue";

export default {
  name: "FormLocal",

  components: { PrimaryButton },

  setup() {
    const router = useRouter();

    // Form fields
    const nombre = ref("");
    const descripcion = ref("");
    const direccion = ref("");
    const localidad = ref("");
    const localidades = ref([]);
    const email = ref("");
    const telefono = ref("");
    const tipo = ref("");
    const contrasenia = ref("");
    const confirmarContrasenia = ref("");
    const user_id = ref("");

    // Error handling
    const errores = ref({});
    const generalErrorMessage = ref("");
    const registroExitoso = ref(false);
    const credencialesInvalidas = ref("");
    const isSubmitting = ref(false);

    // Password visibility
    const visibilidadContrasenia = ref(false);
    const visibilidadConfirmarContrasenia = ref(false);

    // Validation patterns
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const telefonoPattern = /^\+?[0-9]{9}$/;

    // Password validation computed properties
    const tieneMinuscula = computed(() => /[a-z]/.test(contrasenia.value));
    const tieneMayuscula = computed(() => /[A-Z]/.test(contrasenia.value));
    const tieneNumero = computed(() => /\d/.test(contrasenia.value));
    const tieneCaracterEspecial = computed(() => /[!@#$%^&*]/.test(contrasenia.value));
    const tieneLongitudMinima = computed(() => contrasenia.value.length >= 8);
    const contraseniaValida = computed(() => {
      return (
        tieneMinuscula.value &&
        tieneMayuscula.value &&
        tieneNumero.value &&
        tieneCaracterEspecial.value &&
        tieneLongitudMinima.value
      );
    });

    const tieneErrores = computed(() => Object.keys(errores.value).length > 0);

    // Password visibility icons and input types
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

    // Update error function
    const actualizarError = (campo, mensaje) => {
      if (mensaje) {
        errores.value[campo] = mensaje;
      } else {
        delete errores.value[campo];
      }
    };

    // Fetch localities and user_id
    onMounted(async () => {
      cargarLocalidades();

      const storedUser = localStorage.getItem("user");
      if (storedUser) {
        try {
          const user = JSON.parse(storedUser);
          user_id.value = user.id;
        } catch (error) {
          console.error("Error al cargar usuario", error);
          localStorage.removeItem("user");
          router.push("/");
        }
      }
    });

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

    // Manual validation function
    const validarFormulario = () => {
      errores.value = {};

      if (!nombre.value) {
        actualizarError("nombre", "El nombre del negocio es requerido");
      } else if (/[^a-zA-Z0-9\s]/.test(nombre.value)) {
        actualizarError("nombre", "No se permiten caracteres especiales");
      }

      if (descripcion.value && descripcion.value.length > 200) {
        actualizarError("descripcion", "La descripción no puede exceder 200 caracteres");
      }

      if (!direccion.value) {
        actualizarError("direccion", "La dirección es requerida");
      }

      if (!localidad.value) {
        actualizarError("localidad", "La localidad es requerida");
      }

      if (!email.value) {
        actualizarError("email", "El correo es requerido");
      } else if (!emailPattern.test(email.value)) {
        actualizarError("email", "Correo no válido");
      }

      if (!telefono.value) {
        actualizarError("telefono", "El teléfono es requerido");
      } else if (!telefonoPattern.test(telefono.value)) {
        actualizarError("telefono", "Teléfono no válido");
      }

      if (!tipo.value) {
        actualizarError("tipo", "El tipo de servicio es requerido");
      }

      if (!contrasenia.value) {
        actualizarError("contrasenia", "La contraseña es requerida");
      } else if (!contraseniaValida.value) {
        actualizarError("contrasenia", "La contraseña no cumple con los requisitos");
      }

      if (confirmarContrasenia.value !== contrasenia.value) {
        actualizarError("confirmarContrasenia", "Las contraseñas no coinciden");
      }

      if (!user_id.value) {
        actualizarError("user_id", "Debe estar autenticado para registrar un negocio");
      }

      return Object.keys(errores.value).length === 0;
    };

    // Remove watchers and rely on manual validation
    // If you still want real-time feedback, you can keep the watchers, but ensure they don't interfere during submission

    // Form submission
    const submitForm = async () => {
      generalErrorMessage.value = "";
      credencialesInvalidas.value = "";
      isSubmitting.value = true;

      // Validate manually
      if (!validarFormulario()) {
        generalErrorMessage.value = "Por favor, corrige los errores en el formulario.";
        isSubmitting.value = false;
        return;
      }

      try {
        const response = await axios.post("/api/new-local", {
          nombre: nombre.value,
          descripcion: descripcion.value || 'Peluquería sin descripción.',
          direccion: direccion.value,
          localidad: localidad.value,
          email: email.value,
          telefono: telefono.value,
          tipo: tipo.value,
          contrasenia: contrasenia.value,
          user_id: user_id.value
        });
        if (response.status === 200) {
          alert("La solicitud de registro ha sido enviada exitosamente.");
          
          // Reset form fields without triggering validation
          nombre.value = "";
          descripcion.value = "";
          direccion.value = "";
          localidad.value = "";
          email.value = "";
          telefono.value = "";
          tipo.value = "";
          contrasenia.value = "";
          confirmarContrasenia.value = "";
          errores.value = {};
          generalErrorMessage.value = "";
        }
      } catch (error) {
        console.error("Error de registro", error.response);
        if (error.response && error.response.status === 422) {
          const erroresServidor = error.response.data.errors;
          errores.value = {};
          for (const campo in erroresServidor) {
            actualizarError(campo, erroresServidor[campo][0]);
          }
          generalErrorMessage.value = "Error en el registro. Por favor, revisa los campos.";
        } else {
          generalErrorMessage.value = "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
        }
      } finally {
        isSubmitting.value = false;
      }
    };

    return {
      nombre,
      descripcion,
      direccion,
      localidad,
      localidades,
      email,
      telefono,
      tipo,
      contrasenia,
      confirmarContrasenia,
      user_id,
      errores,
      generalErrorMessage,
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
      credencialesInvalidas,
      tieneErrores,
      isSubmitting,
      submitForm
    };
  }
};
</script>

<template>
  <form class="flex-column" @submit.prevent="submitForm">
    <h1 class="flex-center">¿Quieres que tu negocio llegue a más gente?</h1>
    <h3 class="flex-center">Entra en <span class="span__corteclick">Corteclick</span> como empresario con esta solicitud. </h3>

    <hr />

    <div class="flex-column form__campo">
      <label for="nombre">Nombre del negocio *</label>
      <div class="inputForm flex">
        <img src="/img/auth/person_orange.svg" alt="Icono para input de nombre" />
        <input v-model="nombre" type="text" placeholder="Sin caracteres especiales" required />
      </div>
      <div v-if="errores.nombre" class="errorMensaje">
        {{ errores.nombre }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="descripcion">Descripción</label>
      <div class="inputForm inputForm-textarea flex">
        <img src="/img/utils/edit.svg" alt="Icono para descripción" />
        <textarea v-model="descripcion" placeholder="Describe tu negocio. Max. 200 caracteres" />
      </div>
      <div v-if="errores.descripcion" class="errorMensaje">
        {{ errores.descripcion }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="direccion">Dirección *</label>
      <div class="inputForm flex">
        <img src="/img/locals/location.svg" alt="Icono para dirección" />
        <input v-model="direccion" type="text" placeholder="Calle, número, etc." required />
      </div>
      <div v-if="errores.direccion" class="errorMensaje">
        {{ errores.direccion }}
      </div>
    </div>

    <div class="form__horizontal flex">
      <div class="flex-column form__campo">
        <label for="localidad">Localidad *</label>
        <div class="inputForm flex">
          <select v-model="localidad" required>
            <option selected disabled value="">Selecciona una localidad</option>
            <option v-for="localidad in localidades" :key="localidad.id" :value="localidad.id">
              {{ localidad.nombre }} - {{ localidad.codigo_postal }}
            </option>
          </select>
        </div>
        <div v-if="errores.localidad" class="errorMensaje">
          {{ errores.localidad }}
        </div>
      </div>

      <div class="flex-column form__campo">
        <label for="telefono">Teléfono *</label>
        <div class="inputForm flex">
          <img src="/img/auth/phone_orange.svg" alt="Icono de teléfono" />
          <input v-model="telefono" type="tel" placeholder="000 000 000" required />
        </div>
        <div v-if="errores.telefono" class="errorMensaje">
          {{ errores.telefono }}
        </div>
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="email">Correo *</label>
      <div class="inputForm flex">
        <img src="/img/auth/at_sign_orange.svg" alt="Icono de correo" />
        <input v-model="email" type="email" placeholder="ejemplo@email.com" required />
      </div>
      <div v-if="errores.email" class="errorMensaje">
        {{ errores.email }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="tipo">Tipo de servicio *</label>
      <div class="inputForm flex">
        <select v-model="tipo" required>
          <option selected disabled value="">Selecciona un tipo</option>
          <option value="UNISEX">Unisex</option>
          <option value="BARBERIA">Barbería para hombres</option>
          <option value="PELUQUERIA">Peluquería para mujeres</option>
        </select>
      </div>
      <div v-if="errores.tipo" class="errorMensaje">
        {{ errores.tipo }}
      </div>
    </div>

    <div class="form__horizontal flex">

      <div class="flex-column form__campo">
        <label for="contrasenia">Contraseña *</label>
        <div class="inputForm flex">
          <img src="/img/auth/lock_orange.svg" alt="Icono de contraseña" />
          <input v-model="contrasenia" :type="tipoInputContrasenia" placeholder="Contraseña" required />
          <img class="input-visibilidad" :src="iconoVisibilidadContrasenia" alt="Mostrar y ocultar contraseña"
            @click="visibilidadContrasenia = !visibilidadContrasenia" />
        </div>
        <ul v-if="contrasenia.length" class="errorMensaje">
          <li :class="{ correcto: tieneMinuscula }">Debe tener al menos una letra minúscula</li>
          <li :class="{ correcto: tieneMayuscula }">Debe tener al menos una letra mayúscula</li>
          <li :class="{ correcto: tieneNumero }">Debe tener al menos un número</li>
          <li :class="{ correcto: tieneCaracterEspecial }">Debe tener al menos un carácter especial (!@#$%^&*)</li>
          <li :class="{ correcto: tieneLongitudMinima }">Debe tener al menos 8 caracteres</li>
        </ul>
        <div v-if="errores.contrasenia" class="errorMensaje">
          {{ errores.contrasenia }}
        </div>
      </div>

      <div class="flex-column form__campo">
        <label for="confirmarContrasenia">Confirma tu contraseña *</label>
        <div class="inputForm flex">
          <img src="/img/auth/lock_orange.svg" alt="Icono de contraseña" />
          <input v-model="confirmarContrasenia" :type="tipoInputConfirmarContrasenia"
            placeholder="Confirma tu contraseña" required />
          <img class="input-visibilidad" :src="iconoVisibilidadConfirmarContrasenia" alt="Mostrar y ocultar contraseña"
            @click="visibilidadConfirmarContrasenia = !visibilidadConfirmarContrasenia" />
        </div>
        <div v-if="errores.confirmarContrasenia" class="errorMensaje">
          {{ errores.confirmarContrasenia }}
        </div>
      </div>
    </div>


    <div v-if="errores.user_id" class="errorMensaje">
      {{ errores.user_id }}
    </div>
    <div v-if="generalErrorMessage" class="errorMensaje">
      {{ generalErrorMessage }}
    </div>

    <div class="form__bottom flex-column mg-tb-1">
      <PrimaryButton label="Enviar mi solicitud" />
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
  width: 100%;
  padding: 20px 30px;
  gap: 10px;
  border-radius: 20px;

  h3 {
    color: map-get($colores, 'gris_oscuro');
    align-items: end !important;

    span {
      margin-left: 5px;
      margin-right: 7.5px !important;
    }
  }

  hr {
    background-color: map-get($colores, 'gris_claro');
    height: 2px;
    border: none;
  }

  .form__horizontal {
    gap: 10px;

    >div {
      width: 50%;
    }
  }

  .form__campo {
    gap: 2px;

    .inputForm {
      width: 100%;
      height: 46px;
    }

    .inputForm-textarea {
      height: 100px;
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
  }

  .form__bottom {
    align-items: center;
    gap: 1rem;
  }

  label {
    margin-left: 5px;
  }
}

.span {
  color: map-get($colores, "naranja");
  font-weight: 500;
  cursor: pointer;
}

.errorMensaje {
  color: map-get($colores, "rojo");
  width: 100%;
}

.correcto {
  color: map-get($colores, "verde");
}

.input-visibilidad {
  cursor: pointer;
}

@include responsive-layout(1440px) {
  .contenedor-inicio-sesion {
    width: 80%;
  }

  .form-container {
    margin-bottom: 1rem;
  }
}

@include responsive-layout(1024px) {
  form {
    width: 100%;
    padding: 0;
  }

  .input {
    margin-left: 0;
  }
}
</style>