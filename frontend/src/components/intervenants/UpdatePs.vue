<template>
  <div class="flex items-center justify-center min-h-screen p-6 bg-gray-100">
    <div class="container relative max-w-screen-lg mx-auto">
      <!-- Section principale du formulaire -->
      <div class="p-4 px-4 mb-6 bg-white rounded-lg shadow-lg md:p-8">
        <div class="grid items-center justify-center grid-cols-1 gap-4 text-sm gap-y-2 lg:grid-cols-3">
          <div class="text-gray-600">
            <img src="@/assets/sbsN.jpeg" alt="" class="mb-2 rounded-lg" />
            <p class="text-lg font-medium">Modifier un Consultant</p>
            <p class="text-xs">
              S'il vous plaît, remplissez les champs que vous désirez modifier
            </p>
            <div v-if="errorMessage" class="text-red-500">{{ errorMessage }}</div>
          </div>

          <div class="lg:col-span-2">
            <div class="grid grid-cols-1 gap-4 text-sm gap-y-2 md:grid-cols-5">
              <div class="md:col-span-5">
                <label for="name">Noms</label>
                <input
                  type="text"
                  v-model="form.name"
                  id="name"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                  placeholder="Noms"
                />
              </div>

              <div class="md:col-span-5">
                <label for="email">Email Address</label>
                <input
                  type="email"
                  v-model="form.email"
                  id="email"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                  placeholder="email@domain.com"
                />
              </div>

              <div class="md:col-span-5">
                <label for="password">Mot de Passe</label>
                <input
                  type="password"
                  v-model="form.password"
                  id="password"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                  placeholder="Mot de Passe (laisser vide si inchangé)"
                />
              </div>

              <!-- Agency Select Dropdown -->
              <div class="md:col-span-5">
                <label for="agency">Agence</label>
                <select v-model="form.agence_id" id="agency" class="w-full h-10 px-4 mt-1 border rounded bg-gray-50">
                  <option value="">Choisir une agence</option>
                  <option v-for="agence in agences" :key="agence.id" :value="agence.id">
                    {{ agence.name }}
                  </option>
                </select>
              </div>

              <div class="md:col-span-5">
                <label for="type">Rôle de l'utilisateur</label>
                <select
                  v-model="form.type"
                  id="type"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                >
                  <option value="">Choisir</option>
                  <option value="5" v-if="users.type == 5">Administrateur</option>
                  <option value="4">Manager</option>
                  <option value="3">Chef De Projet</option>
                  <option value="0">Consultant Technique</option>
                  <option value="1">Consultant Fonctionnel</option>
                  <option value="2">Consultant FullStack</option>
                </select>
              </div>

              <div class="md:col-span-5">
                <label for="conge">Congés</label>
                <input
                  type="number"
                  v-model="form.conge"
                  id="conge"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                  placeholder="Congés"
                />
              </div>

              <div class="md:col-span-5">
                <label for="recuperation">Récupération</label>
                <input
                  type="number"
                  v-model="form.recuperation"
                  id="recuperation"
                  class="w-full h-10 px-4 mt-1 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
                  placeholder="Récupération"
                />
              </div>
            </div>

            <div class="mt-4 text-center md:col-span-5">
              <div class="inline-flex items-center justify-between space-x-6">
                <button
                  @click="toggleCompetences"
                  v-if="!showCompetences"
                  class="px-4 py-2 font-bold text-white bg-blue-500 rounded left-6 hover:bg-blue-700"
                >
                  Voir Compétences
                </button>
                <button
                  @click="toggleCompetences"
                  v-if="showCompetences"
                  class="px-4 py-2 font-bold text-white bg-blue-500 rounded left-6 hover:bg-blue-700"
                >
                  Fermer Compétences
                </button>
                <router-link to="/listPs">
                  <button
                    class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
                  >
                    Retour
                  </button>
                </router-link>

                <button
                  @click="updateUser"
                  class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
                >
                  Enregistrer
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal pour afficher les compétences -->
      <transition name="modal">
        <div
          v-if="showCompetences"
          class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 centered-modal"
        >
          <div class="w-full max-w-4xl p-6 bg-white rounded shadow-lg">
            <h3 class="mb-4 text-lg font-bold text-center">Compétences</h3>
            <table class="w-full table-auto">
              <thead>
                <tr>
                  <th class="px-4 py-2">Domaine</th>
                  <th class="px-4 py-2">Module</th>
                  <th class="px-4 py-2">Niveau</th>
                  <th class="px-4 py-2">Supprimer</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="competence in user.domaines_competence" :key="competence.user_domaine_competence_id">
                  <td class="px-4 py-2 border">{{ competence.domaine_nom }}</td>
                  <td class="px-4 py-2 border">{{ competence.module_nom }}</td>
                  <td class="px-4 py-2 border">{{ competence.niveau_competence }}</td>
                  <td class="px-4 py-2 border">
                    <button
                      @click="deleteCompetence(competence.user_domaine_competence_id)"
                      class="text-xs font-semibold text-red-600 hover:text-red-800"
                    >
                      Supprimer
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="flex items-center justify-between mt-4 text-center">
              <button
                @click="toggleCompetences"
                class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
              >
                Fermer
              </button>
              <button
                @click="showAddCompetenceModal = true"
                class="px-4 py-2 mt-4 text-white transition duration-200 bg-green-500 rounded hover:bg-green-700"
              >
                Ajouter Compétence
              </button>
            </div>
          </div>
        </div>
      </transition>

      <!-- Modal pour ajouter une compétence -->
      <transition name="modal">
        <div
          v-if="showAddCompetenceModal"
          class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
          <div class="p-4 bg-white rounded shadow-lg w-96">
            <h2 class="text-lg font-bold">Ajouter une Compétence</h2>
            <select
              v-model="newCompetence.domaine_id"
              class="w-full h-10 mt-2 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
              @change="updateModules"
            >
              <option disabled selected>Choisissez un domaine</option>
              <option
                v-for="domaine in domaines"
                :key="domaine.id"
                :value="domaine.id"
              >
                {{ domaine.nom }}
              </option>
            </select>
            <select
              v-model="newCompetence.module_id"
              class="w-full h-10 mt-2 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
            >
              <option disabled selected>Choisissez un module</option>
              <option
                v-for="module in selectedModules"
                :key="module.id"
                :value="module.id"
              >
                {{ module.nom }}
              </option>
            </select>
            <select
              v-model="newCompetence.niveau_competence_id"
              class="w-full h-10 mt-2 border rounded bg-gray-50 focus:ring focus:ring-blue-300"
            >
              <option disabled selected>Choisissez un niveau</option>
              <option
                v-for="niveau in niveaux"
                :key="niveau.id"
                :value="niveau.id"
              >
                {{ niveau.niveau }}
              </option>
            </select>
            <div class="flex items-center justify-between space-x-4">
              <button
                @click="addCompetence"
                class="px-4 py-2 mt-4 text-white transition duration-200 bg-blue-500 rounded hover:bg-blue-700"
              >
                Ajouter
              </button>
              <button
                @click="showAddCompetenceModal = false"
                class="px-4 py-2 mt-4 text-black transition duration-200 border rounded hover:bg-gray-300"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/config'; // Assurez-vous que le chemin est correct

