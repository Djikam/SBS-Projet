<template>
  <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Gestion des Niveaux de Compétence</h1>
      <router-link
        to="/"
        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700"
      >
        Retour
      </router-link>
    </div>

    <!-- Formulaire d'ajout / modification d'un niveau de compétence -->
    <form @submit.prevent="saveNiveau" class="space-y-4">
      <div class="flex space-x-4">
        <div class="flex-1">
          <label for="niveau" class="block text-sm font-medium text-gray-700">Niveau de Compétence</label>
          <input
            v-model="niveauForm.niveau"
            required
            placeholder="Entrez le niveau de compétence"
            class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <button
        type="submit"
        class="w-full py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700"
      >
        {{ isEdit ? "Modifier" : "Ajouter" }} Niveau
      </button>
    </form>

    <!-- Liste des niveaux de compétence -->
    <div class="mt-8">
      <h2 class="text-xl font-semibold">Liste des Niveaux de Compétence</h2>
      <table class="min-w-full mt-4 bg-gray-100 rounded-md shadow">
        <thead>
          <tr class="text-center text-white bg-blue-600">
            <th class="px-4 py-2">Niveau de Compétence</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="niveau in niveaux" :key="niveau.id" class="border-b hover:bg-gray-200">
            <td class="px-4 py-2">{{ niveau.niveau }}</td>
            <td class="px-4 py-2">
              <button @click="editNiveau(niveau)" class="text-blue-600 hover:underline">
                Modifier
              </button>
              <button @click="deleteNiveau(niveau.id)" class="ml-4 text-red-600 hover:underline">
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { API_URL } from '@/config'; // Assurez-vous que le chemin est correct

export default {
  data() {
    return {
      niveaux: [],
      niveauForm: { niveau: "" },
      isEdit: false,
      editNiveauId: null,
    };
  },
  methods: {
    fetchNiveaux() {
      axios
        .get(`${API_URL}/niveaux-competence`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then((response) => {
          this.niveaux = response.data;
        })
        .catch((error) => {
          console.error(
            "Il y a eu une erreur lors de la récupération des niveaux :",
            error
          );
        });
    },
    saveNiveau() {
      const url = this.isEdit
        ? `${API_URL}/niveaux-competence/${this.editNiveauId}`
        : `${API_URL}/niveaux-competence`;
      const method = this.isEdit ? "put" : "post";

      axios[method](url, this.niveauForm, {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }).then(() => {
        this.fetchNiveaux();
        this.resetForm();
      });
    },
    editNiveau(niveau) {
      this.niveauForm = {
        niveau: niveau.niveau,
      };
      this.isEdit = true;
      this.editNiveauId = niveau.id;
    },
    deleteNiveau(id) {
      axios
        .delete(`${API_URL}/niveaux-competence/${id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then(() => {
          this.fetchNiveaux();
        });
    },
    resetForm() {
      this.niveauForm = { niveau: "" };
      this.isEdit = false;
      this.editNiveauId = null;
    },
  },
  mounted() {
    this.fetchNiveaux();
  },
};
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici si nécessaire */
</style>
