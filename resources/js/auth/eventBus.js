// src/js/eventBus.js
import { ref } from "vue";

export const isAuthenticated = ref(!!localStorage.getItem("token"));
