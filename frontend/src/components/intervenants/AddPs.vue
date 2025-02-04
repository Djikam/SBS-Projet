<template>
  <div>
    <div class="flex items-center justify-center min-h-screen p-6 bg-gray-100">
      <div class="container max-w-screen-lg mx-auto">
        <div class="p-4 px-4 mb-6 bg-white rounded shadow-lg md:p-8">
          <div class="grid items-center justify-center grid-cols-1 gap-4 text-sm gap-y-2 lg:grid-cols-3">
            <div class="text-gray-600">
              <img src="@/assets/sbsN.jpeg" alt="">
              <p class="text-lg font-medium">Ajouter un Consultant</p>
              <p class="text-xs">S'il vous plaît remplissez tous les champs</p>
            </div>

            <div class="lg:col-span-2">
              <div class="grid grid-cols-1 gap-4 text-sm gap-y-2 md:grid-cols-5">
                <div class="md:col-span-5">
                  <label for="name">Noms</label>
                  <input v-model="form.name" type="text" id="name" class="w-full h-10 px-4 mt-1 border rounded bg-gray-50" placeholder="noms" />
                </div>

                <div class="md:col-span-5">
                  <label for="email">Email Address</label>
                  <input v-model="form.email" type="email" id="email" class="w-full h-10 px-4 mt-1 border rounded bg-gray-50" placeholder="email@domain.com" />
                </div>

                <div class="md:col-span-5">
                  <label for="password">Mot de Passe</label>
                  <div class="relative">
                    <input
                      v-model="form.password"
                      :type="passwordVisible ? 'text' : 'password'"
                      id="password"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      placeholder="password"
                      readonly
                    />
                    <button @click="togglePasswordVisibility" type="button" class="absolute transform -translate-y-1/2 right-3 top-1/2">
                      <span v-if="passwordVisible">👁️</span>
                      <span v-else>👁️‍🗨️</span>
                    </button>
                  </div>
                  <button @click="generatePassword" class="px-4 py-2 mt-2 text-gray-800 bg-gray-300 rounded">Générer Mot de Passe</button>
                </div>

                <!-- Agency Select Dropdown -->
                <div class="md:col-span-2">
                  <label for="agency">Agence</label>
                  <select v-model="form.agence_id" id="agency" class="w-full h-10 px-4 mt-1 border rounded bg-gray-50">
                    <option value="">Choisir une agence</option>
                    <option v-for="agence in agences" :key="agence.id" :value="agence.id">
                      {{ agence.name }}
                    </option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label for="type">Rôle de l'utilisateur</label>
                  <select v-model="form.type" id="type" class="w-full h-10 px-4 mt-1 border rounded bg-gray-50">
                    <option value="">Choisir</option>
                    <option value="5" v-if="users.type == 5">Administrateur</option>
                    <option value="4">Manager</option>
                    <option value="3">Chef De Projet</option>
                    <option value="0">Consultant Technique</option>
                    <option value="1">Consultant Fonctionnel</option>
                    <option value="2">Consultant FullStack</option>
                  </select>
                </div>

                <div class="md:col-span-2">
                  <label>Domaine de Compétence</label>
                  <div class="p-2 overflow-y-auto border rounded h-30 bg-gray-50">
                    <div v-for="domaine in domaines" :key="domaine.id" class="flex items-center mb-2">
                      <input
                        type="checkbox"
                        :id="'domaine_' + domaine.id"
                        :value="domaine.id"
                        v-model="selectedDomaines"
                        class="checkbox"
                        @change="updateModules(domaine.id)"
                      />
                      <label :for="'domaine_' + domaine.id" class="ml-2">{{ domaine.nom }}</label>
                    </div>
                  </div>
                </div>

                <div class="w-full md:col-span-5">
                  <label>Modules</label>
                  <div class="p-2 overflow-y-auto border rounded h-30 bg-gray-50">
                    <div v-for="module in selectedModules" :key="module.id" class="flex items-center mb-2">
                      <input
                        type="checkbox"
                        :id="'module_' + module.id"
                        :value="module.id"
                        v-model="selectedModuleIds"
                        class="checkbox"
                      />
                      <label :for="'module_' + module.id" class="ml-2">{{ module.nom }}</label>
                      <select v-model="selectedNiveaux[module.id]" class="ml-2 border rounded">
                        <option v-for="niveau in niveaux" :key="niveau.id" :value="niveau.id">
                          {{ niveau.niveau }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="text-center md:col-span-5">
                  <div class="inline-flex items-center justify-between space-x-6">
                    <router-link to="/listPs">
                      <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Retour</button>
                    </router-link>
                    <button @click="saveUser" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Enregistrer</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/config'; // Assurez-vous que le chemin est correct

export default {
  data() {
    return {
      domaines: [],
      agences: [],
      users: [],
      niveaux: [],
      form: {
        name: '',
        email: '',
        password: '',
        type: '',
        agence_id: null,  // New field for agency selection
        domaines: []
      },
      selectedDomaines: [],
      selectedModules: [],
      selectedModuleIds: [],
      selectedNiveaux: {},
      generatedPassword: '',  // Holds the generated password
      passwordVisible: false,
    };
  },
  mounted() {
    this.fetchDomaines();
    this.fetchAgencies();
    this.fetchUser();
    this.fetchNiveaux(); // Fetch the niveaux list when component is mounted
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
    async fetchNiveaux() {
      try {
        const response = await axios.get(`${API_URL}/niveaux-competence`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.niveaux = response.data;
      } catch (error) {
        console.error('Error fetching niveaux:', error);
      }
    },
    generatePassword() {
      this.generatedPassword = '123456789';
      this.form.password = this.generatedPassword;
    },
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;  // Toggle password visibility
    },
    async saveUser() {
      try {
        // Préparer les domaines et modules pour l'envoi
        this.form.domaines = this.selectedDomaines.map(domaineId => {
          const domaine = this.domaines.find(d => d.id === domaineId);
          return {
            id: domaineId,
            modules: domaine.modules.map(module => ({
              id: module.id,
              niveau_competence_id: this.selectedNiveaux[module.id]
            }))
          };
        });

        // Filtrer les modules sans niveau de compétence
        this.form.domaines.forEach(domaine => {
          domaine.modules = domaine.modules.filter(module => module.niveau_competence_id);
        });

        // 1. Enregistrer l'utilisateur
        const userResponse = await axios.post(`${API_URL}/store`, this.form, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        });

        // Vérifiez si l'utilisateur est bien enregistré
        if (userResponse.status === 201) {
          // Rediriger après succès
          this.$router.push('/listPs');
        }
      } catch (error) {
        console.error('Erreur lors de l\'enregistrement:', error.response?.data?.errors || error.message);
      }
    },
    fetchDomaines() {
      axios.get(`${API_URL}/domaines-competence`, {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        this.domaines = response.data;
      })
      .catch(error => {
        console.error('Error fetching domaines:', error);
      });
    },
    fetchAgencies() {
      axios.get(`${API_URL}/agences`, {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
      .then(response => {
        this.agences = response.data.agences || [];
      })
      .catch(error => {
        console.error('Error fetching agencies:', error);
      });
    },
    updateModules(domaineId) {
      const domaine = this.domaines.find(d => d.id === domaineId);
      if (domaine) {
        this.selectedModules = domaine.modules;
      }
    }
  }
};
</script>

<style scoped>
/* Ajoutez vos styles ici */
.checkbox {
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid #ccc;
  border-radius: 50%;
  background: white;
  display: inline-block;
  vertical-align: middle;
  cursor: pointer;
  transition: background 0.3s, border-color 0.3s;
}

.checkbox:checked {
  background: #4caf50;
  border-color: #4caf50;
}

.checkbox:focus {
  outline: none;
}

.checkbox:disabled {
  background: #ccc;
  border-color: #ccc;
  cursor: not-allowed;
}

.checkbox + label {
  display: inline-block;
  vertical-align: middle;
  margin-left: 8px;
  cursor: pointer;
}

.h-30 {
  height: 120px;
  overflow-y: auto;
}

.slide-fade-enter-active {
  transition: all 0.5s ease;
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
