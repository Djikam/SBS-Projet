<template>
  <div class="p-4 md:p-10">
    <div class="max-w-full mx-auto rounded">
      <div class="relative flex flex-col w-full h-full bg-white shadow-md text-slate-700 rounded-xl bg-clip-border">
        <!-- Status Cards -->
        <div class="grid grid-cols-2 gap-4 p-4 md:grid-cols-4">
          <div class="flex items-center justify-between p-4 text-xs text-white bg-gray-400 rounded-lg shadow-lg">
            <h3 class="text-[9px] font-semibold md:text-sm">En cours</h3>
            <p class="text-[9px] font-bold md:text-lg">{{ demandes.length ? demandes.filter(d => d.status === 1).length : 0 }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white rounded-lg shadow-lg bg-slate-500">
            <h3 class="text-[9px] font-semibold md:text-sm">Abandonné</h3>
            <p class="text-[9px] font-bold md:text-lg">{{ demandes.length ? demandes.filter(d => d.status === 0).length : 0 }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white rounded-lg shadow-lg bg-slate-500">
            <h3 class="text-[9px] font-semibold md:text-sm">Rejeté</h3>
            <p class="text-[9px] font-bold md:text-lg">{{ demandes.length ? demandes.filter(d => d.status === 2).length : 0 }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white bg-gray-400 rounded-lg shadow-lg">
            <h3 class="text-[9px] font-semibold md:text-sm">Validé</h3>
            <p class="text-[9px] font-bold md:text-lg">{{ demandes.length ? demandes.filter(d => d.status === 3).length : 0 }} / {{ demandes.length }} </p>
          </div>
        </div>

        <!-- Table Header and Actions -->
        <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-none text-slate-700 bg-clip-border">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold text-center capitalize text-slate-800 lg:text-left">
                Liste des demandes
              </h3>
            </div>
          </div>
        </div>

        <!-- Demandes Table -->
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-sm text-left table-auto min-w-max md:text-base">
            <thead>
              <tr>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="filters.motif" placeholder="Motif" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="filters.user" placeholder="Utilisateur" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <select v-model="filters.status" class="w-full p-2 border rounded border-slate-300">
                    <option value="">Tous</option>
                    <option value="1">En cours</option>
                    <option value="0">Abandonné</option>
                    <option value="2">Rejeté</option>
                    <option value="3">Validé</option>
                  </select>
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Voir +</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Décider</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(demande) in paginatedDemandes" :key="demande.id">
                <td class="p-4 border-b border-slate-200">
                  <p class="text-sm font-semibold text-slate-700">{{ demande.motif || 'N/A' }}</p>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <p class="text-sm text-slate-500">{{ demande.user ? demande.user.name : 'N/A' }}</p>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <p v-if="demande.status === 1" class="text-sm font-semibold text-green-500">En cours</p>
                  <p v-else-if="demande.status === 0" class="text-sm font-semibold text-red-500">Abandonné</p>
                  <p v-else-if="demande.status === 2" class="text-sm font-semibold text-red-500">Rejeté</p>
                  <p v-else-if="demande.status === 3" class="text-sm font-semibold text-green-500">Validé</p>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openConsultModal(demande)" class="mr-2 text-blue-500 hover:underline">Consulter</button>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button v-if="demande.status === 1" @click="openEditModal(demande)" class="text-slate-500 hover:underline">Décider</button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="flex items-center justify-between p-2 mt-4">
            <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Précédent</button>
            <span class="mx-4">Page {{ currentPage }} sur {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Suivant</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Consult Demande -->
    <div v-if="showConsultModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
        <h2 class="mb-4 text-xl font-bold">Consulter la demande</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 font-bold text-gray-700">Motif</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.motif || 'N/A' }}</p>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-700">Statut</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ getStatusLabel(consultDemande.status) }}</p>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-700">Date de fin</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.date_fin || 'N/A' }}</p>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-700">Date de début</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.date_debut || 'N/A' }}</p>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-700">Module</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.module_nom || 'Non défini' }}</p>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-700">Utilisateur</label>
            <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.user ? consultDemande.user.name : 'Non défini' }}</p>
          </div>
        </div>
        <div>
          <label class="block mb-2 font-bold text-gray-700">Description</label>
          <p class="p-2 bg-gray-100 rounded shadow-inner">{{ consultDemande.description || 'N/A' }}</p>
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" @click="closeConsultModal" class="px-4 py-2 text-gray-700 transition-colors duration-300 bg-gray-300 rounded-full shadow-lg hover:bg-gray-400">Fermer</button>
        </div>
      </div>
    </div>

    <!-- Modal: Edit Demande -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 w-full max-w-md p-6 bg-white rounded-lg">
        <h2 class="mb-4 text-xl font-bold">Modifier le statut de la demande</h2>
        <form @submit.prevent="updateForm">
          <div class="mb-4">
            <label for="status" class="block mb-2 font-bold text-gray-700">Statut</label>
            <select id="status" v-model="editForm.status" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="2" class="text-red-500">Rejeté</option>
              <option value="3" class="text-green-500">Validé</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="comment" class="block mb-2 font-bold text-gray-700">Commentaire</label>
            <textarea id="comment" v-model="editForm.comment" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
          </div>
          <div class="flex justify-end">
            <button type="button" @click="closeEditModal" class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Annuler</button>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-full shadow-lg">Mettre à jour</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/config';

