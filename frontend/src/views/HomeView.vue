<template>
  <div class="relative flex items-center justify-center h-screen overflow-hidden bg-gradient-to-b from-gray-50 to-gray-100">
    <div class="absolute inset-0">
      <img
        alt="Background Image"
        src="../assets/ban.jpeg"
        class="object-cover object-center w-full h-full transition-transform duration-300 rounded-lg shadow-lg hover:scale-102"
      />
    </div>
    <div class="relative z-10 w-full max-w-screen-lg p-6 mx-auto text-center">
      <!-- Titre de la page -->
      <div class="mb-4">
        <h1 class="text-6xl font-extrabold tracking-wide text-transparent uppercase bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
          Bienvenue sur SBS Manager
        </h1>
      </div>

      <!-- Description -->
      <div class="mb-6">
        <h2 class="text-2xl text-white">
          Votre plateforme de gestion du Staffing des Consultants
        </h2>
      </div>

      <!-- Bouton Commencer avec effets iOS -->
      <div>
        <button
          @click="handleStart"
          class="px-8 py-3 text-2xl font-semibold text-white transition duration-300 ease-in-out transform bg-blue-600 rounded-full shadow-lg hover:bg-blue-700 hover:scale-105 focus:ring-4 focus:ring-blue-300 focus:outline-none"
        >
          Commencer
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { gsap } from "gsap";

export default {
  name: "HomeView",
  methods: {
    handleStart() {
      const token = localStorage.getItem("token"); // Vérifie si l'utilisateur est connecté
      const user = JSON.parse(localStorage.getItem("user")); // Récupère le type de l'utilisateur
      if (token && user) {
        // Redirection basée sur le type d'utilisateur
        switch (user.type) {
          case 0:
          case 1:
          case 2:
            this.$router.push("/mycalendar");
            break;
          case 3:
            this.$router.push("/staff");
            break;
          case 4:
          case 5:
            this.$router.push("/projet");
            break;
          default:
            this.$router.push("/"); // Redirige vers Projet pour les autres types
        }
      } else {
        this.$router.push("/login"); // Redirige vers Login si non connecté
      }
    },
  },
  mounted() {
    // Animation de l'image de fond avec GSAP
    gsap.fromTo(".object-cover", { opacity: 0, scale: 1.1 }, { opacity: 1, scale: 1, duration: 2 });
  },
};
</script>

<style scoped>
/* Amélioration des effets et du design */
body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

h1 {
  font-weight: 900;
  letter-spacing: 1px;
}

button {
  background: linear-gradient(135deg, #4f93f6, #285eeb);
}

img {
  transition: transform 0.3s ease-in-out;
  border-radius: 12px;
}

img:hover {
  transform: scale(1.02);
}

@media (max-width: 768px) {
  h1 {
    font-size: 2.5rem;
  }

  h2 {
    font-size: 1.25rem;
  }

  button {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
  }
}
</style>
