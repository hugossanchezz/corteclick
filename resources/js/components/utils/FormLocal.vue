<script>
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, computed, watch, onMounted } from "vue";
import PrimaryButton from "@/js/components/actions/PrimaryButton.vue";
import ModalConfirm from "@/js/components/utils/ModalConfirm.vue";

export default {
  name: "FormLocal",

  components: { PrimaryButton, ModalConfirm },

  setup() {
    const router = useRouter();

    // Campos del formulario
    const nombre = ref("");
    const descripcion = ref("");
    const direccion = ref("");
    const localidad = ref("");
    const localidades = ref([]);
    const email = ref("");
    const telefono = ref("");
    const tipo = ref("");
    const user_id = ref("");
    const imagenes = ref([]);
    const inputImagenes = ref(null);
    const errorImagenes = ref("");

    // Errores
    const errores = ref({});
    const generalErrorMessage = ref("");
    const registroExitoso = ref(false);
    const credencialesInvalidas = ref("");
    const isSubmitting = ref(false);

    // Modal
    const showModal = ref(false);
    const modalMessage = ref("");
    const modalAction = ref(null);

    // Patterns de validación
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const telefonoPattern = /^\+?[0-9]{9}$/;


    const tieneErrores = computed(() => Object.keys(errores.value).length > 0);

    const actualizarError = (campo, mensaje) => {
      if (mensaje) {
        errores.value[campo] = mensaje;
      } else {
        delete errores.value[campo];
      }
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

      // Quitar espacios en blanco
      telefono.value = telefono.value.replace(/\s+/g, "").trim();

      if (!telefono.value) {
        actualizarError("telefono", "El teléfono es requerido");
      } else if (!telefonoPattern.test(telefono.value)) {
        actualizarError("telefono", "Teléfono no válido");
      }

      if (!tipo.value) {
        actualizarError("tipo", "El tipo de servicio es requerido");
      }

      if (!user_id.value) {
        actualizarError("user_id", "Debe estar autenticado para registrar un negocio");
      }

      return Object.keys(errores.value).length === 0;
    };

    const manejarSeleccionImagenes = (event) => {
      const archivos = Array.from(event.target.files);
      errorImagenes.value = "";

      if (archivos.length === 0) return;
      if (archivos.length > 4) errorImagenes.value = "Solo se permiten un máximo de 4 imágenes. Se tomarán las primeras 4.";

      imagenes.value = archivos.slice(0, 5);

      for (const archivo of imagenes.value) {
        if (archivo.size > 10 * 1024 * 1024) {
          errorImagenes.value = `La imagen ${archivo.name} supera el límite de 10MB.`;
          imagenes.value = [];
          return;
        }
      }
    };

    // Enviar formulario
    const submitForm = () => {
      generalErrorMessage.value = "";
      credencialesInvalidas.value = "";
      if (validarFormulario()) {
        modalMessage.value = "¿Estás seguro de enviar esta solicitud de registro?";
        modalAction.value = "submit";
        showModal.value = true;
      } else {
        generalErrorMessage.value = "Por favor, corrige los errores en el formulario.";
      }
    };

    // Handle modal confirmation
    const confirmarEnvio = async () => {
      isSubmitting.value = true;
      try {
        const formData = new FormData();
        formData.append("nombre", nombre.value);
        formData.append("descripcion", descripcion.value || 'Peluquería sin descripción.');
        formData.append("direccion", direccion.value);
        formData.append("localidad", localidad.value);
        formData.append("email", email.value);
        formData.append("telefono", telefono.value);
        formData.append("tipo", tipo.value);
        formData.append("user_id", user_id.value);

        if (imagenes.value.length > 0) {
          formData.append("imagen", imagenes.value[0]);
          for (let i = 1; i < imagenes.value.length; i++) {
            formData.append("otras_imagenes[]", imagenes.value[i]);
          }
        }

        const response = await axios.post("/api/new-request", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.status === 200) {
          nombre.value = descripcion.value = direccion.value = localidad.value = email.value = telefono.value = tipo.value = "";
          imagenes.value = [];
          errores.value = {};
          errorImagenes.value = "";
          generalErrorMessage.value = "";
          imagenes.value = [];
          if (inputImagenes.value) {
            inputImagenes.value.value = null;
          }
        }
      } catch (error) {
        if (error.response && error.response.status === 422) {
          const erroresServidor = error.response.data.errors;
          for (const campo in erroresServidor) actualizarError(campo, erroresServidor[campo][0]);
          generalErrorMessage.value = "Error en el registro. Por favor, revisa los campos.";
        } else {
          generalErrorMessage.value = "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
        }
      } finally {
        isSubmitting.value = false;
        showModal.value = false;
      }
    };

    // Cancel modal
    const cancelarEnvio = () => {
      showModal.value = false;
      modalAction.value = null;
    };

    onMounted(async () => {
      cargarLocalidades();

      const storedUser = sessionStorage.getItem("user");
      if (storedUser) {
        try {
          const user = JSON.parse(storedUser);
          user_id.value = user.id;
        } catch (error) {
          console.error("Error al cargar usuario", error);
          sessionStorage.removeItem("user");
          router.push("/");
        }
      }
    });

    return {
      nombre, descripcion, direccion, localidad, localidades, email, telefono, tipo, user_id,
      errores, generalErrorMessage, registroExitoso, credencialesInvalidas,
      tieneErrores, isSubmitting, submitForm, showModal, modalMessage, modalAction,
      confirmarEnvio, cancelarEnvio, manejarSeleccionImagenes, errorImagenes, inputImagenes,
    };
  }
};
</script>

