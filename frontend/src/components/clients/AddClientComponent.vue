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
                <p class="text-lg font-medium">Ajouter une Mission</p>
                <p class="text-xs">S'il vous plaît remplissez tous les champs</p>
              </div>

              <div class="lg:col-span-2">
                <div class="grid grid-cols-1 gap-4 text-sm gap-y-2 md:grid-cols-5">
                  <div class="md:col-span-5">
                    <label for="nom">Noms</label>
                    <input
                      v-model="form.nom"
                      type="text"
                      id="nom"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      placeholder="noms"
                    />
                  </div>
                  <div class="md:col-span-5">
                    <label for="abbreviation">Abbreviation</label>
                    <input
                      v-model="form.abbreviation"
                      type="text"
                      id="abbreviation"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      placeholder="abbreviation"
                    />
                  </div>
                  <div class="md:col-span-5">
                    <label for="description">Description</label>
                    <input
                      v-model="form.description"
                      type="text"
                      id="description"
                      class="w-full h-10 px-4 mt-1 border rounded bg-gray-50"
                      placeholder="text...."
                    />
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
                      <option value="8">Université (Sky)</option>
                      <option value="9">Avant Vente (Teal)</option>
                      <option value="10">Prévision de Congé (Maron)</option>
                      <option value="11">Prévision de Récupération (Indigo)</option>
                      <option value="12">Prévision de Formation (Rose)</option>
                    </select>
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
                        @click="saveClient"
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
                      >
                        Enregistrer
                      </button>
                    </div>
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
  data() {
    return {
      form: {
        nom: "",
        description: "",
        abbreviation: "",
        color: "", // Ajout du champ color
      },
    };
  },
  watch: {
    'form.nom': function(newVal) {
      if (newVal.startsWith('c_')) {
        this.form.color = '10'; // Set to green for Prévision de Mission
      } else if (newVal.startsWith('r_')) {
        this.form.color = '11'; // Set to indigo for Prévision de Récupération
      } else if (newVal.startsWith('f_')) {
        this.form.color = '12'; // Set to pink for Prévision de Formation
      }
    }
  },
  methods: {
    async saveClient() {
      try {
        await axios.post(`${API_URL}/clients`, this.form, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.$router.push("/clients");
      } catch (error) {
        // Handle error (show a message to the client, etc.)
        console.error("Erreur lors de l'enregistrement du client:", error);
      }
    },
  },
};
</script>
