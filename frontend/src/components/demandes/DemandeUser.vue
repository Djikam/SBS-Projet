<template>
  <div class="p-10">
    <div class="max-w-full mx-auto rounded">
      <div class="relative flex flex-col w-full h-full bg-white shadow-md text-slate-700 rounded-xl bg-clip-border">

        <!-- Status Cards -->
        <div class="grid grid-cols-2 gap-4 p-4 md:grid-cols-4">
          <div class="flex items-center justify-between p-4 text-xs text-white bg-gray-400 rounded-lg shadow-lg">
            <h3 class="font-semibold md:text-sm text-[9px]">En cours</h3>
            <p class="font-bold md:text-lg text-[9px]">{{ demandes.filter(d => d.status === 1).length }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white rounded-lg shadow-lg bg-slate-500">
            <h3 class="font-semibold md:text-sm text-[9px]">Abandonné</h3>
            <p class="font-bold md:text-lg text-[9px]">{{ demandes.filter(d => d.status === 0).length }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white rounded-lg shadow-lg bg-slate-500">
            <h3 class="font-semibold md:text-sm text-[9px]">Rejeté</h3>
            <p class="font-bold md:text-lg text-[9px]">{{ demandes.filter(d => d.status === 2).length }} / {{ demandes.length }}</p>
          </div>
          <div class="flex items-center justify-between p-4 text-xs text-white bg-gray-400 rounded-lg shadow-lg">
            <h3 class="font-semibold md:text-sm text-[9px]">Validé</h3>
            <p class="font-bold md:text-lg text-[9px]">{{ demandes.filter(d => d.status === 3).length }} / {{ demandes.length }}</p>
          </div>
        </div>

        <!-- Table Header and Actions -->
        <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-none text-slate-700 bg-clip-border">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold capitalize md:text-lg text-slate-800 text-[11px]">
                Liste des demandes
              </h3>
            </div>
            <!-- Search Bar and Status Filter -->
            <div class="flex items-center justify-between p-4">
              <button
                @click="openAddModal"
                class="ml-4 flex select-none items-center gap-2 rounded bg-slate-800 py-2.5 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none text-[11px] md:text-base"
                type="button"
              >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" stroke-width="2" class="w-4 h-4">
                  <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                </svg>
                Ajouter Demande
              </button>
            </div>
          </div>
        </div>

        <!-- Demandes Table -->
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-left table-auto min-w-max text-[9px]">
            <thead>
              <tr>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="searchQuery" type="text" placeholder="Motif" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <select v-model="statusFilter" class="p-2 border rounded border-slate-300">
                    <option value=""> affichage par Statut</option>
                    <option value="">Tout</option>
                    <option value="1">En cours</option>
                    <option value="0">Abandonné</option>
                    <option value="2">Rejeté</option>
                    <option value="3">Validé</option>
                  </select>
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Voir +</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Modifier</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Annuler</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="demande in paginatedDemandes" :key="demande.id">
                <td class="p-4 border-b border-slate-200">
                  <p class="text-sm font-semibold text-slate-700">{{ demande.motif }}</p>
                </td>
                <td class="p-4 border-b border-slate-200" :class="getEtatColor(demande.status)">
                  <p class="text-sm font-semibold">{{ getStatusLabel(demande.status) }}</p>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openConsultModal(demande)" class="mr-2 text-blue-500 hover:underline">Consulter</button>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button v-if="demande.status === 1" @click="openEditModal(demande)" class="text-green-500 hover:underline">Modifier</button>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button v-if="demande.status === 1" @click="toggleStatus(demande.id)" class="text-red-500 hover:underline">Annuler</button>
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

    <!-- Modal: Add Demande -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 md:w-[450px] max-w-md p-6 bg-white rounded-lg md:h-[600px]">
        <h2 class="mb-4 text-xl font-bold">Ajouter une demande</h2>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label for="motif" class="block mb-2 font-bold text-gray-700">Motif</label>
            <input type="text" id="motif" v-model="form.motif" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Motif de la demande" required>
          </div>
         <div class="flex justify-between">
          <div class="mb-4">
            <label for="date_fin" class="block mb-2 font-bold text-gray-700">Date de fin</label>
            <input type="date" id="date_fin" v-model="form.date_fin" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
          <div class="mb-4">
            <label for="date_debut" class="block mb-2 font-bold text-gray-700">Date de début</label>
            <input type="date" id="date_debut" v-model="form.date_debut" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
         </div>
         <div class="flex justify-between space-x-3">
          <div class="mb-4">
            <label for="client_id" class="block mb-2 font-bold text-gray-700">Client</label>
            <select id="client_id" v-model="form.client_id" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.nom }}</option>
            </select>
          </div>
          <div class="mb-4">
            <label for="module_id" class="block mb-2 font-bold text-gray-700">Module</label>
            <select id="module_id" v-model="form.module_id" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option v-for="module in modules" :key="module.id" :value="module.id">{{ module.nom }} ({{ module.domaine_competence_nom }})</option>
            </select>
          </div>
        </div>
          <div class="mb-4">
            <label for="description" class="block mb-2 font-bold text-gray-700">Description</label>
            <textarea id="description" v-model="form.description" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description de la demande" rows="4" required></textarea>
          </div>
          
          
          <div class="flex justify-between">
            <button type="button" @click="closeAddModal" class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Annuler</button>
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-full shadow-lg">Soumettre</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal: Consult Demande -->
    <div v-if="showConsultModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 md:w-[450px] max-w-md p-6 bg-white rounded-lg  md:h-[600px]">
        <h2 class="mb-4 text-xl font-bold">Consulter la demande de :
          <p class="font-serif text-sm" v-if="consultDemande.user">{{ consultDemande.user.name }}</p>
          <p v-else>Non défini</p>
        </h2>
        <div class="mb-4">
          <label class="block mb-2 font-bold text-gray-700">Motif</label>
          <p>{{ consultDemande.motif }}</p>
        </div>
       <div class="flex justify-between space-x-2">
        <div class="mb-4">
          <label class="block mb-2 font-bold text-gray-700">État</label>
          <p>{{ getStatusLabel(consultDemande.status) }}</p>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-bold text-gray-700">Date de fin</label>
          <p>{{ consultDemande.date_fin }}</p>
        </div>
        <div class="mb-4">
          <label class="block mb-2 font-bold text-gray-700">Date de début</label>
          <p>{{ consultDemande.date_debut }}</p>
        </div>
       </div>
     <div class="flex justify-between space-x-3">
      <div class="mb-4">
        <label class="block mb-2 font-bold text-gray-700">Description</label>
        <p>{{ consultDemande.client.nom }}</p>
      </div>
      <div class="mb-4">
        <label class="block mb-2 font-bold text-gray-700">Module</label>
        <p v-if="consultDemande.module">{{ consultDemande.module.nom }} ({{ consultDemande.module.domaine_competence_nom }})</p>
        <p v-else>Non défini</p>
      </div>
     </div>
     <div class="mb-4">
      <label class="block mb-2 font-bold text-gray-700">Missions</label>
      <p>{{ consultDemande.description }}</p>
    </div>
        <div class="mb-4">
          <label class="block mb-2 font-bold text-gray-700">Commentaire</label>
          <p>{{ consultDemande.comment }}</p>
        </div>
        <div class="flex justify-end">
          <button type="button" @click="closeConsultModal" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Fermer</button>
        </div>
      </div>
    </div>

    <!-- Modal: Edit Demande -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center ">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 md:w-[450px]  p-6 bg-white rounded-lg md:h-[600px]">
        <h2 class="mb-4 text-xl font-bold">Modifier la demande</h2>
        <form @submit.prevent="updateForm" class="">
          <div class="mb-4">
            <label for="motif" class="block mb-2 font-bold text-gray-700">Motif</label>
            <input type="text" id="motif" v-model="editForm.motif" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Motif de la demande" required>
          </div>
          <div class="flex justify-between ">
            
            <div class="mb-4">
              <label for="date_debut" class="block mb-2 font-bold text-gray-700">Date de début</label>
              <input type="date" id="date_debut" v-model="editForm.date_debut" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
              <label for="date_fin" class="block mb-2 font-bold text-gray-700">Date de fin</label>
              <input type="date" id="date_fin" v-model="editForm.date_fin" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
          </div>
          <div class="flex justify-between md:space-x-3">
            <div class="mb-4">
              <label for="client_id" class="block mb-2 font-bold text-gray-700">MIssion</label>
              <select id="client_id" v-model="editForm.client_id" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.nom }}</option>
              </select>
            </div>
            <div class="mb-4">
              <label for="module_id" class="block mb-2 font-bold text-gray-700">Module</label>
              <select id="module_id" v-model="editForm.module_id" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option v-for="module in modules" :key="module.id" :value="module.id">{{ module.nom }} ({{ module.domaine_competence_nom }})</option>
              </select>
            </div>
            
          </div>
          <div class="mb-1">
            <label for="description" class="block mb-2 font-bold text-gray-700">Description</label>
            <textarea id="description" v-model="editForm.description" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description de la demande" rows="4" required></textarea>
          </div>
          <div class="flex justify-between">
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
      domainesCompetences: [],
      clients: [],
      modules: [],
      searchQuery: "",
      statusFilter: "",
      showAddModal: false,
      showConsultModal: false,
      showEditModal: false,
      consultDemande: {},
      form: {
        motif: '',
        date_fin: '',
        date_debut: '',
        description: '',
        domaine_competence_id: '',
        client_id: '',
        module_id: '',
        
      },
      editForm: {
        id: '',
        motif: '',
        date_fin: '',
        date_debut: '',
        description: '',
        client_id: '',
        module_id: ''
      },
      currentPage: 1,
      itemsPerPage: 10
    };
  },
  computed: {
    filteredDemandes() {
      if (!Array.isArray(this.demandes)) {
        return [];
      }
      return this.demandes.filter(demande => {
        const matchesQuery = demande.motif ? demande.motif.toLowerCase().includes(this.searchQuery.toLowerCase()) : false;
        const matchesStatus = this.statusFilter === "" || demande.status == this.statusFilter;
        return matchesQuery && matchesStatus;
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
    this.fetchClients();
    this.fetchModulesByDomaine();
    
  },
  methods: {
    async fetchDemandes() {
      try {
        const response = await axios.get(`${API_URL}/demande`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.demandes = response.data.demandes;
      } catch (error) {
        console.error('Error fetching demandes:', error);
      }
    },
    async fetchClients() {
      try {
        const response = await axios.get(`${API_URL}/clients`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.clients = response.data;
      } catch (error) {
        console.error('Error fetching clients:', error);
      }
    },
    
    async fetchModulesByDomaine() {
      try {
        const response = await axios.get(`${API_URL}/modules`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.modules= response.data.modules;
      } catch (error) {
        console.error('Error fetching modules by domaine:', error);
      }
    },
    openAddModal() {
      this.showAddModal = true;
    },
    closeAddModal() {
      this.showAddModal = false;
    },
    openConsultModal(demande) {
      this.consultDemande = demande;
      this.showConsultModal = true;
    },
    closeConsultModal() {
      this.showConsultModal = false;
    },
    openEditModal(demande) {
      this.editForm = { ...demande };
      this.showEditModal = true;
      this.fetchModulesByDomaine(demande.domaine_competence_id);
    },
    closeEditModal() {
      this.showEditModal = false;
    },
    async submitForm() {
      try {
        const response = await axios.post(`${API_URL}/demandes`, {
          user_id: localStorage.getItem('userId'),
          motif: this.form.motif,
          date_fin: this.form.date_fin,
          date_debut: this.form.date_debut,
          description: this.form.description,
          client_id: this.form.client_id,
          module_id: this.form.module_id
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.demandes.push(response.data);
        this.closeAddModal();
        this.fetchDemandes();
        this.form.motif = '';
        this.form.date_fin = '';
        this.form.date_debut = '';
        this.form.description = '';
        this.form.client_id = '';
        this.form.module_id = '';
      } catch (error) {
        console.error('Error submitting request:', error);
      }
    },
    async updateForm() {
      try {
        const response = await axios.put(`${API_URL}/demandes/${this.editForm.id}`, {
          motif: this.editForm.motif,
          date_fin: this.editForm.date_fin,
          date_debut: this.editForm.date_debut,
          description: this.editForm.description,
          domaine_competence_id: this.editForm.domaine_competence_id,
          client_id: this.editForm.client_id,
          module_id: this.editForm.module_id
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        const index = this.demandes.findIndex(demande => demande.id === this.editForm.id);
        this.demandes.splice(index, 1, response.data);
        this.closeEditModal();
        this.fetchDemandes();
      } catch (error) {
        console.error('Error updating request:', error);
      }
    },
    async toggleStatus(id) {
      try {
        const newStatus = 0; // Always set to 0 to annul the status
        const response = await axios.put(`${API_URL}/demandes/${id}`, {
          status: newStatus
        }, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        const index = this.demandes.findIndex(demande => demande.id === id);
        this.demandes.splice(index, 1, response.data);
        this.fetchDemandes();
      } catch (error) {
        console.error('Error updating status:', error);
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
    getEtatColor(status) {
      switch (status) {
        case 1:
          return 'text-green-500';
        case 0:
          return 'text-red-500';
        case 2:
          return 'text-red-500';
        case 3:
          return 'text-green-500';
        default:
          return '';
      }
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
    }
  }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
