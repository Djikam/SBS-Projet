<template>
  <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-2xl font-bold">
      {{ isEdit ? "Modifier un Domaine" : "Ajouter un Domaine" }}
    </h2>

    <form @submit.prevent="saveDomaine" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nom du Domaine</label>
        <input
          v-model="domaineForm.nom"
          required
          placeholder="Nom du domaine"
          class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Modules</label>
        <div v-for="(module, index) in domaineForm.modules" :key="index" class="mb-2">
          <input
            v-model="module.nom"
            placeholder="Nom du module"
            class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
          />
          <button type="button" @click="removeModule(index)" class="text-red-600 hover:underline">
            Supprimer
          </button>
        </div>
        <button type="button" @click="addModule" class="text-blue-600 hover:underline">
          Ajouter un module
        </button>
      </div>

      <button
        type="submit"
        class="w-full py-2 font-bold text-white transition duration-200 bg-blue-600 rounded hover:bg-blue-700"
      >
        {{ isEdit ? "Modifier" : "Ajouter" }}
      </button>
    </form>

    <!-- Liste des domaines dans un tableau -->
    <h3 class="mt-8 text-xl font-semibold">Liste des Domaines</h3>
    <table class="min-w-full mt-4 bg-gray-100 rounded-md shadow">
      <thead>
        <tr class="text-center text-white bg-blue-600">
          <th class="px-4 py-2">Domaine</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="domaine in domaines" :key="domaine.id" class="border-b hover:bg-gray-200">
          <td class="px-4 py-2">{{ domaine.nom }}</td>
          <td class="px-4 py-2">
            <button @click="editDomaine(domaine)" class="text-blue-600 hover:underline">
              Modifier
            </button>
            <button @click="deleteDomaine(domaine.id)" class="ml-4 text-red-600 hover:underline">
              Supprimer
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Bouton flottant en coin droit -->
    <router-link
      to="/"
      class="absolute px-4 py-2 font-bold text-white transition duration-200 bg-blue-600 rounded-full shadow-lg right-4 bottom-4 hover:bg-blue-700"
    >
      Retour
    </router-link>
  </div>
</template>

<script>
import axios from "axios";
import { API_URL } from '@/config'; // Assurez-vous que le chemin est correct

export default {
  data() {
    return {
      domaines: [],
      domaineForm: { nom: "", modules: [{ nom: "" }] },
      isEdit: false,
      editDomaineId: null,
    };
  },
  methods: {
    fetchDomaines() {
      axios
        .get(`${API_URL}/domaines-competence`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then((response) => {
          this.domaines = response.data;
        });
    },
    saveDomaine() {
      const url = this.isEdit
        ? `${API_URL}/domaines-competence/${this.editDomaineId}`
        : `${API_URL}/domaines-competence`;
      const method = this.isEdit ? "put" : "post";

      axios[method](url, this.domaineForm, {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      }).then(() => {
        this.fetchDomaines();
        this.resetForm();
      });
    },
    editDomaine(domaine) {
      this.domaineForm = {
        nom: domaine.nom,
        modules: domaine.modules.map(module => ({ nom: module.nom })),
      };
      this.isEdit = true;
      this.editDomaineId = domaine.id;
    },
    deleteDomaine(id) {
      axios
        .delete(`${API_URL}/domaines-competence/${id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then(() => {
          this.fetchDomaines();
        });
    },
    resetForm() {
      this.domaineForm = { nom: "", modules: [{ nom: "" }] };
      this.isEdit = false;
      this.editDomaineId = null;
    },
    addModule() {
      this.domaineForm.modules.push({ nom: "" });
    },
    removeModule(index) {
      this.domaineForm.modules.splice(index, 1);
    },
  },
  mounted() {
    this.fetchDomaines();
  },
};
</script>

<style scoped>
/* Ajoutez des styles personnalisés ici si nécessaire */
</style>
