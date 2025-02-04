<template>
  <div class="p-4 md:p-10">
    <div class="max-w-full mx-auto bg-white rounded shadow-md text-slate-700">
      <div class="relative flex flex-col w-full h-full bg-white shadow-md text-slate-700 rounded-xl bg-clip-border">

        <!-- Table Header and Actions -->
        <div class="relative mx-4 mt-4 overflow-hidden bg-white text-slate-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold capitalize text-slate-800">Liste des Agences</h3>
            <button
              @click="openAddModal"
              class="flex items-center gap-2 rounded bg-slate-800 py-2.5 px-4 text-xs font-semibold text-white transition-colors duration-300 hover:bg-slate-700"
            >
              Ajouter Agence
            </button>
          </div>
        </div>

        <!-- Agencies Table -->
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-left table-auto min-w-max">
            <thead>
              <tr>
                <th class="p-4 border-y border-slate-200 bg-slate-50">
                  <input v-model="searchName" @input="updateSearchName" placeholder="Rechercher par nom..." class="p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 border-y border-slate-200 bg-slate-50">
                  <input v-model="searchPays" @input="updateSearchPays" placeholder="Rechercher par pays..." class="p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 border-y border-slate-200 bg-slate-50">Modification</th>
                <th class="p-4 border-y border-slate-50">Suppression</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="agence in paginatedAgences" :key="agence.id" class="border-b hover:bg-slate-200">
                <td class="p-4 font-bold capitalize border-b border-slate-200">{{ agence.name }}</td>
                <td class="p-4 font-semibold uppercase border-b border-slate-200">{{ agence.pays }}</td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openEditModal(agence)" class="px-2 py-1 text-white transition-colors duration-300 bg-blue-500 rounded hover:bg-blue-700">Modifier</button>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openDeleteModal(agence.id)" class="px-2 py-1 text-white transition-colors duration-300 bg-red-500 rounded hover:bg-red-700">Supprimer</button>
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

    <!-- Add Modal -->
    <AddAgenceModal :show="showAddModal" @close="closeAddModal" @add="addAgence" />

    <!-- Edit Modal -->
    <EditAgenceModal :show="showEditModal" :agence="currentAgence" @close="closeEditModal" @update="updateAgence" />

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="p-4 bg-white rounded-xl shadow-md w-full max-w-md md:w-[30%]">
        <h3 class="mb-4 text-lg font-semibold">Confirmer la suppression</h3>
        <p>Êtes-vous sûr de vouloir supprimer cette agence ?</p>
        <div class="flex justify-center gap-4 mt-4">
          <button @click="closeDeleteModal" class="px-4 py-2 text-gray-700 transition-colors duration-300 bg-gray-200 rounded hover:bg-gray-700">Annuler</button>
          <button @click="confirmDeleteAgence" class="px-4 py-2 text-white transition-colors duration-300 bg-red-500 rounded hover:bg-red-700">Supprimer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AddAgenceModal from './AddAgenceModal.vue';
import EditAgenceModal from './EditAgenceModal.vue';
import { getWithCache } from '../../api';

export default {
  components: {
    AddAgenceModal,
    EditAgenceModal,
  },
  data() {
    return {
      agences: [],
      searchName: "",
      searchPays: "",
      currentAgence: { id: null, name: "", pays: "" },
      currentPage: 1,
      itemsPerPage: 10,
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      agenceToDelete: null,
    };
  },
  computed: {
    filteredAgences() {
      const searchName = this.searchName ? this.searchName.toLowerCase() : "";
      const searchPays = this.searchPays ? this.searchPays.toLowerCase() : "";
      return this.agences.filter((agence) =>
        (agence.name && agence.name.toLowerCase().includes(searchName)) &&
        (agence.pays && agence.pays.toLowerCase().includes(searchPays))
      );
    },
    paginatedAgences() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredAgences.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredAgences.length / this.itemsPerPage);
    },
  },
  watch: {
    searchName() {
      this.currentPage = 1;
    },
    searchPays() {
      this.currentPage = 1;
    },
  },
  mounted() {
    this.fetchAgences();
  },
  methods: {
    async fetchAgences() {
      try {
        const response = await getWithCache('/agences', {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.agences = response.data.agences;
      } catch (error) {
        console.error("Error fetching agences:", error);
      }
    },
    openAddModal() {
      this.showAddModal = true;
    },
    closeAddModal() {
      this.showAddModal = false;
    },
    openEditModal(agence) {
      if (agence) {
        this.currentAgence = { ...agence };
        this.showEditModal = true;
      } else {
        console.error("Agence is undefined");
      }
    },
    closeEditModal() {
      this.showEditModal = false;
    },
    openDeleteModal(id) {
      this.agenceToDelete = id;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.agenceToDelete = null;
    },
    async confirmDeleteAgence() {
      if (this.agenceToDelete) {
        try {
          await getWithCache(`/agences/${this.agenceToDelete}`, {
            method: 'DELETE',
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });
          this.agences = this.agences.filter(agence => agence.id !== this.agenceToDelete);
          this.closeDeleteModal();
          this.fetchAgences();
        } catch (error) {
          console.error(error);
        }
      }
    },
    updateSearchName(query) {
      this.searchName = query;
    },
    updateSearchPays(query) {
      this.searchPays = query;
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
    async addAgence(agence) {
      try {
        const response = await getWithCache("/agences", {
          method: 'POST',
          data: agence,
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const newAgence = response.data;
        this.agences.push(newAgence);
        this.closeAddModal();
        this.fetchAgences();
      } catch (error) {
        console.error(error);
      }
    },
    async updateAgence(agence) {
      try {
        const response = await getWithCache(`/agences/${agence.id}`, {
          method: 'PUT',
          data: agence,
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const updatedAgence = response.data;
        const index = this.agences.findIndex(a => a.id === updatedAgence.id);
        if (index !== -1) {
          this.agences.splice(index, 1, updatedAgence);
        }
        this.closeEditModal();
        this.fetchAgences();
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
