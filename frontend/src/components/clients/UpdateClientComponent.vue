<template>
  <div>
    <!-- component -->
    <div class="flex items-center justify-center min-h-screen p-6 bg-gray-100">
      <div class="container max-w-screen-lg mx-auto">
        <div>
          <div class="p-4 px-4 mb-6 bg-white rounded shadow-lg md:p-8">
            <div class="grid items-center justify-center grid-cols-1 gap-4 text-sm gap-y-2 lg:grid-cols-3">
              <div class="text-gray-600">
                <img src="@/assets/sbsN.jpeg" alt="" />
                <p class="text-lg font-medium">Modifier une Mission</p>
                <p class="text-xs">
                  S'il vous plaît, remplissez les champs que vous désirez modifier
                </p>
              </div>

              <div class="lg:col-span-2">
                <div class="grid grid-cols-1 gap-4 text-sm gap-y-2 md:grid-cols-5">
                  <div class="md:col-span-5">
                    <label for="nom">Nom</label>
                    <input
                      type="text"
                      name="nom"
                      id="nom"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      v-model="form.nom"
                      placeholder="Nom"
                    />
                    <span v-if="errors.nom" class="text-red-500">{{ errors.nom }}</span>
                  </div>
                  <div class="md:col-span-5">
                    <label for="abbreviation">Abbreviation</label>
                    <input
                      type="text"
                      name="abbreviation"
                      id="abbreviation"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      v-model="form.abbreviation"
                      placeholder="Abbreviation"
                    />
                    <span v-if="errors.abbreviation" class="text-red-500">{{ errors.abbreviation }}</span>
                  </div>
                  <div class="md:col-span-5">
                    <label for="description">Description</label>
                    <input
                      type="text"
                      name="description"
                      id="description"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      v-model="form.description"
                      placeholder="Description..."
                    />
                    <span v-if="errors.description" class="text-red-500">{{ errors.description }}</span>
                  </div>

                  <div class="md:col-span-5">
                    <label for="color">Couleur</label>
                    <select
                      v-model="form.color"
                      id="color"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                    >
                      <option value="">Sélectionnez une couleur</option>
                      <option value="1">Mission confirmée (Orange)</option>
                      <option value="2">Prévision de mission (Jaune)</option>
                      <option value="3">Voyage (Bleu clair)</option>
                      <option value="4">Congé (Bleu ciel)</option>
                      <option value="5">Formation (Bleu)</option>
                      <option value="6">Récupération (Rouge)</option>
                      <option value="7">Congé maladie (Violet)</option>
                      <option value="8">Université (Bleu ciel)</option>
                      <option value="9">Avant Vente (Vert clair)</option>
                      <option value="10">Prévision de Congé (Maron)</option>
                      <option value="11">Prévision de Récupération (Indigo)</option>
                      <option value="12">Prévision de Formation (Rose)</option>
                    </select>
                    <span v-if="errors.color" class="text-red-500">{{ errors.color }}</span>
                  </div>

                  <div class="text-center md:col-span-5">
                    <div class="inline-flex items-center justify-between space-x-6">
                      <router-link to="/clients">
                        <button
                          class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
                        >
                          Retour
                        </button>
                      </router-link>
                      <button
                        @click="validateForm"
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
                      >
                        Enregistrer
                      </button>
                    </div>
                  </div>

                  <div v-if="serverError" class="text-center text-red-500 md:col-span-5">
                    {{ serverError }}
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
import axios from "axios";
import { API_URL } from '@/config';

export default {
  props: ["id"],
  data() {
    return {
      form: {
        nom: "",
        description: "",
        abbreviation: "",
        color: "",
      },
      errors: {},
      serverError: "",
    };
  },
  methods: {
    async updateClient() {
      try {
        await axios.put(`${API_URL}/clients/${this.id}`, this.form, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.$router.push("/clients");
      } catch (error) {
        console.error("Error updating client:", error);
        this.serverError = "Une erreur est survenue lors de la mise à jour du client.";
      }
    },
    validateForm() {
      this.errors = {}; // Reset errors

      if (!this.form.nom) {
        this.errors.nom = "Le champ nom est requis.";
      }
      if (!this.form.abbreviation) {
        this.errors.abbreviation = "Le champ abbreviation est requis.";
      }
      
      if (!this.form.color) {
        this.errors.color = "Le champ couleur est requis.";
      }

      // Si pas d'erreurs, on procède à la mise à jour
      if (!Object.keys(this.errors).length) {
        this.updateClient();
      }
    },
    async fetchClientData() {
      try {
        const response = await axios.get(`${API_URL}/clients/${this.id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.form = response.data;

        // Vérifier si le nom commence par "p_" et ajuster la couleur en conséquence
        if (this.form.nom.startsWith('p_') && !this.form.color) {
          this.form.color = '10';
        }
      } catch (error) {
        console.error("Error fetching client data:", error);
      }
    },
  },
  watch: {
    'form.nom': function(newVal) {
      if (newVal.startsWith('c_') && !this.form.color) {
        this.form.color = '10';
      }
    }
  },
  created() {
    this.fetchClientData();
  },
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>
