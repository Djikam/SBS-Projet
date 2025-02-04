<template>
  <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-none text-slate-700 bg-clip-border">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold capitalize text-slate-800">Gestion des Jours Fériés</h3>
      </div>
      <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
        <select v-model="selectedPays" @change="filterJoursFeries" class="p-2 border rounded border-slate-300">
          <option value="">Tous les pays</option>
          <option v-for="agence in agences" :key="agence.id" :value="agence.pays">{{ agence.pays }}</option>
        </select>
        <button
          @click="openModal"
          class="flex select-none items-center gap-2 rounded bg-slate-800 py-2.5 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
          type="button"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" stroke-width="2" class="w-4 h-4">
            <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
          </svg>
          Ajouter un Jour Férié
        </button>
      </div>
    </div>

    <!-- Barre de recherche -->
    <div class="mt-4">
      <input
        v-model="searchQuery"
        @input="filterJoursFeries"
        type="text"
        placeholder="Rechercher par nom..."
        class="w-full p-2 border rounded border-slate-300"
      />
    </div>

    <!-- Table des Jours Fériés -->
    <div class="p-0 mt-4 overflow-y-scroll">
      <table class="w-full min-w-full text-left table-auto">
        <thead>
          <tr>
            <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
              <p class="flex items-center justify-between gap-2 font-sans text-sm font-normal leading-none text-slate-500">Date</p>
            </th>
            <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
              <p class="flex items-center justify-between gap-2 font-sans text-sm font-normal leading-none text-slate-500">Nom</p>
            </th>
            <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
              <p class="flex items-center justify-between gap-2 font-sans text-sm font-normal leading-none text-slate-500">Agence</p>
            </th>
            <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
              <p class="flex items-center justify-between gap-2 font-sans text-sm font-normal leading-none text-slate-500">Modifier</p>
            </th>
            <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
              <p class="flex items-center justify-between gap-2 font-sans text-sm font-normal leading-none text-slate-500">Supprimer</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="jourFerie in paginatedJoursFeries" :key="jourFerie.id">
            <td class="p-4 border-b border-slate-200">
              <p class="text-sm font-semibold text-slate-700">{{ jourFerie.date_ferie }}</p>
            </td>
            <td class="p-4 border-b border-slate-200">
              <p class="text-sm text-slate-500">{{ jourFerie.nom_ferie }}</p>
            </td>
            <td class="p-4 border-b border-slate-200">
              <p class="text-sm text-slate-500">{{ jourFerie.agence ? jourFerie.agence.name : 'Non défini' }}</p>
            </td>
            <td class="p-4 border-b border-slate-200">
              <div class="flex items-center gap-2">
                <button @click="editJourFerie(jourFerie)" class="flex items-center px-2 py-1 text-white rounded bg-stone-400 hover:bg-stone-600">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                  </svg>
                </button>
              </div>
            </td>
            <td class="p-4 border-b border-slate-200">
              <div class="flex items-center gap-2">
                <button @click="deleteJourFerie(jourFerie.id)" class="flex items-center px-2 py-1 text-white bg-red-700 rounded hover:bg-red-500">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between p-2 mt-4">
      <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Précédent</button>
      <span>Page {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Suivant</button>
    </div>

    <!-- Modal pour ajouter/modifier un jour férié -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black bg-opacity-50 backdrop-blur-sm">
      <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">{{ modalTitle }}</h3>
        <div class="mb-4">
          <label for="date_ferie" class="block text-sm font-medium text-gray-700">Date</label>
          <input id="date_ferie" v-model="form.date_ferie" type="date" class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
        </div>
        <div class="mb-4">
          <label for="nom_ferie" class="block text-sm font-medium text-gray-700">Nom</label>
          <input id="nom_ferie" v-model="form.nom_ferie" type="text" class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
        </div>
        <div class="mb-4">
          <label for="agence_id" class="block text-sm font-medium text-gray-700">Agence</label>
          <select id="agence_id" v-model="form.agence_id" class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
            <option v-for="agence in agences" :key="agence.id" :value="agence.id">{{ agence.name }}</option>
          </select>
        </div>
        <div class="flex justify-center space-x-4">
          <button @click="saveJourFerie" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Enregistrer</button>
          <button @click="closeModal" class="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-600">Annuler</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { API_URL } from '../config';

export default {
  data() {
    return {
      joursFeries: [],
      agences: [],
      isModalOpen: false,
      modalTitle: "",
      form: {
        id: null,
        date_ferie: "",
        nom_ferie: "",
        agence_id: null,
      },
      searchQuery: "",
      selectedPays: "",
      currentPage: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    filteredJoursFeries() {
      return this.joursFeries.filter(jourFerie =>
        jourFerie.nom_ferie.toLowerCase().includes(this.searchQuery.toLowerCase()) &&
        (this.selectedPays === "" || (jourFerie.agence && jourFerie.agence.pays === this.selectedPays))
      );
    },
    paginatedJoursFeries() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredJoursFeries.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredJoursFeries.length / this.itemsPerPage);
    },
  },
  mounted() {
    this.fetchJoursFeries();
    this.fetchAgences();
  },
  methods: {
    async fetchJoursFeries() {
      try {
        const response = await axios.get(`${API_URL}/jours-feries`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.joursFeries = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des jours fériés:", error);
      }
    },
    async fetchAgences() {
      try {
        const response = await axios.get(`${API_URL}/agences`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.agences = response.data.agences;
      } catch (error) {
        console.error("Erreur lors de la récupération des agences:", error);
      }
    },
    openModal() {
      this.modalTitle = "Ajouter un Jour Férié";
      this.form = {
        id: null,
        date_ferie: "",
        nom_ferie: "",
        agence_id: null,
      };
      this.fetchAgences().then(() => {
        this.isModalOpen = true;
      });
    },
    editJourFerie(jourFerie) {
      this.modalTitle = "Modifier un Jour Férié";
      this.form = { ...jourFerie };
      this.fetchAgences().then(() => {
        this.isModalOpen = true;
      });
    },
    saveJourFerie() {
      if (this.form.id) {
        axios
          .put(`${API_URL}/jours-feries/${this.form.id}`, this.form, {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          })
          .then(() => {
            this.fetchJoursFeries();
            this.closeModal();
          })
          .catch((error) => {
            console.error("Erreur lors de la mise à jour du jour férié:", error);
          });
      } else {
        axios
          .post(`${API_URL}/jours-feries`, this.form, {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          })
          .then(() => {
            this.fetchJoursFeries();
            this.closeModal();
          })
          .catch((error) => {
            console.error("Erreur lors de l'ajout du jour férié:", error);
          });
      }
    },
    deleteJourFerie(id) {
      axios
        .delete(`${API_URL}/jours-feries/${id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then(() => {
          this.fetchJoursFeries();
        })
        .catch((error) => {
          console.error("Erreur lors de la suppression du jour férié:", error);
        });
    },
    closeModal() {
      this.isModalOpen = false;
      this.form = {
        id: null,
        date_ferie: "",
        nom_ferie: "",
        agence_id: null,
      };
    },
    filterJoursFeries() {
      this.currentPage = 1;
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
