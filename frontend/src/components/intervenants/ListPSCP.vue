<template>
  <div class="p-10">
    <div class="max-w-full mx-auto rounded">
      <div class="relative flex flex-col w-full h-full bg-white shadow-md text-slate-700 rounded-xl bg-clip-border">
        <!-- Table Header and Actions -->
        <div class="relative mx-4 mt-4 overflow-hidden bg-white rounded-none text-slate-700 bg-clip-border">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-semibold capitalize text-slate-800">
                Liste des Consultants
              </h3>
            </div>
            <div class="flex flex-col gap-2 shrink-0 sm:flex-row">
             

              <!-- Agency Filter -->
              <div class="ml-4">
                <select v-model="localSelectedAgency" @change="filterByAgency" class="p-2 border rounded">
                  <option value="">Toutes les agences</option>
                  <option v-for="agency in agencies" :key="agency.id" :value="agency.id">{{ agency.name }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Users Table -->
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-left table-auto min-w-max">
            <thead>
              <tr>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="nameFilter" type="text" placeholder="Nom" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="roleFilter" type="text" placeholder="Rôle" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">
                  <input v-model="statusFilter" type="text" placeholder="Statut" class="w-full p-2 border rounded border-slate-300" />
                </th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Compétences</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in paginatedUsers" :key="user.id">
                <td class="p-4 border-b border-slate-200">
                  <div class="flex items-center gap-3">
                    <div class="flex flex-col">
                      <p class="text-sm font-semibold text-slate-700">{{ user.name }}</p>
                      <p class="text-sm text-slate-500">{{ user.email }}</p>
                    </div>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <div v-if="user.type == 0" class="text-sm font-semibold text-teal-700">
                    Consultant Technique
                  </div>
                  <div v-if="user.type == 1" class="text-sm font-semibold text-sky-700">
                    Consultant Fonctionnel
                  </div>
                  <div v-if="user.type == 2" class="text-sm font-semibold text-amber-700">
                    Consultant FullStack
                  </div>
                  <div v-if="user.type == 3" class="text-sm font-semibold text-blue-700">
                    Chef De Projet
                  </div>
                  <div v-if="user.type == 4" class="text-sm font-semibold text-cyan-400">
                    Manager
                  </div>
                  <div v-if="user.type == 5" class="text-sm font-semibold text-slate-700">
                    Administrateur
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <div class="w-max">
                    <div :class="{'bg-green-500/20': user.active, 'bg-red-500/20': !user.active}"  class="relative grid items-center w-24 px-2 py-1 font-sans text-xs font-bold text-center uppercase rounded-md select-none whitespace-nowrap">
                      <p class="text-sm font-semibold capitalize text-slate-700">{{ user.active ? "Active" : "Bloqué" }}</p>
                    </div>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openCompetenceModal(user)" class="text-blue-500 hover:underline">Compétences</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between p-2 mt-4">
          <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Précédent</button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Suivant</button>
        </div>
      </div>
    </div>

    <!-- Modal: Competence -->
    <div v-if="showCompetenceModal" class="fixed inset-0 z-50 flex items-center justify-center ">
      <div class="absolute inset-0 bg-gray-900 opacity-50"></div>
      <div class="z-50 w-full m p-6 bg-white rounded-lg md:w-[1000px]">
        <h2 class="mb-4 text-xl font-bold">Compétences de {{ selectedUser.name }}</h2>
        <div class="p-0 overflow-y-scroll md:h-[400px] ">
          <table class="w-full mt-4 text-left table-auto min-w-max">
            <thead>
              <tr>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Domaine</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Module</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Niveau</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(domain, index) in groupedCompetences" :key="index">
                <tr>
                  <td :rowspan="domain.modules.length"  class="p-4 border-b border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">{{ domain.domaine }}</p>
                  </td>
                  <td class="p-4 border-b border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">{{ domain.modules[0].nom }}</p>
                  </td>
                  <td class="p-4 border-b border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">{{ domain.modules[0].niveau }}</p>
                  </td>
                </tr>
                <tr v-for="(module, moduleIndex) in domain.modules.slice(1)" :key="moduleIndex">
                  <td class="p-4 border-b border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">{{ module.nom }}</p>
                  </td>
                  <td class="p-4 border-b border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">{{ module.niveau }}</p>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" @click="closeCompetenceModal" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapMutations } from 'vuex';
import debounce from 'lodash/debounce';

export default {
  data() {
    return {
      nameFilter: "",
      roleFilter: "",
      statusFilter: "",
      localSelectedAgency: "",
    };
  },
  computed: {
    ...mapState('listPs', ['users', 'agencies', 'showCompetenceModal', 'selectedUser', 'selectedAgency', 'currentPage', 'itemsPerPage']),
    filteredUsers() {
      return this.users.filter(user => {
        const matchesName = this.nameFilter ? user.name.toLowerCase().includes(this.nameFilter.toLowerCase()) : true;
        const matchesRole = this.roleFilter ? this.getRoleName(user.type).toLowerCase().includes(this.roleFilter.toLowerCase()) : true;
        const matchesStatus = this.statusFilter ? (user.active ? 'bloquer' : 'activer').includes(this.statusFilter.toLowerCase()) : true;
        const matchesAgency = this.localSelectedAgency ? user.agence_id === this.localSelectedAgency : true;
        return matchesName && matchesRole && matchesStatus && matchesAgency;
      });
    },
    paginatedUsers() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredUsers.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredUsers.length / this.itemsPerPage);
    },
    groupedCompetences() {
      const grouped = {};
      this.selectedUser.user_competences.forEach(competence => {
        const domaine = competence.domaine_competence.nom;
        if (!grouped[domaine]) {
          grouped[domaine] = { domaine, modules: [] };
        }
        grouped[domaine].modules.push({
          id: competence.module.id,
          nom: competence.module.nom,
          niveau: competence.niveau_competence.niveau,
        });
      });
      return Object.values(grouped);
    },
  },
  watch: {
    nameFilter() {
      this.debouncedSearch();
    },
    roleFilter() {
      this.debouncedSearch();
    },
    statusFilter() {
      this.debouncedSearch();
    },
    localSelectedAgency() {
      this.filterByAgency();
    },
  },
  mounted() {
    this.fetchUsers();
    this.fetchAgencies();
  },
  methods: {
    ...mapActions('listPs', ['fetchUsers', 'fetchAgencies', 'toggleUserStatus']),
    ...mapMutations('listPs', ['setCurrentPage', 'setSelectedAgency']),
    debouncedSearch: debounce(function() {
      this.fetchUsers();
    }, 300),
    filterByAgency() {
      this.setSelectedAgency(this.localSelectedAgency);
      this.fetchUsers();
    },
    goToUserCalendar(userId) {
      this.$router.push({ name: "userCalendar", params: { userId } });
    },
    openCompetenceModal(user) {
      this.$store.commit('listPs/setSelectedUser', user);
      this.$store.commit('listPs/setShowCompetenceModal', true);
    },
    closeCompetenceModal() {
      this.$store.commit('listPs/setShowCompetenceModal', false);
    },
    getRoleName(type) {
      switch (type) {
        case 0:
          return 'Consultant Technique';
        case 1:
          return 'Consultant Fonctionnel';
        case 2:
          return 'Consultant FullStack';
        case 3:
          return 'Chef De Projet';
        case 4:
          return 'Manager';
        case 5:
          return 'Administrateur';
        default:
          return '';
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.setCurrentPage(this.currentPage - 1);
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.setCurrentPage(this.currentPage + 1);
      }
    },
  },
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>
