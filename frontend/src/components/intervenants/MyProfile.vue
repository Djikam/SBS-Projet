<template>
  <div>
    <div class="w-full max-w-full max-h-full p-4 overflow-hidden bg-white rounded-lg shadow-xl">
      <!-- User Info -->
      <div class="flex justify-between gap-6 p-2">
        <router-link to="/" class="flex items-center gap-2 px-4 py-2 pt-2 text-gray-700 transition-transform duration-300 transform bg-gray-300 rounded-full shadow-lg hover:scale-105">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z" clip-rule="evenodd" />
          </svg>
          Retour
        </router-link>
        
      </div>
    </div>
    <div class="container flex items-center justify-center w-auto p-2 mx-auto mt-20 md:p-0">
      <div class="grid grid-cols-1 gap-6 p-6 bg-white border border-gray-300 rounded-lg shadow-lg md:w-[1470px]">
        <div class="flex flex-col md:flex-row">
          <div class="bg-info hover:bg-infoActive text-white text-3xl py-2.5 px-5 mr-2 mb-2 rounded-xl transition duration-300">
            Mes informations
          </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
              </svg>
              <div class="flex-grow max-w-full text-gray-700 bg-gray-300">
                {{ user.name }}
              </div>
            </div>
          </div>
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M19.5 22.5a3 3 0 0 0 3-3v-8.174l-6.879 4.022 3.485 1.876a.75.75 0 1 1-.712 1.321l-5.683-3.06a1.5 1.5 0 0 0-1.422 0l-5.683 3.06a.75.75 0 0 1-.712-1.32l3.485-1.877L1.5 11.326V19.5a3 3 0 0 0 3 3h15Z" />
                <path d="M1.5 9.589v-.745a3 3 0 0 1 1.578-2.642l7.5-4.038a3 3 0 0 1 2.844 0l7.5 4.038A3 3 0 0 1 22.5 8.844v.745l-8.426 4.926-.652-.351a3 3 0 0 0-2.844 0l-.652.351L1.5 9.589Z" />
              </svg>
              <div class="flex-grow max-w-full text-gray-700 bg-gray-300">
                {{ user.email }}
              </div>
            </div>
          </div>
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
              </svg>
              <div v-if="user.type == 0" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Consultant Technique
              </div>
              <div v-if="user.type == 1" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Consultant Fonctionnel
              </div>
              <div v-if="user.type == 2" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Consultant FullStack
              </div>
              <div v-if="user.type == 3" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Chef De Projet
              </div>
              <div v-if="user.type == 4" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Manager
              </div>
              <div v-if="user.type == 5" class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Administrateur
              </div>
            </div>
          </div>
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M19.006 3.705a.75.75 0 1 0-.512-1.41L6 6.838V3a.75.75 0 0 0-.75-.75h-1.5A.75.75 0 0 0 3 3v4.93l-1.006.365a.75.75 0 0 0 .512 1.41l16.5-6Z" />
                <path fill-rule="evenodd" d="M3.019 11.114 18 5.667v3.421l4.006 1.457a.75.75 0 1 1-.512 1.41l-.494-.18v8.475h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3v-9.129l.019-.007ZM18 20.25v-9.566l1.5.546v9.02H18Zm-9-6a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75H9Z" clip-rule="evenodd" />
              </svg>
              <div class="flex-grow max-w-full text-gray-700 bg-gray-300">
                {{ user.agence ? user.agence.name : 'Aucune agence assignée' }}
              </div>
            </div>
          </div>
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10S2 17.52 2 12zm10 15c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-3h2v-2h-2v2zm-2 0h-2v-2h2v2z" />
              </svg>
              <div class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Congé: {{ user.conge }}
              </div>
            </div>
          </div>
          <div class="gap-2 p-2 border border-gray-200 rounded">
            <div class="flex items-center p-2 bg-gray-300 border rounded">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10S2 17.52 2 12zm10 15c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-3h2v-2h-2v2zm-2 0h-2v-2h2v2z" />
              </svg>
              <div class="flex-grow max-w-full text-gray-700 bg-gray-300">
                Récupération: {{ user.recuperation }}
              </div>
            </div>
          </div>
        </div>
        <div class="bg-primary hover:bg-primaryActive text-white text-3xl py-2.5 px-5 mr-2 mb-2 rounded-xl transition duration-300">
          Mes compétences
        </div>
        <div class="p-0 overflow-y-scroll">
          <table class="w-full mt-4 text-center table-auto min-w-max">
            <thead>
              <tr>
                <th class="p-2 text-xs font-semibold text-gray-700 bg-gray-200 border-gray-300 border-y">Domaine</th>
                <th class="p-2 text-xs font-semibold text-gray-700 bg-gray-200 border-gray-300 border-y">Module</th>
                <th class="p-2 text-xs font-semibold text-gray-700 bg-gray-200 border-gray-300 border-y">Niveau</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(competence, index) in user.domaines" :key="index">
                <td class="p-2 text-xs font-semibold text-gray-700 border-b border-gray-300">
                  {{ competence.domaine_nom }}
                </td>
                <td class="p-2 text-xs font-semibold text-gray-700 border-b border-gray-300">
                  {{ competence.module_nom }}
                </td>
                <td class="p-2 text-xs font-semibold text-gray-700 border-b border-gray-300">
                  {{ competence.niveau_competence }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/config'; // Importer API_URL

export default {
  data() {
    return {
      user: {
        name: '',
        email: '',
        type: '',
        agence: null,
        domaines: [],
        conge: 0,
        recuperation: 0,
      }
    };
  },
  mounted() {
    this.fetchUserData();
  },
  computed: {
    getCalendarRoute() {
      const userType = this.user.type;
      switch (userType) {
        case '0':
        case '1':
        case '2':
          return '/Mycalendar';
        case '3':
          return '/staff';
        case '4':
        case '5':
          return '/projet';
        default:
          return '/';
      }
    }
  },
  methods: {
    async fetchUserData() {
      try {
        const response = await axios.get(`${API_URL}/user`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.user = {
          name: response.data.name,
          email: response.data.email,
          type: response.data.type,
          agence: {
            id: response.data.agence_id,
            name: response.data.agence_name
          },
          domaines: response.data.domaines_competence,
          conge: response.data.conge,
          recuperation: response.data.recuperation,
        };
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    }
  }
};
</script>

<style scoped>
/* Add any additional styles here */
.text-xs {
  font-size: 9px;
}

.bg-info {
  background-color: #007bff;
}

.bg-infoActive {
  background-color: #0056b3;
}

.bg-primary {
  background-color: #6c757d;
}

.bg-primaryActive {
  background-color: #5a6268;
}

.hover\:bg-infoActive:hover {
  background-color: #0056b3;
}

.hover\:bg-primaryActive:hover {
  background-color: #5a6268;
}
</style>
