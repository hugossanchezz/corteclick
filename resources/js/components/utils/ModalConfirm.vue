<script>
export default {
  name: "ModalConfirm",
  props: {
    show: {
      type: Boolean,
      required: true,
      default: false,
    },
    message: {
      type: String,
      required: true,
    },
    confirmText: {
      type: String,
      default: "Confirmar",
    },
    cancelText: {
      type: String,
      default: "Cancelar",
    },
    showCancel: {
      type: Boolean,
      default: true, // Si es false, solo muestra el botón de confirmación (para mensajes de éxito/error)
    },
  },
  methods: {
    onConfirm() {
      this.$emit("confirm");
      this.close();
    },
    onCancel() {
      this.$emit("cancel");
      this.close();
    },
    close() {
      this.$emit("update:show", false); // Actualiza la prop show para cerrar el modal
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