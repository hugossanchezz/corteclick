<script>
export default {
  name: "ModalConfirm",

  // Props que recibe el componente desde el padre
  props: {
    // Controla si el modal se muestra o no
    show: {
      type: Boolean,
      required: true,
      default: false,
    },
    // Mensaje que se mostrará dentro del modal
    message: {
      type: String,
      required: true,
    },
    // Texto del botón de confirmación
    confirmText: {
      type: String,
      default: "Confirmar",
    },
    // Texto del botón de cancelar
    cancelText: {
      type: String,
      default: "Cancelar",
    },
    // Controla si se muestra el botón de cancelar
    showCancel: {
      type: Boolean,
      default: true, // Si es false, se muestra solo el botón de confirmación (ideal para mensajes simples)
    },
  },

  // Métodos del componente
  methods: {
    // Lógica cuando se hace clic en el botón de confirmar
    onConfirm() {
      this.$emit("confirm"); // Emite un evento personalizado 'confirm' al componente padre
      this.close();          // Cierra el modal
    },

    // Lógica cuando se hace clic en el botón de cancelar
    onCancel() {
      this.$emit("cancel"); // Emite un evento personalizado 'cancel' al componente padre
      this.close();         // Cierra el modal
    },

    // Método para cerrar el modal manualmente
    close() {
      // Emite un evento para actualizar la prop `show` a false,
      // esto permite cerrar el modal desde el componente padre usando v-model:show
      this.$emit("update:show", false);
    },
  },
};
</script>

<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <p v-html="message" class="modal-message"></p>
      <div class="modal-buttons flex-center">
        <button v-if="showCancel" class="btn btn-cancel" @click="onCancel">
          {{ cancelText }}
        </button>
        <button class="btn btn-confirm" @click="onConfirm">
          {{ confirmText }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
@use "@/sass/variables" as *;

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: map-get($colores, 'blanco');
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.modal-message {
  @include fuente("parrafo");
  margin-bottom: 20px;
  color: map-get($colores, 'gris_oscuro');

  a{
    color: map-get($colores, 'naranja');
  }
}

.modal-buttons {
  gap: 10px;
}
</style>