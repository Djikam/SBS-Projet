<template>
  <div>
    <div class="bg-gray-100">
      <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
          <h1 class="mt-8 mb-6 text-2xl font-semibold text-center text-gray-500">Réinitialisation de mot de passe</h1>
          <form @submit.prevent="resetPassword">
            <div class="mb-6">
              <label for="email" class="block mb-2 text-sm text-gray-600">Adresse e-mail</label>
              <input
                v-model="email"
                type="email"
                id="email"
                name="email"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div class="mb-6">
              <label for="password" class="block mb-2 text-sm text-gray-600">Nouveau mot de passe</label>
              <input
                v-model="password"
                type="password"
                id="password"
                name="password"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <div class="mb-6">
              <label for="password_confirmation" class="block mb-2 text-sm text-gray-600">Confirmer le mot de passe</label>
              <input
                v-model="password_confirmation"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            <button
              type="submit"
              class="block w-32 py-2 mx-auto mt-4 mb-4 text-white rounded-lg shadow-sm bg-gradient-to-r from-blue-900 to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 hover:bg-blue-95"
            >
              Réinitialiser
            </button>
          </form>
          <div v-if="errorMessage" class="text-center text-red-500">{{ errorMessage }}</div>
          <div v-if="successMessage" class="text-center text-green-500">{{ successMessage }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: '',
      password_confirmation: '',
      token: '',
      errorMessage: null,
      successMessage: null,
    };
  },
  created() {
    this.token = this.$route.query.token;
    this.email = this.$route.query.email;
  },
  methods: {
    resetPassword() {
      axios.post('http://127.0.0.1:8000/api/password/reset', {
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation,
        token: this.token,
      })
        .then(() => {
          this.successMessage = 'Votre mot de passe a été réinitialisé avec succès.';
          this.errorMessage = null;
        })
        .catch(() => {
          this.errorMessage = 'Échec de la réinitialisation du mot de passe.';
          this.successMessage = null;
        });
    },
  },
};
</script>

<style scoped>
/* Ajoute des styles personnalisés si nécessaire */
</style>
