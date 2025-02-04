<template>
  <div>
    <div class="relative">
      <!-- Navbar fixée en haut avec effet iOS -->
      <nav
        class="fixed top-0 z-50 flex items-center justify-between w-full h-20 text-black transition-all duration-300 ease-in-out bg-white shadow-lg"
      >
        <router-link to="/">
          <img src="../assets/sbsN.jpeg" alt="sbs-manager" class="w-40 h-20 ml-4" />
        </router-link>
        <div class="hidden mr-8 space-x-4 md:flex">
          <router-link
            to="/"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Accueil</router-link
          >
          <router-link
            v-if="userType === 3"
            to="/staff"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Staffing</router-link
          >
          <router-link
            v-if="userType === 4 || userType === 5"
            to="/projet"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Staffing</router-link
          >
          <button
            @click="toggleServices"
            v-if="userType === 4 || userType === 5"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
          >
            Services
          </button>
          <router-link
            v-if="userType === 3"
            to="/demande"
            class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900"
            >Demandes</router-link
          >
          <router-link v-if="userType === 3" to="/listPsCP"
           class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900" >
                Consultants
            </router-link>
          <router-link
            v-if="userType === 5"
            to="/login-audit"
            class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900"
            >Audits</router-link
          >
          <router-link
            v-if="userType === 5"
            to="/demandes"
            class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900"
            >Demandes</router-link
          >
          <button
            @click="toggleCompetences"
            v-if="userType === 4 || userType === 5"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
          >
            Compétences
          </button>
          <router-link
            v-if="userType === 0 || userType === 1 || userType === 2 || userType === 3"
            to="/mycalendar"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >MyCalendar</router-link
          >
          <router-link
            v-if="auth"
            to="/myprofile"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Mon Profil</router-link
          >
          <router-link
            v-if="!auth"
            to="/login"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Connexion</router-link
          >
          <router-link
            v-if="auth"
            to="/"
            @click="logout"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Déconnexion</router-link
          >
        </div>
        <div class="md:hidden">
          <button @click="toggleMobileMenu" class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </nav>

      <!-- Mobile Menu -->
      <div v-show="mobileMenuOpen" class="fixed inset-0 z-40 transition-transform duration-500 ease-in-out transform bg-white shadow-lg bg-opacity-90 backdrop-blur-md" :class="{ 'translate-x-full': !mobileMenuOpen }">
        <div class="flex flex-col p-4 space-y-4 rounded-xl">
          <router-link
            to="/"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Accueil</router-link
          >
          <router-link
            v-if="userType === 3"
            to="/staff"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Staffing</router-link
          >
          <router-link
            v-if="userType === 4 || userType === 5"
            to="/projet"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Staffing</router-link
          >
          <router-link to="/listAgence" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Agences
            </button>
          </router-link>
          <router-link to="/listPs" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Consultants
            </button>
          </router-link>
          <router-link to="/clients" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Missions
            </button>
          </router-link>
          <router-link to="/ferie" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Fériés
            </button>
          </router-link>
          <router-link
            v-if="userType === 3"
            to="/demande"
            class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900"
            >Demandes</router-link
          >
          <router-link v-if="userType === 3" to="/listPsCP"
           class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900" >
                Consultants
            </router-link>
          <router-link
            v-if="userType === 5"
            to="/demandes"
            class="p-2 text-base font-medium capitalize transition duration-200 hover:text-blue-900"
            >Demandes</router-link
          >
          <router-link to="/domain" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Domaines de Compétences
            </button>
          </router-link>
          <router-link to="/niveau" v-if="userType === 4 || userType === 5">
            <button class="p-2 text-base font-medium transition duration-200 hover:text-blue-900">
              Niveau de Compétences
            </button>
          </router-link>
          <router-link
            v-if="userType === 0 || userType === 1 || userType === 2 || userType === 3"
            to="/mycalendar"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >MyCalendar</router-link
          >
          <router-link
            v-if="auth"
            to="/myprofile"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Mon Profil</router-link
          >
          <router-link
            v-if="!auth"
            to="/login"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Connexion</router-link
          >
          <router-link
            v-if="auth"
            to="/"
            @click="logout"
            class="p-2 text-base font-medium transition duration-200 hover:text-blue-900"
            >Déconnexion</router-link
          >
        </div>
      </div>

      <!-- Modal Compétences -->
      <div v-show="over" class="absolute z-50 right-[290px] mt-20">
        <div class="w-auto h-auto p-2 space-y-4 text-xs bg-white shadow-lg rounded-b-3xl backdrop-blur-md">
          <div>
            <router-link to="/domain">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Domaines
              </button>
            </router-link>
          </div>
          <div>
            <router-link to="/niveau">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Niveau
              </button>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Modal Services -->
      <div v-show="servicesOpen" class="absolute z-50 right-[470px] mt-20">
        <div class="grid w-auto h-auto grid-cols-2 gap-2 p-2 text-[9px] bg-white shadow-lg rounded-b-3xl backdrop-blur-md">
          <div>
            <router-link to="/listAgence">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Agences
              </button>
            </router-link>
          </div>
          <div>
            <router-link to="/listPs">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Consultants
              </button>
            </router-link>
          </div>
          <div>
            <router-link to="/clients">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Missions
              </button>
            </router-link>
          </div>
          <div>
            <router-link to="/ferie">
              <button
                class="w-24 p-2 font-bold text-black capitalize bg-blue-100 rounded hover:bg-slate-300"
              >
                Fériés
              </button>
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Vue spécifique selon la route -->
    <div class="h-20"></div>
    <keep-alive>
      <router-view name="one"></router-view>
    </keep-alive>
  </div>
</template>

<script>
import { getWithCache } from '@/api';

export default {
  name: "NavView",
  data() {
    return {
      userType: null,
      auth: false,
      over: false,
      servicesOpen: false,
      mobileMenuOpen: false,
    };
  },
  mounted() {
    this.getUserInfo();
  },
  methods: {
    async getUserInfo() {
      try {
        const response = await getWithCache("/me", {
          method: 'GET',
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.userType = response.data.type;
        this.auth = true;
      } catch (error) {
        console.error(
          "Erreur lors de la récupération des informations utilisateur",
          error
        );
        this.auth = false;
      }
    },
    logout() {
      localStorage.removeItem("token");
      this.auth = false;
      this.userType = null;
      this.$router.push("/login");
    },
    toggleCompetences() {
      this.over = !this.over;
    },
    toggleServices() {
      this.servicesOpen = !this.servicesOpen;
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
    },
  },
  watch: {
    $route() {
      if (this.over) {
        this.over = false;
      }
      if (this.servicesOpen) {
        this.servicesOpen = false;
      }
      if (this.mobileMenuOpen) {
        this.mobileMenuOpen = false;
      }
    },
  },
};
</script>

<style scoped>
/* Styles pour un look professionnel avec effets iOS */
nav {
  background-color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}
nav:hover {
  background-color: #f9f9f9;
  box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}
button {
  transition: transform 0.2s ease-in-out;
}
button:hover {
  transform: scale(1.05);
}
</style>
