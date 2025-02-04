<template>
  <div>
    <div class="bg-gray-100">
      <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
          <h1 class="mt-8 mb-6 text-2xl font-semibold text-center text-gray-500">Récupération de mot de passe</h1>
          <p class="mt-8 mb-6 text-sm text-center text-gray-600">Entrez votre adresse e-mail pour réinitialiser votre mot de passe</p>
          <form @submit.prevent="sendPasswordResetEmail">
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
            <button
              type="submit"
              class="block w-32 py-2 mx-auto mt-4 mb-4 text-white rounded-lg shadow-sm bg-gradient-to-r from-blue-900 to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 hover:bg-blue-95"
            >
              Envoyer
            </button>
          </form>
          <div v-if="errorMessage" class="text-center text-red-500">{{ errorMessage }}</div>
          <div v-if="successMessage" class="text-center text-green-500">{{ successMessage }}</div>
          <div class="text-center">
            <p>
              <router-link to="/Login" class="text-sm"
                >Retour à la <a href="#" class="text-blue-600">Connexion</a></router-link
              >
            </p>
          </div>
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
      errorMessage: null,
      successMessage: null,
    };
  },
  methods: {
    sendPasswordResetEmail() {
      axios.post('http://127.0.0.1:8000/api/password/email', { email: this.email })
        .then(() => {
          this.successMessage = 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.';
          this.errorMessage = null;
        })
        .catch(() => {
          this.errorMessage = 'Échec de l\'envoi de l\'e-mail de réinitialisation.';
          this.successMessage = null;
        });
    },
  },
};
</script>

<style scoped>
/* Ajoute des styles personnalisés si nécessaire */
</style>
