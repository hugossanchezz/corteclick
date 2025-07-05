import { ref } from "vue";

// Crea una variable reactiva que indica si el usuario está autenticado
// Se basa en si existe un token en sessionStorage
export const isAuthenticated = ref(!!sessionStorage.getItem("token"));