<template>
  <form class="flex-column" @submit.prevent="submitForm">
    <h1 class="flex-center">¿Quieres que tu negocio llegue a más gente?</h1>
    <h3 class="flex-center">Entra en <span class="span__corteclick">Corteclick</span> como empresario con esta
      solicitud. </h3>

    <hr />

    <div class="flex-column form__campo">
      <label for="nombre">Nombre del negocio</label>
      <div class="inputForm flex">
        <img src="/img/auth/person_orange.svg" alt="Icono para input de nombre" />
        <input v-model="nombre" type="text" placeholder="Sin caracteres especiales" required />
      </div>
      <div v-if="errores.nombre" class="errorMensaje">
        {{ errores.nombre }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="descripcion">Descripción (Opcional)</label>
      <div class="inputForm inputForm-textarea flex">
        <img src="/img/utils/edit.svg" alt="Icono para descripción" />
        <textarea v-model="descripcion" placeholder="Describe tu negocio. Max. 200 caracteres" />
      </div>
      <div v-if="errores.descripcion" class="errorMensaje">
        {{ errores.descripcion }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="direccion">Dirección</label>
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
        <label for="localidad">Localidad</label>
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
        <label for="telefono">Teléfono</label>
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
      <label for="email">Correo</label>
      <div class="inputForm flex">
        <img src="/img/auth/at_sign_orange.svg" alt="Icono de correo" />
        <input v-model="email" type="email" placeholder="ejemplo@email.com" required />
      </div>
      <div v-if="errores.email" class="errorMensaje">
        {{ errores.email }}
      </div>
    </div>

    <div class="flex-column form__campo">
      <label for="tipo">Tipo de local</label>
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

    <div class="flex-column form__campo">
      <label for="imagenes">Imágenes del local</label>
      <div class="inputForm flex">
        <img src="/img/auth/image_orange.svg" alt="Imagenes del local" />
        <input ref="inputImagenes" type="file" id="imagenes" multiple
          accept="image/png, image/jpeg, image/jpg, image/webp" @change="manejarSeleccionImagenes" required/>
      </div>
      <div v-if="errorImagenes" class="errorMensaje">
        {{ errorImagenes }}
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

    <!-- Modal de confirmación -->
    <ModalConfirm v-model:show="showModal" :message="modalMessage" :show-cancel="true" @confirm="confirmarEnvio"
      @cancel="cancelarEnvio" />
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