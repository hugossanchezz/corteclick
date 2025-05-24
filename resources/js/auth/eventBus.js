// src/js/eventBus.js
import { ref } from "vue";

export const isAuthenticated = ref(!!sessionStorage.getItem("token"));
