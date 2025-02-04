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
              <router-link
                to="/addps"
                class="flex select-none items-center gap-2 rounded bg-slate-800 py-2.5 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                type="button"
              >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" stroke-width="2" class="w-4 h-4">
                  <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
                </svg>
                Ajouter Consultant
              </router-link>

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
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Staffing</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Compétences</th>
                <th class="p-4 transition-colors cursor-pointer border-y border-slate-200 bg-slate-50 hover:bg-slate-100">Modifier</th>
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
                    <button :class="{'bg-red-500/20': user.active, 'bg-green-500/20': !user.active}" @click="toggleUserStatus(user)" class="relative grid items-center w-24 px-2 py-1 font-sans text-xs font-bold text-center uppercase rounded-md cursor-pointer select-none whitespace-nowrap">
                      <p class="text-sm font-semibold capitalize text-slate-700">{{ user.active ? "Bloquer ?" : "Activer ?" }}</p>
                    </button>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <div>
                    <button @click="goToUserCalendar(user.id)" class="flex select-none items-center gap-2 rounded bg-slate-800 py-2.5 px-4 text-xs font-semibold text-white shadow-md shadow-slate-900/10 transition-all hover:shadow-lg hover:shadow-slate-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                      Planning
                    </button>
                  </div>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <button @click="openCompetenceModal(user)" class="text-blue-500 hover:underline">Compétences</button>
                </td>
                <td class="p-4 border-b border-slate-200">
                  <router-link :to="`/updateps/${user.id}`">
                    <button class="relative h-10 max-h-[40px] w-10 max-w-[40px] select-none rounded-lg text-center align-middle font-sans text-xs font-medium uppercase text-slate-900 transition-all hover:bg-slate-900/10 active:bg-slate-900/20 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                      <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="w-4 h-4">
                          <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z"></path>
                        </svg>
                      </span>
                    </button>
                  </router-link>
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
