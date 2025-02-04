<template>
  <div class="flex h-screen">
    <!-- Design et mise en page -->
    <div class="items-center justify-around hidden w-full lg:flex lg:w-1/2 login_img_section">
      <div class="inset-0 z-0 bg-black opacity-20"></div>
      <div class="flex-col items-center w-full px-20 mx-auto space-y-6">
        <h1 class="font-sans text-4xl font-bold text-white">SBS MANAGER</h1>
        <p class="mt-1 text-white">Bon retour sur votre espace de travail</p>
        <div class="flex justify-center mt-6">
          <router-link to="/" class="px-4 py-2 mt-4 mb-2 font-bold text-indigo-800 transition-none bg-white hover:bg-indigo-700 hover:text-white hover:-translate-y-1 rounded-2xl">
            Retour
          </router-link>
        </div>
      </div>
    </div>

    <!-- Formulaire de connexion -->
    <div class="flex items-center justify-center w-full space-y-8 bg-white lg:w-1/2">
      <div class="w-full px-8 md:px-32 lg:px-24">
        <form @submit.prevent="login" class="p-5 bg-white rounded-md shadow-2xl">
          <h1 class="mb-1 text-2xl font-bold text-gray-800">Salut à nouveau !</h1>
          <p class="mb-8 text-sm font-normal text-gray-600">Bon retour</p>

          <div class="flex items-center px-3 py-2 mb-4 border-2 rounded-2xl">
            <input v-model="email" class="w-full pl-2 border-none outline-none" type="email" placeholder="Adresse email" required />
          </div>

          <div class="flex items-center px-3 py-2 mb-12 border-2 rounded-2xl">
            <input v-model="password" class="w-full pl-2 border-none outline-none" :type="showPassword ? 'text' : 'password'" placeholder="Mot de passe" required />
            <button type="button" @click="togglePasswordVisibility" class="focus:outline-none">
              <svg v-if="showPassword" class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7-3.732 7-9.542 7-8.268-2.943-9.542-7z"></path>
              </svg>
              <svg v-else class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7-3.732 7-9.542 7-8.268-2.943-9.542-7zM12 17a5 5 0 100-10 5 5 0 000 10z"></path>
              </svg>
            </button>
          </div>

          <div v-if="errorMessage" class="text-center text-red-500">{{ errorMessage }}</div>

          <!-- Bouton de connexion avec spinner de chargement -->
          <button class="block w-full py-2 mt-5 mb-2 font-semibold text-white transition-all duration-500 bg-blue-900 rounded-2xl hover:bg-blue-950 hover:-translate-y-1" :disabled="isLoading || isBlocked">
            <span v-if="isLoading" class="loader"></span>
            <span v-else>Connexion</span>
          </button>

          <div class="flex justify-center mt-4">
            <router-link to="/ForgotComponent" class="ml-2 text-sm transition-none cursor-pointer hover:text-blue-500 hover:-translate-y-1">
              Mot de passe oublié ?
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { getWithCache } from '../../api.js';

export default {
  name: 'LoginForm',
  data() {
    return {
      email: '',
      password: '',
      errorMessage: null,
      isLoading: false,
      showPassword: false,
      failedAttempts: 0,
      isBlocked: false
    };
  },
  mounted() {
    this.verifyAuth();
  },
  methods: {
    async login() {
      this.isLoading = true;
      try {
        const response = await getWithCache('/login', {
          method: 'POST',
          data: {
            email: this.email,
            password: this.password
          }
        });

        if (response.data.message === "Veuillez changer votre mot de passe pour continuer.") {
          this.$router.push('/change-password');
        } else {
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('user', JSON.stringify({
            type: response.data.type,
            firstConnect: response.data.firstConnect
          }));
          this.redirectUser(response.data.type);
        }
      } catch (error) {
        console.error('Erreur Axios :', error);

        if (error.response && error.response.data) {
          this.errorMessage = 'Échec de la connexion : ' + error.response.data.message;
        } else if (error.request) {
          this.errorMessage = "Aucune réponse du serveur. Veuillez réessayer plus tard.";
        } else {
          this.errorMessage = "Une erreur s'est produite. Veuillez réessayer.";
        }

        this.failedAttempts++;
        if (this.failedAttempts >= 5) {
          this.isBlocked = true;
          this.errorMessage = "Votre compte est bloqué en raison de trop nombreuses tentatives de connexion échouées.";
        }
      } finally {
        this.isLoading = false;
      }
    },
    verifyAuth() {
      const token = localStorage.getItem('token');
      const user = localStorage.getItem('user');

      if (token && user) {
        const parsedUser = JSON.parse(user);

        console.log('Utilisateur connecté :', parsedUser);

        if (parsedUser && parsedUser.type !== undefined) {
          this.redirectUser(parsedUser.type);
        }
      }
    },
    redirectUser(userType) {
      switch (userType) {
        case 0:
        case 1:
        case 2:
          this.$router.push('/mycalendar');
          break;
        case 3:
          this.$router.push('/');
          break;
        case 4:
        case 5:
          this.$router.push('/projet');
          break;
        default:
          console.log('Type utilisateur non reconnu. Contactez l\'administrateur.');
      }
    },
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    }
  }
};
</script>

<style scoped>
.login_img_section {
  background: linear-gradient(rgba(2, 2, 2, 0.7), rgba(0, 0, 0, 0.7)),
    url(https://images.unsplash.com/photo-1650825556125-060e52d40bd0?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80)
      center center;
}

/* Styles pour le spinner de chargement */
.loader {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid white;
  width: 20px;
  height: 20px;
  -webkit-animation: spin 1s linear infinite;
  animation: spin 1s linear infinite;
  display: inline-block;
  vertical-align: middle;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