export default {
  data() {
    return {
      demandes: [],
      modules: [],
      filters: {
        motif: "",
        user: "",
        status: ""
      },
      showConsultModal: false,
      showEditModal: false,
      consultDemande: {},
      editForm: {
        id: '',
        status: '',
        comment: ''
      },
      currentPage: 1,
      itemsPerPage: 10
    };
  },
  computed: {
    filteredDemandes() {
      return this.demandes.filter(demande => {
        const matchesMotif = demande.motif?.toLowerCase().includes(this.filters.motif.toLowerCase());
        const matchesUser = demande.user?.name.toLowerCase().includes(this.filters.user.toLowerCase());
        const matchesStatus = this.filters.status === "" || demande.status == this.filters.status;
        return matchesMotif && matchesUser && matchesStatus;
      });
    },
    paginatedDemandes() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredDemandes.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredDemandes.length / this.itemsPerPage);
    }
  },
  mounted() {
    this.fetchDemandes();
    this.fetchModules();
  },
  methods: {
    async fetchDemandes() {
      try {
        const response = await axios.get(`${API_URL}/demandes`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.demandes = response.data.demandes;
        this.totalPages = response.data.demandes.last_page;
      } catch (error) {
        console.error('Error fetching demandes:', error);
      }
    },
    async fetchModules() {
      try {
        const response = await axios.get(`${API_URL}/modules`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.modules = response.data.modules;
      } catch (error) {
        console.error('Error fetching modules:', error);
      }
    },
    async openConsultModal(demande) {
      this.consultDemande = demande;
      await this.fetchModules();
      const module = this.modules.find(module => module.id === demande.module_id);
      if (module) {
        this.consultDemande.module_nom = module.nom;
      }
      this.showConsultModal = true;
    },
    closeConsultModal() {
      this.showConsultModal = false;
    },
    openEditModal(demande) {
      this.editForm = { ...demande };
      this.showEditModal = true;
    },
    closeEditModal() {
      this.showEditModal = false;
    },
    async updateForm() {
      try {
        const response = await axios.put(`${API_URL}/demandes/${this.editForm.id}`, {
          status: this.editForm.status,
          comment: this.editForm.comment
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.demandes.push(response.data);
        this.closeEditModal();
        this.fetchDemandes();
      } catch (error) {
        console.error('Error updating request:', error);
      }
    },
    async deleteDemande(id) {
      try {
        await axios.delete(`${API_URL}/demandes/${id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.demandes = this.demandes.filter(demande => demande.id !== id);
      } catch (error) {
        console.error('Error deleting request:', error);
      }
    },
    getStatusLabel(status) {
      switch (status) {
        case 1:
          return 'En cours';
        case 0:
          return 'Abandonné';
        case 2:
          return 'Rejeté';
        case 3:
          return 'Validé';
        default:
          return '';
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchDemandes();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchDemandes();
      }
    }
  }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
