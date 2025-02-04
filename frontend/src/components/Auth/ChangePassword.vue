<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="px-8 py-6 mt-4 text-left bg-white shadow-lg">
      <h3 class="text-2xl font-bold text-center">Changement de mot de passe</h3>
      <form @submit.prevent="changePassword">
        <div class="mt-4">
          <div>
            <label class="block" for="email">Email</label>
            <input
              v-model="email"
              type="email"
              placeholder="Votre email"
              id="email"
              class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
            />
          </div>
          <div class="mt-4">
            <label class="block" for="currentPassword">Mot de passe actuel</label>
            <div class="relative">
              <input
                v-model="currentPassword"
                :type="showCurrentPassword ? 'text' : 'password'"
                placeholder="Mot de passe actuel"
                id="currentPassword"
                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
              />
              <button
                type="button"
                @click="toggleShowCurrentPassword"
                class="absolute transform -translate-y-1/2 right-2 top-1/2"
              >
                <svg
                  v-if="showCurrentPassword"
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.972 9.972 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a3 3 0 104.243 4.243m-4.243 0a3 3 0 104.243-4.243M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="mt-4">
            <label class="block" for="newPassword">Nouveau mot de passe</label>
            <div class="relative">
              <input
                v-model="newPassword"
                :type="showNewPassword ? 'text' : 'password'"
                placeholder="Nouveau mot de passe"
                id="newPassword"
                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
                @input="checkPasswordStrength"
              />
              <button
                type="button"
                @click="toggleShowNewPassword"
                class="absolute transform -translate-y-1/2 right-2 top-1/2"
              >
                <svg
                  v-if="showNewPassword"
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.972 9.972 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a3 3 0 104.243 4.243m-4.243 0a3 3 0 104.243-4.243M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
              </button>
            </div>
            <div class="mt-2">
              <span :class="passwordStrengthClass">{{ passwordStrengthMessage }}</span>
            </div>
          </div>
          <div class="mt-4">
            <label class="block" for="confirmPassword">Confirmer le nouveau mot de passe</label>
            <div class="relative">
              <input
                v-model="confirmPassword"
                :type="showConfirmPassword ? 'text' : 'password'"
                placeholder="Confirmer le nouveau mot de passe"
                id="confirmPassword"
                class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-600"
              />
              <button
                type="button"
                @click="toggleShowConfirmPassword"
                class="absolute transform -translate-y-1/2 right-2 top-1/2"
              >
                <svg
                  v-if="showConfirmPassword"
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  ></path>
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="w-6 h-6 text-gray-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.972 9.972 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878a3 3 0 104.243 4.243m-4.243 0a3 3 0 104.243-4.243M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="flex items-baseline justify-between">
            <button
              class="px-6 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-900"
            >
              Changer le mot de passe
            </button>
          </div>
        </div>
      </form>
      <div v-if="errorMessage" class="mt-4 text-center text-red-500">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { API_URL } from '@/config';

export default {
  props: {
    temporaryToken: {
      type: String,
      default: null,
    },
  },
  name: "ChangePassword",
  data() {
    return {
      email: "",
      currentPassword: "",
      newPassword: "",
      confirmPassword: "",
      errorMessage: "",
      showCurrentPassword: false,
      showNewPassword: false,
      showConfirmPassword: false,
      passwordStrength: 0,
      passwordStrengthMessage: "",
    };
  },
  computed: {
    passwordStrengthClass() {
      switch (this.passwordStrength) {
        case 1:
          return "text-red-500";
        case 2:
          return "text-yellow-500";
        case 3:
          return "text-green-500";
        default:
          return "";
      }
    },
  },
  methods: {
    toggleShowCurrentPassword() {
      this.showCurrentPassword = !this.showCurrentPassword;
    },
    toggleShowNewPassword() {
      this.showNewPassword = !this.showNewPassword;
    },
    toggleShowConfirmPassword() {
      this.showConfirmPassword = !this.showConfirmPassword;
    },
    checkPasswordStrength() {
      const password = this.newPassword;
      let strength = 0;

      if (password.length >= 8) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[\W_]/.test(password)) strength++;

      this.passwordStrength = strength;
      switch (strength) {
        case 1:
          this.passwordStrengthMessage = "Faible";
          break;
        case 2:
          this.passwordStrengthMessage = "Moyen";
          break;
        case 3:
          this.passwordStrengthMessage = "Fort";
          break;
        default:
          this.passwordStrengthMessage = "";
      }
    },
    async changePassword() {
      if (this.newPassword !== this.confirmPassword) {
        this.errorMessage = "Les nouveaux mots de passe ne correspondent pas.";
        return;
      }

      try {
        let token = this.temporaryToken || localStorage.getItem("token");
        const response = await axios.post(
          `${API_URL}/change-password`,
          {
            email: this.email,
            old_password: this.currentPassword,
            password: this.newPassword,
            password_confirmation: this.confirmPassword,
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
              Accept: "application/json",
            },
          }
        );

        if (response.data.message === "Votre mot de passe a été changé avec succès.") {
          this.$router.push("/login");
          return;
        }

        if (response.data.success) {
          if (response.data.token) {
            localStorage.setItem("token", response.data.token);
          }

          let user = JSON.parse(localStorage.getItem("user")) || {};
          user.firstConnect = "NO";
          localStorage.setItem("user", JSON.stringify(user));

          this.$router.go(user.type === 0 ? "/mycalendar" : "/projet");
        }
      } catch (error) {
        console.error("Erreur complète:", error);
        if (error.response) {
          console.error("Réponse du serveur:", error.response.data);
          if (error.response.data.errors) {
            this.errorMessage = error.response.data.errors.join(", ");
          } else if (error.response.data.message) {
            this.errorMessage = error.response.data.message;
          } else {
            this.errorMessage = "Une erreur s'est produite. Veuillez réessayer.";
          }
        } else {
          this.errorMessage = "Aucune réponse du serveur. Veuillez réessayer plus tard.";
        }
      }
    },
  },
};
</script>
