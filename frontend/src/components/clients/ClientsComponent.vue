<template>
  <div class="p-10">
    <div class="max-w-full mx-auto rounded">
      <div class="relative flex flex-col w-full h-full bg-white shadow-md text-slate-700 rounded-xl bg-clip-border">

        <!-- Color Cards -->
        <div class="grid grid-cols-4 gap-2 p-2">
          <div v-for="(color, index) in colors" :key="index" class="p-2 text-center bg-white rounded shadow">
            <div class="text-[9px] font-semibold text-gray-700">{{ color.name }}</div>
            <div class="text-[9px] font-semibold text-gray-700">{{ color.count }} / {{ totalClients }}</div>
          </div>
        </div>

        <!-- Table Header and Actions -->
        <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-none text-slate-700 bg-clip-border">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold capitalize text-slate-800">Liste des Missions</h3>
            </div>
            <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
              <router-link to="/addclient" class="flex select-none items-center gap-2 rounded bg-slate-800 py-2 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" stroke-width="2" class="w-4 h-4">
                  <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                </svg>
                Ajouter un Client
              </router-link>
            </div>
          </div>
        </div>

        <!-- Users Table -->
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-sm text-left table-auto min-w-max">
            <thead>
              <tr>
                <th class="p-2 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="searchName" @input="filterClients" placeholder="Rechercher par nom..." class="p-2 border rounded border-slate-300" />
                </th>
                <th class="p-2 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="searchAbbreviation" @input="filterClients" placeholder="Rechercher par abréviation..." class="p-2 border rounded border-slate-300" />
                </th>
                <th class="p-2 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <p class="flex items-center justify-between gap-2 font-sans leading-none text-slate-500">Description</p>
                </th>
                <th class="p-2 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <p class="flex items-center justify-between gap-2 font-sans leading-none text-slate-500">Modifier</p>
                </th>
                <th class="p-2 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <p class="flex items-center justify-between gap-2 font-sans leading-none text-slate-500">Supprimer</p>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in paginatedClients" :key="client.id">
                <td class="p-2 border-b border-slate-200">
                  <div class="flex items-center gap-3">
                    <div class="flex flex-col">
                      <p class="text-sm font-semibold uppercase text-slate-700">{{ client.nom }}</p>
                    </div>
                  </div>
                </td>
                <td class="p-2 border-b border-slate-200">
                  <div class="flex flex-col">
                    <p class="text-sm font-semibold text-slate-700">{{ client.abbreviation }}</p>
                  </div>
                </td>
                <td class="p-2 border-b border-slate-200">
                  <div class="flex flex-col">
                    <p class="text-[9px] font-semibold text-slate-700 whitespace-normal">{{ client.description }}</p>
                  </div>
                </td>
                <td class="p-2 border-b border-slate-200">
                  <router-link :to="`/updateclient/${client.id}`">
                    <button class="relative w-8 h-8 font-sans text-xs font-medium text-center uppercase align-middle transition-all rounded-lg select-none text-slate-900 hover:bg-slate-900/10 active:bg-slate-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                      <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-4 h-4">
                          <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z"></path>
                        </svg>
                      </span>
                    </button>
                  </router-link>
                </td>
                <td class="p-2 border-b border-slate-200">
                  <button @click="deleteClient(client.id)" class="relative w-8 h-8 font-sans text-xs font-medium text-center text-red-600 uppercase align-middle transition-all rounded-lg select-none hover:bg-red-600/10 active:bg-red-600/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                    <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-4 h-4">
                        <path d="M3 6h18M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2M10 6h4m-6 1v12a1 1 0 001 1h8a1 1 0 001-1V7H4z"></path>
                      </svg>
                    </span>
                  </button>
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
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { API_URL } from '@/config'; // Importer API_URL

export default {
  data() {
    return {
      isModalOpen: false,
      selectedClient: null,
      clients: [],
      searchName: "",
      searchAbbreviation: "",
      showDetailModal: false,
      showAddInterventionModal: false,
      clientDetail: {},
      newIntervention: {
        titre: "",
        commentaire: "",
        date_debut: "",
        date_fin: "",
      },
      currentPage: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    filteredClients() {
      return this.clients.filter((client) =>
        client.nom.toLowerCase().includes(this.searchName.toLowerCase()) &&
        client.abbreviation.toLowerCase().includes(this.searchAbbreviation.toLowerCase())
      );
    },
    paginatedClients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredClients.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredClients.length / this.itemsPerPage);
    },
    totalClients() {
      return this.clients.length;
    },
    colors() {
      const colorCounts = this.clients.reduce((acc, client) => {
        acc[client.color] = (acc[client.color] || 0) + 1;
        return acc;
      }, {});
      return [
        { name: "Mission confirmée", count: colorCounts[1] || 0 },
        { name: "Prévision de mission", count: colorCounts[2] || 0 },
        { name: "Voyage", count: colorCounts[3] || 0 },
        { name: "Congé", count: colorCounts[4] || 0 },
        { name: "Formation", count: colorCounts[5] || 0 },
        { name: "Récupération", count: colorCounts[6] || 0 },
        { name: "Congé maladie", count: colorCounts[7] || 0 },
        { name: "Université", count: colorCounts[8] || 0 },
        { name: "Avant Vente", count: colorCounts[9] || 0 },
        { name: "Prévision de Congé", count: colorCounts[10] || 0 },
        { name: "Prévision de Récupération", count: colorCounts[11] || 0 },
        { name: "Prévision de Formation", count: colorCounts[12] || 0 },
      ];
    },
  },
  watch: {
    searchName() {
      this.currentPage = 1;
    },
    searchAbbreviation() {
      this.currentPage = 1;
    },
  },
  mounted() {
    this.fetchClients();
  },
  methods: {
    async fetchClients() {
      try {
        const response = await axios.get(`${API_URL}/clients`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.clients = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchClientDetail(clientId) {
      try {
        const response = await axios.get(
          `${API_URL}/clients/${clientId}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        this.clientDetail = response.data;
        this.showDetailModal = true;
      } catch (error) {
        console.error(error);
      }
    },
    async deleteClient(clientId) {
      if (confirm("Es-tu sûr de vouloir supprimer ce client ?")) {
        try {
          await axios.delete(`${API_URL}/clients/${clientId}`, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });
          this.clients = this.clients.filter((client) => client.id !== clientId);
        } catch (error) {
          console.error(error);
        }
      }
    },
    openDetailModal(client) {
      this.selectedClient = client;
      this.isModalOpen = true;
    },
    closeDetailModal() {
      this.isModalOpen = false;
      this.selectedClient = null;
    },
    openAddInterventionModal(clientId) {
      this.currentClientId = clientId; // Assure-toi que clientId passé ici est valide
      this.showAddInterventionModal = true;
    },
    closeAddInterventionModal() {
      this.showAddInterventionModal = false;
    },
    async addIntervention() {
      try {
        await axios.post(
          `${API_URL}/interventions`,
          {
            ...this.newIntervention, // Assure-toi que cela inclut bien client_id
            client_id: this.currentClientId, // Vérifie que currentClientId est bien défini
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        this.fetchClientDetail(this.currentClientId);
        this.closeAddInterventionModal();
      } catch (error) {
        console.error(error);
      }
    },
    filterClients() {
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
/* Add any necessary styles here */
</style>