export default {
  data() {
    return {
      id: this.$route.params.id,
      form: {
        name: '',
        email: '',
        password: '',
        type: null,
        agence_id: null,
        conge: null,
        recuperation: null,
        domaines: []
      },
      agences: [],
      domaines: [],
      niveaux: [],
      showCompetences: false,
      showAddCompetenceModal: false,
      newCompetence: {
        domaine_id: null,
        module_id: null,
        niveau_competence_id: null,
      },
      selectedModules: [],
      errorMessage: '',
      user: '',
      users: [],
    };
  },
  mounted() {
    this.fetchDomaines();
    this.fetchUserData();
    this.fetchAgencies();
    this.fetchUser();
    this.fetchNiveaux();
  },
  methods: {
    async fetchUser() {
      try {
        const response = await axios.get(`${API_URL}/user`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.users = response.data;
        localStorage.setItem('userType', this.user.type);
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    },
    fetchAgencies() {
      axios.get(`${API_URL}/agences`, {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        // Ensure the API returns 'agences' and populate agencies
        this.agences = response.data.agences || [];
      })
      .catch(error => {
        console.error('Error fetching agencies:', error);
      });
    },
    async fetchDomaines() {
      try {
        const response = await axios.get(`${API_URL}/domaines-competence`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.domaines = response.data;
      } catch (error) {
        console.error('Erreur lors du chargement des domaines:', error);
      }
    },
    async fetchNiveaux() {
      try {
        const response = await axios.get(`${API_URL}/niveaux-competence`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.niveaux = response.data;
      } catch (error) {
        console.error('Erreur lors du chargement des niveaux:', error);
      }
    },
    async fetchUserData() {
      try {
        const response = await axios.get(`${API_URL}/users/${this.id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.form = { ...response.data };
        this.user = response.data;
        this.errorMessage = '';
      } catch (error) {
        console.error('Erreur lors de la récupération des données de l\'utilisateur:', error);
        this.errorMessage = 'Utilisateur non trouvé. Veuillez vérifier l\'ID.';
      }
    },
    async addCompetence() {
      try {
        // Send a POST request to the server to add the new competence
        await axios.post(
          `${API_URL}/users/${this.user.id}/competence`,
          {
            // Send the selected domain ID, module ID, and level ID in the request body
            domaine_competence_id: this.newCompetence.domaine_id,
            module_id: this.newCompetence.module_id,
            niveau_competence_id: this.newCompetence.niveau_competence_id,
          },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        // If the competence is added successfully, update the local user object
        this.user.domaines_competence.push({
          domaine_nom: this.domaines.find(d => d.id === this.newCompetence.domaine_id).nom,
          module_nom: this.selectedModules.find(m => m.id === this.newCompetence.module_id).nom,
          niveau_competence: this.niveaux.find(n => n.id === this.newCompetence.niveau_competence_id).niveau,
          user_domaine_competence_id: this.newCompetence.domaine_id,
        });

        // Close the modal and reset the selected domain, module, and level
        this.showAddCompetenceModal = false;
        this.newCompetence.domaine_id = null;
        this.newCompetence.module_id = null;
        this.newCompetence.niveau_competence_id = null;
      } catch (error) {
        // If there's an error, log it to the console
        console.error("Erreur lors de l'ajout de la compétence:", error);
      }
    },
    async deleteCompetence(userDomaineCompetenceId) {
      try {
        await axios.delete(
          `${API_URL}/user-domaine-competence/${userDomaineCompetenceId}`,
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        this.user.domaines_competence = this.user.domaines_competence.filter(
          (competence) =>
            competence.user_domaine_competence_id !== userDomaineCompetenceId
        );
      } catch (error) {
        console.error("Erreur lors de la suppression de la compétence:", error);
      }
    },
    async updateUser() {
      try {
        // Préparer les domaines et modules pour l'envoi
        this.form.domaines = this.user.domaines_competence.map(competence => ({
          domaine_competence_id: competence.domaine_id,
          module_id: competence.module_id,
          niveau_competence_id: competence.niveau_competence.id
        }));

        // 1. Enregistrer l'utilisateur
        const userResponse = await axios.post(`${API_URL}/users/${this.id}`, this.form, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          }
        });

        // Vérifiez si l'utilisateur est bien enregistré
        if (userResponse.status === 200) {
          // Rediriger après succès
          this.$router.push('/listPs');
        }
      } catch (error) {
        console.error('Erreur lors de la mise à jour de l\'utilisateur:', error.response?.data?.errors || error.message);
      }
    },
    toggleCompetences() {
      this.showCompetences = !this.showCompetences;
    },
    updateModules() {
      const domaine = this.domaines.find(d => d.id === this.newCompetence.domaine_id);
      if (domaine) {
        this.selectedModules = domaine.modules;
      } else {
        this.selectedModules = [];
      }
    }
  },
};
</script>

<style scoped>
/* Ajoutez vos styles ici */
.slide-fade-enter-active {
  transition: all 0.8s ease;
}
.slide-fade-leave-active {
  transition: all 0.5s ease;
}
.slide-fade-enter, .slide-fade-leave-to /* .slide-fade-leave-active in <2.1.8 */ {
  transform: translateX(100%);
  opacity: 0;
}
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.5s;
}
.modal-enter, .modal-leave-to /* .modal-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
