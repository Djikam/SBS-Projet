<template>
  <div class="container p-4 mx-auto">
    <h1 class="mb-4 text-2xl font-bold">Audit des Connexions</h1>
    <div class="flex justify-between mb-4">
      <input v-model="searchQuery" @input="filterAudits" class="px-4 py-2 border rounded-lg" placeholder="Rechercher par email" />
      <select v-model="filterResult" @change="filterAudits" class="px-4 py-2 border rounded-lg">
        <option value="">Tous</option>
        <option value="1">Réussi</option>
        <option value="0">Échoué</option>
      </select>
    </div>
    <table class="min-w-full bg-white border rounded-lg shadow-md">
      <thead>
        <tr class="bg-gray-200">
          <th class="px-4 py-2 border-b">Email</th>
          <th class="px-4 py-2 border-b">Adresse IP</th>
          <th class="px-4 py-2 border-b">Résultat</th>
          <th class="px-4 py-2 border-b">Message</th>
          <th class="px-4 py-2 border-b">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="audit in paginatedAudits" :key="audit.id" class="hover:bg-gray-100">
          <td class="px-4 py-2 border-b">{{ audit.email }}</td>
          <td class="px-4 py-2 border-b">{{ audit.ip_address }}</td>
          <td class="px-4 py-2 border-b">{{ audit.success ? 'Réussi' : 'Échoué' }}</td>
          <td class="px-4 py-2 border-b">{{ audit.message }}</td>
          <td class="px-4 py-2 border-b">{{ audit.created_at }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="flex items-center justify-between p-2 mt-4">
      <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Précédent</button>
      <span>Page {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-gray-700 bg-gray-300 rounded-full shadow-lg disabled:opacity-50">Suivant</button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
  name: 'LoginAudit',
  data() {
    return {
      searchQuery: '',
      filterResult: '',
      currentPage: 1,
      itemsPerPage: 10,
    };
  },
  computed: {
    ...mapState('loginAudit', ['audits']),
    filteredAudits() {
      return this.audits.filter(audit => {
        const matchesSearch = audit.email.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesFilter = this.filterResult === '' || audit.success.toString() === this.filterResult;
        return matchesSearch && matchesFilter;
      });
    },
    paginatedAudits() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredAudits.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredAudits.length / this.itemsPerPage);
    },
  },
  mounted() {
    this.fetchAudits();
  },
  methods: {
    ...mapActions('loginAudit', ['fetchAudits']),
    filterAudits() {
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
/* Ajoutez vos styles personnalisés ici */
</style>
