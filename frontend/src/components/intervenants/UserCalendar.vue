
    <template>
      <div class="container p-4 mx-auto">
        <div class="flex items-end justify-end p-2">
          <router-link
            to="/ListPS"
            class="px-4 py-2 text-white bg-gray-500 rounded shadow-lg"
          >
            Retour
          </router-link>
        </div>
        <!-- Conteneur principal pour le calcul du bench et la recherche -->
        <div class="flex items-center justify-between mb-2">
          <!-- Formulaire pour le calcul du BANGE -->
          <div class="mb-1">
            <h3 class="mb-0   font-medium text-gray-800 text-[9px] uppercase">
              Calcul du Bench par période
            </h3>
            <div class="flex items-center p-2 space-x-6 rounded-lg shadow-md bg-gray-50">
              <div class="flex items-center space-x-2">
                <label for="bange-start-date" class="text-sm font-medium text-gray-700 text-[9px]">Date de début:</label>
                <input id="bange-start-date" v-model="bangeStartDate" type="date" class="p-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 text-[10px]" />
              </div>
              <div class="flex items-center space-x-2">
                <label for="bange-end-date" class="text-sm font-medium text-gray-700 text-[9px]">Date de fin:</label>
                <input id="bange-end-date" v-model="bangeEndDate" type="date" class="p-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 text-[10px]" />
              </div>
              <button @click="calculateAndDisplayBANGE" class="p-2 text-[9px] text-white bg-blue-500 rounded shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Calculer le Bench
              </button>
            </div>
          </div>
    
          <!-- Modal pour afficher les résultats du BANGE -->
          <div v-if="isBANGEModalOpen" class="fixed inset-0 z-[70] flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 bg-white rounded-lg">
              <h2 class="mb-4 text-xl font-bold">Résultats du Bench</h2>
              <div class="overflow-y-auto max-h-60">
                <table class="w-full">
                  <thead>
                    <tr>
                      <th class="px-4 py-2 font-semibold text-left bg-gray-100">Période</th>
                      <th class="px-4 py-2 font-semibold text-right bg-gray-100">Etp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="result in bangeResults" :key="result.date" class="border-b">
                      <td class="px-4 py-2">{{ result.date }}</td>
                      <td class="px-4 py-2 text-right">{{ result.bange }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button @click="closeBANGEModal" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Fermer
              </button>
            </div>
          </div>
    
          <!-- Formulaire de recherche -->
          <div class="mb-1">
            <h3 class="mb-0  font-medium text-gray-800 text-[9px] uppercase">Recherche des Consultants en TNF </h3>
            <div class="flex items-center p-1 space-x-6 rounded-lg shadow-md bg-gray-50">
              <div class="flex items-center space-x-2">
                <label for="start-search" class="text-sm font-medium text-gray-700 text-[9px]">Date de début:</label>
                <input id="start-search" v-model="searchStartDate" type="date" class="p-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 text-[9px]" />
              </div>
              <div class="flex items-center space-x-2">
                <label for="end-search" class="text-sm font-medium text-gray-700 text-[9px]">Date de fin:</label>
                <input id="end-search" v-model="searchEndDate" type="date" class="p-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 text-[9px]" />
              </div>
              <button @click="clearDateFilters" class="p-2 text-[9px] text-white bg-red-500 rounded shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                Effacer les filtres
              </button>
            </div>
          </div>
        </div>
        <div class="flex justify-center p-2 space-x-4">
          <!-- Modal pour sélectionner la période et le format d'exportation -->
          <div v-if="isExportModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-[70]">
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
              <h2 class="mb-4 text-xl font-bold">Exporter les données</h2>
              <div class="mb-4">
                <label for="start-date" class="block text-sm font-medium text-gray-700">Date de début :</label>
                <input type="date" v-model="startDate" id="start-date" class="p-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
              </div>
              <div class="mb-4">
                <label for="end-date" class="block text-sm font-medium text-gray-700">Date de fin :</label>
                <input type="date" v-model="endDate" id="end-date" class="p-2 mt-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
              </div>
              <div class="flex justify-between gap-2">
                <button @click="exportToXLSX" class="px-4 py-2 text-xs text-white bg-green-500 rounded">Exporter en XLSX</button>
                <button @click="exportToPDF" class="px-4 py-2 text-xs text-white bg-blue-500 rounded">Exporter en PDF</button>
                <button @click="closeExportModal" class="px-4 py-2 text-xs text-white bg-red-500 rounded">Fermer</button>
              </div>
            </div>
          </div>
          <!-- Legend Container -->
          <div class="pt-2">
            <h4 class="mb-2 font-medium text-[9px] uppercase">Statistique par Période</h4>
            <div class="flex flex-wrap gap-2">
              <div v-for="(stat, color) in statisticsByColor" :key="color" class="flex items-center p-2 space-x-2 bg-white rounded shadow-md">
                <div class="flex items-center justify-center h-4 p-1" :class="stat.class">
                  <small class="text-[8px] text-white">{{ stat.count }} / {{ totalInterventionDays }} j</small>
                </div>
                <span class="text-[8px] text-black">{{ stat.name }}</span>
                
              </div>
              
            <div class="flex items-center p-2 space-x-2 bg-red-500 rounded shadow-md">
              <div class="flex items-center justify-center h-4 p-1">
                <small class="text-[8px] text-white">{{ totalTNF }} j</small>
              </div>
              <span class="text-[8px] text-white">Total TNF</span>
            </div>
            <div v-for="(role, index) in consultantsByRole" :key="index" class="flex items-center p-2 space-x-2 bg-blue-500 rounded shadow-md ">
              <div class="flex items-center justify-center h-4 p-1" :class="getClientColor(index)">
                <small class="text-[8px] text-white">{{ role.count }} / {{ totalConsultants }}</small>
              </div>
              <span class="text-[8px] text-white">{{ role.name }}</span>
            </div>
            
              
            </div>
           
          </div>
        </div>
        <div class="flex justify-between p-3 mt-1 bg-blue-500 rounded-t-md">
          <div class="flex items-center justify-">
            <div>
              <button @click="prevPeriod" class="px-4 py-2 text-white bg-blue-500 rounded shadow-lg text-[10px]">
                Précédent
              </button>
              <button @click="resetToToday" class="px-4 py-2 ml-4 text-white bg-green-500 rounded shadow-lg text-[10px]">
                Mois en cours
              </button>
              <button @click="nextPeriod" class="px-4 py-2 ml-4 text-white bg-blue-500 rounded shadow-lg text-[10px]">
                Suivant
              </button>
              <input id="client-search" v-model="clientSearch" type="text" class="px-4 py-2 ml-4 border border-gray-300 rounded-md focus:ring focus:ring-blue-300 text-[10px]" placeholder="Search with client name's" />
            </div>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-white">{{ displayPeriod }}</h2>
          </div>
          <div class="flex">
            <button @click="viewMode = 'month'" :class="{ 'bg-white text-black': viewMode === 'month' }" class="px-4 py-2 transition-colors duration-200 rounded shadow-lg text-[10px]">
              Mois
            </button>
            <button @click="viewMode = 'year'" :class="{ 'bg-white text-black': viewMode === 'year' }" class="px-4 py-2 ml-2 transition-colors duration-200 rounded shadow-lg text-[10px]">
              Année
            </button>
            <button @click="openExportModal" class="px-4 py-2 ml-2 text-xs text-black rounded bg-gray-50">Exporter</button>
          </div>
        </div>
    <!-- Vue Mensuelle -->
    <div v-if="viewMode === 'month'" class="overflow-x-auto overflow-y-scroll md:h-[400px]">
      <table class="min-w-full border border-gray-300 table-auto">
        <thead class="sticky z-50 text-white bg-blue-500">
          <tr class="sticky z-50 bg-blue-500">
            <th class="sticky left-0 w-1/4 p-2 text-[9px] z-[70] bg-blue-500 rounded-l-lg">
              Ressources
            </th>
            <th v-for="(day, index) in periodDays" :key="index" class="sticky top-0 p-2 border-b bg-blue-500 z-50 border-gray-300 w-[50px] text-[9px]">
              {{ getDayLabel(index, day) }}
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- Bench Row -->
          <tr class="bench-row">
            <td class="sticky left-0 p-2 bg-white text-[9px] font-bold z-50">
              Bench
            </td>
            <td v-for="(day, index) in periodDays" :key="index" class="p-2 border-b border-gray-300 w-[50px] text-[9px]">
              {{ getWeeklyBenchForDay(day) }}
            </td>
          </tr>
          <!-- Resource rows -->
          <tr v-for="resource in filteredResources" :key="resource.name">
            <td class="sticky left-0 p-1 bg-white text-[10px] z-50">
              {{ resource.name }}
            </td>
            <td v-for="(day, index) in periodDays" :key="index" class="p-1 border-b border-gray-300 w-[50px] text-[9px]" :class="{
                'bg-red-400': isJourFerie(day),
                'bg-gray-300': isWeekend(day).isWeekend,
              }" @click="openEventModal(day, resource.name)">
              <!-- Holiday Display -->
              <div v-if="isJourFerie(day)" class="text-xs font-bold text-white">
                <div class="w-[50px] h-6 p-1 text-[6px] text-white bg-red-400 rounded">
                  {{ getJourFerieName(day, monthIndex) }}
                </div>
              </div>
              <!-- Weekend Display -->
              <div v-else-if="isWeekend(day).isWeekend" class="w-full h-full p-1 text-[9px] text-black bg-gray-300 rounded">
                <template v-if="filteredEventsByDay[resource.name][day].length">
                  <div v-for="event in filteredEventsByDay[resource.name][day]" :key="event.id" class="relative w-[50px] h-6 p-1 text-[9px] text-center rounded cursor-pointer" :class="getClientColor(event.color)" @mouseover="showComment(event, day)" @mouseleave="hideComment">
                    {{ event.clientAbbreviation }}
                  </div>
                </template>
                <template v-else><p class="text-[9px]">weekend</p></template>
              </div>
              <!-- Normal Day Display (not holiday, not weekend) -->
              <div v-else>
                <template v-if="filteredEventsByDay[resource.name][day].length">
                  <div v-for="event in filteredEventsByDay[resource.name][day]" :key="event.id" class="relative w-[50px] h-6 p-1 text-[9px] text-center rounded cursor-pointer" :class="getClientColor(event.color)" @mouseover="showComment(event, day)" @mouseleave="hideComment">
                    {{ event.clientAbbreviation }}
                    <div v-if="hoveredEvent === event && hoveredDay === day" class="absolute z-10 p-2 text-sm text-white whitespace-normal bg-gray-800 rounded shadow-lg" style="
                        width: 200px;
                        bottom: 100%;
                        left: 50%;
                        transform: translateX(-50%);
                      ">
                      {{ event.name }}
                    </div>
                  </div>
                </template>
                <!-- TNF for normal days without events -->
                <template v-else>
                  <div v-if="!isJourFerie(day)" class="h-6 p-1 text-[9px] text-white bg-green-400 rounded cursor-pointer hover:bg-green-600" :class="{
                      'border-2 border-blue-600': isHighlightedTNF(resource.name, day),
                    }">
                    TNF
                  </div>
                </template>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
        <!-- Vue Annuelle -->
        <div v-else-if="viewMode === 'year'" class="overflow-x-auto overflow-y-scroll md:h-[400px]">
          <div class="relative overflow-x-auto">
            <table class="min-w-full border border-gray-900 table-auto">
              <thead class="sticky top-0 z-10 text-white bg-blue-500">
                <tr>
                  <th class="sticky left-0 z-50 w-1/4 p-1 bg-blue-500 rounded-l-lg">Ressources</th>
                  <th v-for="month in monthsOfYear" :key="month" class="p-1 border border-gray-300">
                    <div>{{ month }}</div>
                    <div class="flex row-auto gap-1">
                      <div v-for="day in getDaysInMonth(monthsOfYear.indexOf(month))" :key="`${month}-${day}`" class="text-[9px] p-1 w-[50px] mr-[8px]">
                        {{ getDayAbbreviation(new Date(currentYear, monthsOfYear.indexOf(month), day)) }}
                        {{ String(day).padStart(2, "0") }}
                      </div>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody class="overflow-x-auto">
                <!-- Bench Row pour la vue annuelle -->
                <tr class="">
                  <td class="sticky left-0 p-2 bg-white text-[9px] font-bold gap-2 ">Bench</td>
                  <td v-for="(month, monthIndex) in getDaysInYear()" :key="monthIndex" class="p-1 border border-gray-300 text-[9px]">
                    <div class="flex gap-[2px]">
                      <div v-for="day in month.days" :key="day" class="mx-[5px] text-[9px] text-center bg-transparent w-[50px] h-6 gap-3 rounded-sm">
                        <!-- Ajout de la condition pour les jours fériés -->
                        <div v-if="isJourFerie(day, monthIndex)">
                          <div class="w-[50px] h-6 p-1 text-[1px] text-white bg-transparent rounded">
                            {{ getJourFerieName(day, monthIndex) }}
                          </div>
                        </div>
                        <div v-else-if="isWeekend(day, monthIndex).isWeekend">
                          <div class="w-[50px] h-6 p-1 text-[1px] text-center text-white bg-transparent rounded">
                            weekend
                          </div>
                        </div>
                        <div v-else>
                          <!-- Afficher le BANGE pour chaque mercredi en vérifiant directement le jour -->
                          <div v-if="new Date(currentYear, monthIndex, day).getDay() === 3" class="w-[50px] h-6 p-1 text-[9px] text-white bg-cyan-400 rounded cursor-pointer hover:bg-green-600">
                            {{ getWeeklyBANGE(day, monthIndex).bange }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr v-for="resource in filteredResources" :key="resource.name">
                  <td class="sticky left-0  p-1 bg-white border text-[9px]">
                    {{ resource.name }}
                  </td>
                  <td v-for="(month, monthIndex) in getDaysInYear()" :key="monthIndex" class="p-1 border border-gray-300 text-[9px]">
                    <div class="flex gap-1">
                      <div v-for="day in month.days" :key="day" class="p-1 text-[9px] text-center rounded-lg" @click="openEventModal(day, resource.name, monthIndex)">
                        <!-- Ajout de la condition pour les jours fériés -->
                        <div v-if="isJourFerie(day, monthIndex)">
                          <div class="w-[50px] h-6 p-1 text-[6px] text-white bg-red-400 rounded">
                            {{ getJourFerieName(day, monthIndex) }}
                          </div>
                        </div>
                        <div v-else-if="isWeekend(day, monthIndex).isWeekend">
                          <div class="w-[50px] h-6 p-1 text-[9px] text-center bg-gray-300 rounded">
                            <template v-if="getEventsForMonth(resource.name, monthIndex, day).length">
                              <div v-for="event in getEventsForMonth(resource.name, monthIndex, day)" :key="event.id" class="relative text-center rounded cursor-pointer w-[50px] h-6 p-1 text-[9px]" :class="getClientColor(event.color)" @mouseover="showComment(event, day)" @mouseleave="hideComment">
                                {{ event.clientAbbreviation }}
                                <div v-if="hoveredEvent === event && hoveredDay === day" class="absolute z-10 p-1 text-[9px] text-white whitespace-normal bg-gray-800 rounded shadow-lg" style="
                                  width: 200px;
                                  bottom: 100%;
                                  left: 50%;
                                  transform: translateX(-50%);
                                ">
                                  {{ event.name }}
                                </div>
                              </div>
                            </template>
                            <template v-else>weekend</template>
                          </div>
                        </div>
                        <div v-else>
                          <template v-if="getEventsForMonth(resource.name, monthIndex, day).length">
                            <div v-for="event in getEventsForMonth(resource.name, monthIndex, day)" :key="event.id" class="relative w-[50px] h-6 p-1 text-[9px] text-center rounded cursor-pointer" :class="getClientColor(event.color)" @mouseover="showComment(event, day)" @mouseleave="hideComment">
                              {{ event.clientAbbreviation }}
                              <div v-if="hoveredEvent === event && hoveredDay === day" class="absolute z-10 w-[50px] h-6 p-1 text-[9px] text-white whitespace-normal bg-gray-800 rounded shadow-lg" style="
                                width: 200px;
                                bottom: 100%;
                                left: 50%;
                                transform: translateX(-50%);
                              ">
                                {{ event.name }}
                              </div>
                            </div>
                          </template>
                          <template v-else>
                            <div v-if="!isJourFerie(day, monthIndex)" class="w-[50px] h-6 p-1 text-[9px] text-white bg-green-400 rounded cursor-pointer hover:bg-green-600" :class="{
                              'border-2 border-blue-600': isHighlightedTNF(resource.name, day, monthIndex),
                            }">
                              TNF
                            </div>
                          </template>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Modal pour ajouter/modifier un événement -->
        <div v-if="isEventModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black bg-opacity-50 backdrop-blur-sm" @click.self="closeEventModal">
          <div class="w-full max-w-2xl p-6 transition-transform duration-300 ease-in-out transform scale-75 bg-white rounded-lg shadow-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">
              Ressource : {{ selectedEvent.resourceName }}
            </h3>
            <div class="mt-4">
              <label for="client" class="block text-sm font-medium text-gray-700">Mission:</label>
              <div>
                <!-- Barre de recherche pour filtrer les clients -->
                <input type="text" v-model="clientSearch" placeholder="Rechercher un client" class="w-full p-2 mb-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:border-blue-500" />
                <!-- Sélecteur de clients -->
                <select id="client" v-model="selectedClientId" @change="updateSelectedClient" class="w-full p-3 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:border-blue-500">
                  <option value="">Sélectionnez un client</option>
                  <option v-for="client in filteredClients" :key="client.id" :value="client.id">
                    {{ client.nom }}
                  </option>
                </select>
              </div>
            </div>
            <div class="mt-4">
              <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire:</label>
              <textarea id="comment" v-model="comment" class="w-full p-3 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:border-blue-500" placeholder="Entrez un commentaire"></textarea>
            </div>
            <div class="flex justify-between mt-4">
              <div class="w-full pr-2">
                <label for="start-date" class="block text-sm font-medium text-gray-700">Date de début:</label>
                <input id="start-date" type="date" v-model="startDate" class="w-full p-3 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:border-blue-500" />
              </div>
              <div class="w-full pl-2">
                <label for="end-date" class="block text-sm font-medium text-gray-700">Date de fin:</label>
                <input id="end-date" type="date" v-model="endDate" class="w-full p-3 transition duration-150 ease-in-out border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:border-blue-500" />
              </div>
            </div>
            <div class="flex space-x-4">
              <button v-if="selectedEvent.id" @click="updateEvent" class="w-full px-4 py-3 mt-6 text-white transition duration-150 ease-in-out bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-300">
                Mettre à jour l'intervention
              </button>
              <button v-else @click="saveEvent" class="w-full px-4 py-3 mt-6 text-white transition duration-150 ease-in-out bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                Enregistrer l'intervention
              </button>
              <!-- Button to delete the event -->
              <button v-if="selectedEvent.id" @click="deleteEvent" class="w-full px-4 py-3 mt-6 text-white transition duration-150 ease-in-out bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300">
                Supprimer l'intervention
              </button>
              <button @click="closeEventModal" class="w-full px-4 py-3 mt-6 text-white transition duration-150 ease-in-out bg-gray-500 rounded-md hover:bg-gray-600 focus:outline-none focus:ring focus:ring-gray-300">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>

<script>
import { format } from "date-fns";
import axios from "axios";
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import ExcelJS from 'exceljs';
import { API_URL } from '@/config'; // Importer API_URL// Assurez-vous que le chemin est correct

export default {
  props: ["userId"],
  data() {
    return {
      colorMapping: {
        1: { name: 'Mission confirmée', class: 'bg-orange-400' },
        2: { name: 'Prévision de Mission', class: 'bg-yellow-400' },
        3: { name: 'Voyage', class: 'bg-sky-400' },
        4: { name: 'Congé', class: 'bg-blue-300' },
        5: { name: 'Formation', class: 'bg-blue-500' },
        6: { name: 'Récupération', class: 'bg-red-200' },
        7: { name: 'Congé maladie', class: 'bg-purple-600' },
        8: { name: 'Université', class: 'bg-sky-600' },
        9: { name: 'Avant Vente', class: 'bg-teal-300' },
        10: { name: 'Prévision de Congé', class: 'bg-orange-600' }, // Nouvelle couleur pour Prévision de Mission
        11: { name: 'Prévision de Récupération', class: 'bg-indigo-400' }, // Nouvelle couleur pour Prévision de Récupération
        12: { name: 'Prévision de Formation', class: 'bg-pink-400' }, // Nouvelle couleur pour Prévision de Formation
        default: { name: 'Inconnu', class: 'bg-blue-400' }
      },
      isExportModalOpen: false,
      currentYear: new Date().getFullYear(),
      currentMonth: new Date().getMonth(),
      monthsOfYear: [
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Août",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre",
      ],
      currentDay: new Date().getDate(),
      viewMode: "month", //  month or year
      resources: [],
      events: [],
      selectedClientId: "",
      clientSearch: "", // Barre de recherche pour le filtre
      clients: [], // Assurez-vous que `clients` contient les données de vos clients
      joursFeries: [],
      agences: [],
      bangeResults: [],
      isBANGEModalOpen: false,
      bangeStartDate: "",
      bangeEndDate: "",
      searchStartDate: "",
      searchEndDate: "",
      tnfSearchResults: [],
      isEventModalOpen: false,
      selectedEvent: {},
      hoveredEvent: null,
      hoveredDay: null,
      selectedClientColor: "",
      comment: "",
      startDate: "",
      endDate: "",
      user_id: null,
      selectedPays: "",
      
    };
  },
  computed: {
 consultantsByRole() {
    if (!this.resources || this.resources.length === 0) {
      return {};
    }

    const roles = {
      0: 'Consultant technique',
      1: 'Consultant fonctionnel',
      2: 'Consultant Fullstack',
      3: 'Chef de projet',
      4: 'Manager'
    };

    const consultantsCount = this.resources.reduce((acc, resource) => {
      const role = resource.type;
      if (!acc[role]) {
        acc[role] = { name: roles[role], count: 0 };
      }
      acc[role].count++;
      return acc;
    }, {});

    return consultantsCount;
  },
  totalConsultants() {
    return this.resources.length;
  },
  totalInterventionDays() {
    let total = 0;
    this.filteredEvents.forEach(event => {
      const eventStart = new Date(event.startDate);
      const eventEnd = new Date(event.endDate);
      total += this.calculateInterventionDays(eventStart, eventEnd, this.viewMode);
    });
    return total;
  },
  totalTNF() {
    let total = 0;
    const startDate = this.viewMode === 'month' ? new Date(this.currentYear, this.currentMonth, 1) : new Date(this.currentYear, 0, 1);
    const endDate = this.viewMode === 'month' ? new Date(this.currentYear, this.currentMonth + 1, 0) : new Date(this.currentYear, 11, 31);

    this.resources.forEach(resource => {
      total += this.getTNFDaysInPeriod(resource, startDate, endDate).length;
    });

    return total;
  },
    filteredEvents() {
    let filtered = this.events;

    // Filtrer par agence sélectionnée
    if (this.selectedPays) {
      filtered = filtered.filter(event => {
        const resource = this.resources.find(r => r.name === event.resource);
        return resource && resource.agence && resource.agence.pays === this.selectedPays;
      });
    }

    return filtered;
  },
   

 
    filteredClients() {
      return this.clients.filter((client) =>
        client.nom.toLowerCase().includes(this.clientSearch.toLowerCase())
      );
    },
    displayPeriod() {
      if (this.viewMode === "month") {
        const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1);
        return format(firstDayOfMonth, "MMMM yyyy");
      } else if (this.viewMode === "year") {
        const firstDayOfYear = new Date(this.currentYear, 0, 1);
        return format(firstDayOfYear, "yyyy");
      } else {
        return `${this.currentYear}`;
      }
    },
    formattedJoursFeries() {
      // Format the public holidays to only include the date in 'yyyy-MM-dd' format
      return this.joursFeries.map((jourFerie) => ({
        date: format(new Date(jourFerie.date_ferie), "yyyy-MM-dd"), // Keep the date format
        name: jourFerie.nom_ferie,
      }));
    },
    periodDays() {
      return this.viewMode === "month" ? this.getDaysInMonth() : this.getDaysInYear();
    },
    filteredResources() {
      let filtered = this.resources;

      // Filter by client name
      if (this.clientSearch) {
        // Find resource IDs that have interventions matching the client name
        const matchingResourceIds = new Set(
          this.events
            .filter((event) =>
              event.clientName.toLowerCase().includes(this.clientSearch.toLowerCase())
            )
            .map((event) => event.resourceId)
        );

        // Filter resources based on matching IDs
        filtered = this.resources.filter((resource) =>
          matchingResourceIds.has(resource.id)
        );
      }

      // Filter by date range
      if (this.searchStartDate && this.searchEndDate) {
        const startDate = new Date(this.searchStartDate);
        const endDate = new Date(this.searchEndDate);
        filtered = filtered.filter((resource) => {
          const tnfDays = this.getTNFDaysInPeriod(resource, startDate, endDate);
          return tnfDays.length > 0;
        });
      }

      // Filter by selected agency
      if (this.selectedPays) {
        filtered = filtered.filter(
          (resource) => resource.agence && resource.agence.pays === this.selectedPays
        );
      }

      return filtered;
    },
    // Nouvelle propriété calculée pour récupérer les événements par jour et ressource
    filteredEventsByDay() {
      const filteredEvents = {};

      this.filteredResources.forEach((resource) => {
        filteredEvents[resource.name] = {};

        const days =
          this.viewMode === "month"
            ? this.getDaysInMonth()
            : this.getDaysInYear().flatMap((month) => month.days);

        days.forEach((day) => {
          const dayDate = new Date(
            this.currentYear,
            this.viewMode === "month" ? this.currentMonth : 0,
            typeof day === "number" ? day : day.day
          );

          filteredEvents[resource.name][day] = this.events.filter(
            (event) =>
              event.resource === resource.name && this.isEventOnDay(event, dayDate)
          );
        });
      });

      return filteredEvents;
    },
    statisticsByColor() {
      return this.calculateStatisticsByColor();
    },
  },
  watch: {
  searchStartDate() {
    this.updateView();
    this.updateStatistics();
  },
  searchEndDate() {
    this.updateView();
    this.updateStatistics();
  },
  clientSearch() {
    this.updateView();
    this.updateStatistics();
  },
  selectedPays() {
    this.fetchJoursFeries();
    this.fetchResourcesAndEvents(this.userId);
    this.updateStatistics();
  }
},
  mounted() {
    this.fetchResourcesAndEvents(this.userId);
    this.fetchClients();
  this.fetchJoursFeries();
  this.fetchAgences();
  this.updateStatistics();
  },
  methods: {

    calculateInterventionDays(startDate, endDate, viewMode) {
    let totalDays = 0;
    var start = new Date(startDate);
    var end = new Date(endDate);

    // Réinitialiser l'heure à minuit pour les deux dates
    start.setHours(0, 0, 0, 0);
    end.setHours(0, 0, 0, 0);

    if (viewMode === 'month') {
      const monthStart = new Date(this.currentYear, this.currentMonth, 1);
      const monthEnd = new Date(this.currentYear, this.currentMonth + 1, 0);

      if (start < monthStart) start = monthStart;
      if (end > monthEnd) end = monthEnd;
    } else if (viewMode === 'year') {
      const yearStart = new Date(this.currentYear, 0, 1);
      const yearEnd = new Date(this.currentYear, 11, 31);

      if (start < yearStart) start = yearStart;
      if (end > yearEnd) end = yearEnd;
    }

    // Calculer les jours entiers entre les dates de début et de fin, en excluant les week-ends et les jours fériés
    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
      const dayOfWeek = d.getDay();
      if (dayOfWeek !== 0 && dayOfWeek !== 6 && !this.isJourFerie(d.getDate(), d.getMonth())) {
        totalDays++;
      }
    }

    return totalDays;
  },
 calculateTotalInterventionDays() {
    let total = 0;
    this.filteredEvents.forEach(event => {
      const eventStart = new Date(event.startDate);
      const eventEnd = new Date(event.endDate);
      total += this.calculateInterventionDays(eventStart, eventEnd, this.viewMode);
    });
    this.totalInterventionDays = total;
  },
  getTNFDaysInPeriod(resource, startDate, endDate) {
    const tnfDays = [];
    const resourceEvents = this.filteredEvents.filter(event => event.resource === resource.name);

    for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
      // Réinitialiser l'heure à minuit pour les deux dates
      const day = new Date(d.getFullYear(), d.getMonth(), d.getDate());
      day.setHours(0, 0, 0, 0);

      // Ignorer les week-ends et les jours fériés
      if (this.isWeekend(day.getDate(), day.getMonth()).isWeekend || this.isJourFerie(day.getDate(), day.getMonth())) {
        continue;
      }

      const eventsOnDay = resourceEvents.filter(event => this.isEventOnDay(event, day));
      if (eventsOnDay.length === 0) {
        tnfDays.push(new Date(day.getFullYear(), day.getMonth(), day.getDate()));
      }
    }

    return tnfDays;
  },
    calculateWeeklyBench(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    let totalBench = 0;
    let currentWeekStart = new Date(start);

    while (currentWeekStart <= end) {
      const weekEnd = new Date(currentWeekStart);
      weekEnd.setDate(currentWeekStart.getDate() + 6);

      let totalTNF = 0;
      let workingDays = 0;

      for (
        let d = new Date(currentWeekStart);
        d <= weekEnd;
        d.setDate(d.getDate() + 1)
      ) {
        const { isWeekend } = this.isWeekend(d.getDate(), d.getMonth());
        const isHoliday = this.isJourFerie(d.getDate(), d.getMonth());

        if (isHoliday || isWeekend) {
          continue;
        } else {
          workingDays++;

          this.resources.forEach((resource) => {
            const events = this.getEventsForMonth(
              resource.name,
              d.getMonth(),
              d.getDate()
            );
            if (!events || events.length === 0) {
              totalTNF++;
            }
          });
        }
      }

      const etp = workingDays > 0 ? Math.min(workingDays, 5) : 0; // Limite à 5 jours max
      const weeklyBench = etp > 0 ? (totalTNF / etp).toFixed(1) : 0;
      totalBench += parseFloat(weeklyBench);

      currentWeekStart.setDate(currentWeekStart.getDate() + 7); // Passe à la semaine suivante
    }

    return totalBench.toFixed(1); // Retourne le total formaté
  },

  getWeeklyBenchForDay(day) {
    const startOfWeek = new Date(this.currentYear, this.currentMonth, day);
    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay() + 1); // Début de la semaine (lundi)
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Fin de la semaine (dimanche)

    // Vérifiez si le jour donné est le premier jour de la semaine
    if (startOfWeek.getDate() === day) {
      const totalBench = this.calculateWeeklyBench(startOfWeek, endOfWeek);
      return (totalBench); // Divisez par le nombre de ressources
    } else {
      return ''; // Retourne une chaîne vide pour les jours qui ne sont pas le premier jour de la semaine
    }
  },
    updateStatistics() {
    this.calculateTotalTNF();
    this.calculateTotalConsultants();
    this.calculateTotalInterventionDays();
  },

  calculateTotalTNF() {
    let total = 0;
    const startDate = this.viewMode === 'month' ? new Date(this.currentYear, this.currentMonth, 1) : new Date(this.currentYear, 0, 1);
    const endDate = this.viewMode === 'month' ? new Date(this.currentYear, this.currentMonth + 1, 0) : new Date(this.currentYear, 11, 31);

    this.resources.forEach(resource => {
      total += this.getTNFDaysInPeriod(resource, startDate, endDate).length;
    });
    this.totalTNF = total;
  },

  calculateTotalConsultants() {
    this.totalConsultants = this.resources.length;
  },

 
    openExportModal() {
      this.isExportModalOpen = true;
    },
    closeExportModal() {
      this.isExportModalOpen = false;
      this.endDate = "";
      this.startDate = "";
    },
    exportToXLSX() {
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet('Interventions');

      // Définir les colonnes
      worksheet.columns = [
        { header: 'Numero', key: 'Numero', width: 10 },
        { header: 'Ressource', key: 'Ressource', width: 20 },
        { header: 'Date de debut', key: 'Date_de_debut', width: 20 },
        { header: 'Date de fin', key: 'Date_de_fin', width: 20 },
        { header: 'Client', key: 'Client', width: 20 },
        { header: 'Commentaire', key: 'Commentaire', width: 30 },
        { header: 'Abbreviation', key: 'Abbreviation', width: 15 }
      ];

      // Vérifier si les dates sont sélectionnées
      let filteredEvents = this.events;
      if (this.startDate && this.endDate) {
        filteredEvents = this.events.filter(event => {
          const eventStartDate = new Date(event.startDate);
          const eventEndDate = new Date(event.endDate);
          const start = new Date(this.startDate);
          const end = new Date(this.endDate);
          return eventStartDate >= start && eventEndDate <= end;
        });
      }

      // Ajouter les données
      filteredEvents.forEach((event, index) => {
        worksheet.addRow({
          Numero: index + 1,
          Ressource: event.resource,
          Date_de_debut: event.startDate,
          Date_de_fin: event.endDate,
          Client: event.clientName,
          Commentaire: event.name,
          Abbreviation: event.clientAbbreviation
        });
      });

      // Styliser l'entête avec un gradient violet et rose
      const headerRow = worksheet.getRow(1);
      headerRow.eachCell((cell) => {
        cell.fill = {
          type: 'gradient',
          gradient: 'angle',
          degree: 0,
          stops: [
            { position: 0, color: { argb: 'FF69B4' } }, // Rose
            { position: 1, color: { argb: '800080' } } // Violet
          ]
        };
        cell.font = { color: { argb: 'FFFFFF' }, bold: true };
        cell.alignment = { horizontal: 'center', vertical: 'middle' };
      });

      // Obtenir la date actuelle
      const currentDate = new Date().toLocaleDateString().replace(/\//g, '-');

      // Enregistrer le fichier avec la date d'exportation dans le nom
      workbook.xlsx.writeBuffer().then((data) => {
        const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `Staffings_${currentDate}.xlsx`;
        link.click();
        URL.revokeObjectURL(link.href);
      });

      // Fermer le modal après l'exportation
      this.closeExportModal();
    },
    exportToPDF() {
      // Définir le format A4
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: [210, 297]
      });

      // Définir les colonnes et les données
      const columns = [
        { header: 'Numero', dataKey: 'Numero' },
        { header: 'Ressource', dataKey: 'Ressource' },
        { header: 'Date de debut', dataKey: 'Date_de_debut' },
        { header: 'Date de fin', dataKey: 'Date_de_fin' },
        { header: 'Client', dataKey: 'Client' },
        { header: 'Commentaire', dataKey: 'Commentaire' },
        { header: 'Abbreviation', dataKey: 'Abbreviation' }
      ];

      // Vérifier si les dates sont sélectionnées
      let filteredEvents = this.events;
      if (this.startDate && this.endDate) {
        filteredEvents = this.events.filter(event => {
          const eventStartDate = new Date(event.startDate);
          const eventEndDate = new Date(event.endDate);
          const start = new Date(this.startDate);
          const end = new Date(this.endDate);
          return eventStartDate >= start && eventEndDate <= end;
        });
      }

      const data = filteredEvents.map((event, index) => ({
        Numero: index + 1,
        Ressource: event.resource,
        Date_de_debut: event.startDate,
        Date_de_fin: event.endDate,
        Client: event.clientName,
        Commentaire: event.name,
        Abbreviation: event.clientAbbreviation
      }));

      // Ajouter le tableau au PDF
      doc.autoTable({
        head: [columns.map(col => col.header)],
        body: data.map(row => columns.map(col => row[col.dataKey])),
        startY: 20,
        styles: {
          fillColor: [240, 240, 240], // Blanc légèrement gris
          textColor: [0, 0, 0], // Noir
          fontStyle: 'normal',
          halign: 'center',
          valign: 'middle',
          cellPadding: 5
        },
        headStyles: {
          fillColor: [128, 0, 128], // Violet
          textColor: [255, 255, 255],
          fontStyle: 'bold',
          halign: 'center',
          valign: 'middle',
          cellPadding: 5
        }
      });

      // Obtenir la date actuelle
      const currentDate = new Date().toLocaleDateString().replace(/\//g, '-');

      // Enregistrer le PDF avec la date d'exportation dans le nom
      doc.save(`Staffings_${currentDate}.pdf`);

      // Fermer le modal après l'exportation
      this.closeExportModal();
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
    filterInterventionsByClientName(clientName) {
      const lowerCaseClientName = clientName.toLowerCase();
      return this.events.filter((event) =>
        event.clientName.toLowerCase().includes(lowerCaseClientName)
      );
    },
    calculateBANGE(startDate, endDate) {
      const start = new Date(startDate);
      const end = new Date(endDate);
      let totalBANGE = 0;
      let currentWeekStart = new Date(start);

      while (currentWeekStart <= end) {
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(currentWeekStart.getDate() + 6);

        let totalTNF = 0;
        let workingDays = 0;

        for (
          let d = new Date(currentWeekStart);
          d <= weekEnd;
          d.setDate(d.getDate() + 1)
        ) {
          const { isWeekend } = this.isWeekend(d.getDate(), d.getMonth());
          const isHoliday = this.isJourFerie(d.getDate(), d.getMonth());

          if (isHoliday) {
            continue;
          }

          if (isWeekend) {
            console.log("holiday");
          } else {
            workingDays++;

            this.resources.forEach((resource) => {
              const events = this.getEventsForMonth(
                resource.name,
                d.getMonth(),
                d.getDate()
              );
              if (!events || events.length === 0) {
                totalTNF++;
              }
            });
          }
        }

        const etp = workingDays > 0 ? Math.min(workingDays, 5) : 0; // Limite à 5 jours max
        const weeklyBANGE = etp > 0 ? (totalTNF / etp).toFixed(1) : 0;
        totalBANGE += parseFloat(weeklyBANGE);

        currentWeekStart.setDate(currentWeekStart.getDate() + 7); // Passe à la semaine suivante
      }

      return totalBANGE.toFixed(1); // Retourne le total formaté
    },
    calculateAndDisplayBANGE() {
      if (this.bangeStartDate && this.bangeEndDate) {
        const bangeResult = this.calculateBANGE(this.bangeStartDate, this.bangeEndDate);
        this.bangeResults.push({
          date: `${this.bangeStartDate} - ${this.bangeEndDate}`,
          bange: bangeResult,
        });
        this.isBANGEModalOpen = true;
      } else {
        alert("Veuillez sélectionner une date de début et une date de fin.");
      }
    },
    closeBANGEModal() {
      this.isBANGEModalOpen = false;
      this.bangeStartDate = "";
      this.bangeEndDate = "";
      this.bangeResults = [];
    },
    getDayAbbreviation(date) {
      const days = ["dim", "lun", "mar", "mer", "jeu", "ven", "sam"];
      return days[date.getDay()];
    },
    isWednesday(day, monthIndex) {
      const date = new Date(this.currentYear, monthIndex, day);
      return date.getDay() === 3; // 3 représente mercredi (0 = dimanche, 1 = lundi, etc.)
    },
    // Modifiez la méthode getWeeklyBANGE pour prendre en compte le monthIndex
    getWeeklyBANGE(day, monthIndex = null) {
      // Détermine la date de la semaine à partir du jour donné
      const initialDate = new Date(
        this.currentYear,
        monthIndex !== null ? monthIndex : this.currentMonth,
        day
      );

      // Ajuste le début de la semaine au lundi
      const weekStart = new Date(initialDate);
      const dayOfWeek = weekStart.getDay(); // 0 = Dimanche, 1 = Lundi, ..., 6 = Samedi
      const offsetToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek; // Ajustement vers le lundi
      weekStart.setDate(weekStart.getDate() + offsetToMonday);

      // Fixe la fin de la semaine au vendredi (4 jours après lundi)
      const weekEnd = new Date(weekStart);
      weekEnd.setDate(weekStart.getDate() + 4);

      let totalTNF = 0; // Compteur des jours sans événements
      let workingDays = 0; // Compteur des jours ouvrables

      // Parcourt chaque jour de la semaine (lundi à vendredi)
      for (let d = new Date(weekStart); d <= weekEnd; d.setDate(d.getDate() + 1)) {
        const isHoliday = this.isJourFerie(d.getDate(), d.getMonth()); // Vérifie si le jour est férié

        if (isHoliday) {
          // Ignore les jours fériés
          continue;
        }

        // Tous les jours de lundi à vendredi sont considérés ouvrables ici
        workingDays++; // Incrémente les jours ouvrables

        // Vérifie les événements pour chaque ressource
        this.resources.forEach((resource) => {
          const events = this.getEventsForMonth(
            resource.name,
            d.getMonth(),
            d.getDate()
          );

          if (!events || events.length === 0) {
            // Si aucun événement trouvé, incrémente TNF
            totalTNF++;
          }
        });
      }

      // Calcule l'ETP (équivalent temps plein), limité à un maximum de 5 jours par semaine
      const etp = workingDays > 0 ? Math.min(workingDays, 5) : 0;

      // Calcule le ratio BANGE pour la semaine
      const bange = etp > 0 ? (totalTNF / etp).toFixed(1) : 0;

      // Retourne le BANGE pour la semaine
      return { bange };
    },
    // Nouvelle méthode pour calculer le BANGE mensuel
    getMonthlyBANGE() {
      const start = new Date(this.currentYear, this.currentMonth, 1);
      const end = new Date(this.currentYear, this.currentMonth + 1, 0);
      let totalBANGE = 0;
      let currentWeekStart = new Date(start);

      while (currentWeekStart <= end) {
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(currentWeekStart.getDate() + 6);

        let totalTNF = 0;
        let workingDays = 0;

        for (
          let d = new Date(currentWeekStart);
          d <= weekEnd;
          d.setDate(d.getDate() + 1)
        ) {
          const dayOfWeek = d.getDay();
          if (
            dayOfWeek !== 0 &&
            dayOfWeek !== 6 &&
            !this.isJourFerie(d.getDate(), d.getMonth())
          ) {
            workingDays++;
            this.resources.forEach((resource) => {
              const events = this.getEventsForMonth(
                resource.name,
                d.getMonth(),
                d.getDate()
              );
              if (!events || events.length === 0) {
                totalTNF++;
              }
            });
          }
        }

        const etp = workingDays > 0 ? Math.min(workingDays, 5) : 0;
        const weeklyBANGE = etp > 0 ? (totalTNF / etp).toFixed(1) : 0;
        totalBANGE += parseFloat(weeklyBANGE);

        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
      }

      return totalBANGE.toFixed(1);
    },
    getJourFerieName(day, monthIndex = null) {
      const date = new Date(
        this.currentYear,
        monthIndex !== null ? monthIndex : this.currentMonth,
        typeof day === "number" ? day : new Date(day).getDate()
      );
      const formattedDate = format(date, "yyyy-MM-dd");
      const jourFerie = this.formattedJoursFeries.find(
        (jour) => jour.date === formattedDate
      );
      return jourFerie ? jourFerie.name : "Férié";
    },
    isJourFerie(day, monthIndex = null) {
      const date = new Date(
        this.currentYear,
        monthIndex !== null ? monthIndex : this.currentMonth,
        typeof day === "number" ? day : new Date(day).getDate()
      );
      const formattedDate = format(date, "yyyy-MM-dd");

      const isHoliday = this.formattedJoursFeries.some(
        (jour) => jour.date === formattedDate
      );

      return isHoliday;
    },
    calculateBusinessDays(startDate, endDate) {
      let count = 0;
      const currentDate = new Date(startDate);

      while (currentDate <= endDate) {
        const dayOfWeek = currentDate.getDay();
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
          count++;
        }
        currentDate.setDate(currentDate.getDate() + 1);
      }

      return count;
    },
    showComment(event, day) {
      this.hoveredEvent = event;
      this.hoveredDay = day;
    },
    hideComment() {
      this.hoveredEvent = null;
      this.hoveredDay = null;
    },
    // Remplacer la méthode searchTNF par updateView
    updateView() {
      if (this.searchStartDate && this.searchEndDate) {
        const startDate = new Date(this.searchStartDate);
        this.currentYear = startDate.getFullYear();
        this.currentMonth = startDate.getMonth();
      }
    },
    clearDateFilters() {
      this.searchStartDate = "";
      this.searchEndDate = "";
      this.updateView();
    },
   
    isHighlightedTNF(resourceName, day, monthIndex) {
      if (!this.searchStartDate || !this.searchEndDate) return false;

      const resource = this.resources.find((r) => r.name === resourceName);
      if (!resource) return false;

      const date = new Date(
        this.currentYear,
        monthIndex !== undefined ? monthIndex : this.currentMonth,
        day
      );
      const startDate = new Date(this.searchStartDate);
      const endDate = new Date(this.searchEndDate);

      // Exclure le jour s'il est en dehors de la plage ou s'il est un jour férié
      if (date < startDate || date > endDate || this.isJourFerie(day, monthIndex)) return false;

      const tnfDays = this.getTNFDaysInPeriod(resource, startDate, endDate);

      return tnfDays.some(
        (tnfDay) =>
          tnfDay.getDate() === date.getDate() &&
          tnfDay.getMonth() === date.getMonth() &&
          tnfDay.getFullYear() === date.getFullYear()
      );
    },
    async fetchJoursFeries() {
      try {
        const response = await axios.get(`${API_URL}/jours-feries`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.joursFeries = response.data; // Stocke les jours fériés
      } catch (error) {
        console.error("Erreur lors de la récupération des jours fériés:", error);
      }
    },
    updateSelectedClient() {
      const selectedClient = this.clients.find(
        (client) => client.id === this.selectedClientId
      );
      if (selectedClient) {
        this.selectedClientColor = selectedClient.color;
      }
    },
    async fetchClients() {
      try {
        const response = await axios.get(`${API_URL}/clients`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        this.clients = response.data;
      } catch (error) {
        console.error("Erreur lors de la récupération des clients:", error);
      }
    },
    async saveEvent() {
      try {
        await axios.post(
          `${API_URL}/interventions`,
          {
            user_id: this.user_id,
            client_id: this.selectedClientId, // Assurez-vous d'utiliser l'ID du client ici
            commentaire: this.comment,
            date_debut: this.startDate,
            date_fin: this.endDate,
            color: this.selectedClientColor,
          },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        await this.fetchResourcesAndEvents(this.userId);
        this.closeEventModal();
      } catch (error) {
        console.error("Erreur lors de la sauvegarde de l'événement:", error);
        if (error.response) {
          console.error("Détails de l'erreur:", error.response.data);
          // Affichez un message d'erreur utilisateur ici si nécessaire
        }
      }
    },
    async updateEvent() {
      try {
        await axios.post(
          // Utilisez PUT pour les mises à jour
          `${API_URL}/interventions/${this.selectedEvent.id}`,
          {
            user_id: this.user_id,
            client_id: this.selectedClientId, // Assurez-vous d'utiliser l'ID du client ici
            commentaire: this.comment,
            date_debut: this.startDate,
            date_fin: this.endDate,
            color: this.selectedClientColor,
          },
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        await this.fetchResourcesAndEvents(this.userId);
        this.closeEventModal();
      } catch (error) {
        console.error("Erreur lors de la mise à jour de l'événement:", error);
        if (error.response) {
          console.error("Détails de l'erreur:", error.response.data);
          // Affichez un message d'erreur utilisateur ici si nécessaire
        }
      }
    },
    selectClient(clientId) {
      const client = this.clients.find((c) => c.id === clientId);
      if (client) {
        this.selectedClient = client.nom;
        this.selectedClientId = client.id;
        this.selectedClientColor = client.color; // Assurez-vous que cette ligne est présente
      } else {
        this.selectedClient = null;
        this.selectedClientId = null;
        this.selectedClientColor = "";
      }
    },
    openEventModal(day, resourceName, monthIndex = null) {
      let selectedDate;

      if (monthIndex !== null) {
        selectedDate = new Date(this.currentYear, monthIndex, day);
      } else {
        selectedDate = new Date(this.currentYear, this.currentMonth, day);
      }

      if (!isNaN(selectedDate.getTime())) {
        const headerDate = format(selectedDate, "yyyy-MM-dd");
        this.startDate = headerDate;

        const resource = this.resources.find((r) => r.name === resourceName);

        if (resource) {
          this.selectedEvent = {
            resourceName,
            resourceId: resource.id,
            day: this.startDate,
          };
          this.user_id = resource.id;

          const events =
            monthIndex !== null
              ? this.getEventsForMonth(resourceName, monthIndex, day)
              : this.filteredEventsByDay[resourceName][day];

          const event = events && events[0];

          if (event) {
            const intervention = resource.interventions.find((i) => i.id === event.id);
            console.log(intervention); // Ajoutez ceci pour vérifier ce que contient intervention
            if (intervention) {
              this.selectedClientId = intervention.client_id; // L'ID du client
              this.selectedClient = intervention.client_name; // Le nom du client
              this.comment = intervention.commentaire;
              this.startDate = format(new Date(intervention.date_debut), "yyyy-MM-dd");
              this.endDate = format(new Date(intervention.date_fin), "yyyy-MM-dd");
              (this.selectedClientColor = intervention.client_color),
                (this.selectedEvent.id = intervention.id); // Marquer comme événement existant
            }
          } else {
            // Réinitialiser pour un nouvel événement
            this.selectedClient = null;
            this.selectedClientId = null;
            this.comment = "";
            this.startDate = headerDate;
            this.endDate = "";
            this.selectedClientColor = ""; // Valeur par défaut
            this.selectedEvent.id = null; // Marquer comme nouvel événement
          }

          this.isEventModalOpen = true; // Ouvrir le modal
        }
      }
    },
    async deleteEvent() {
      try {
        await axios.delete(`${API_URL}/interventions/${this.selectedEvent.id}`, {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });

        await this.fetchResourcesAndEvents(this.userId);
        this.closeEventModal();
      } catch (error) {
        console.error("Erreur lors de la suppression de l'événement:", error);
        if (error.response) {
          console.error("Détails de l'erreur:", error.response.data);
        }
      }
    },
    closeEventModal() {
      this.isEventModalOpen = false;
      this.selectedEvent = {};
      this.selectedClient = null;
      this.comment = "";
      this.startDate = "";
      this.endDate = "";
      this.selectedClientColor = "";
    },
    resetToToday() {
      const today = new Date();
      this.currentYear = today.getFullYear();
      this.currentMonth = today.getMonth();
      this.currentDay = today.getDate();
    },
    prevPeriod() {
      if (this.viewMode === "month") {
        this.currentMonth--;
        if (this.currentMonth < 0) {
          this.currentMonth = 11;
          this.currentYear--;
        }
      } else {
        this.currentYear--;
      }
      this.fetchJoursFeries(); // Ajoutez cette ligne
    },
    nextPeriod() {
      if (this.viewMode === "month") {
        this.currentMonth++;
        if (this.currentMonth > 11) {
          this.currentMonth = 0;
          this.currentYear++;
        }
      } else {
        this.currentYear++;
      }
      this.fetchJoursFeries(); // Ajoutez cette ligne
    },
    getDayLabel(index, day) {
      const dayDate = new Date(this.currentYear, this.currentMonth, day);
      return format(dayDate, "EEE dd");
    },
    getDaysInMonth(monthIndex = null) {
      const month = monthIndex !== null ? monthIndex : this.currentMonth;
      const daysInMonth = new Date(this.currentYear, month + 1, 0).getDate();
      return Array.from({ length: daysInMonth }, (_, i) => i + 1);
    },
    getDaysInYear() {
      const months = [];
      for (let month = 0; month < 12; month++) {
        const daysInMonth = new Date(this.currentYear, month + 1, 0).getDate();
        const days = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        months.push({
          month: format(new Date(this.currentYear, month), "MMMM"),
          days,
        });
      }
      return months;
    },
  
    isWeekend(day, month = this.currentMonth) {
      const date = new Date(this.currentYear, month, day);
      const dayOfWeek = date.getDay();
      return { isWeekend: dayOfWeek === 0 || dayOfWeek === 6, dayOfWeek };
    },


isEventOnDay(event, day) {
    const eventStart = new Date(event.startDate);
    const eventEnd = new Date(event.endDate);

    // Réinitialiser l'heure à minuit pour les deux dates
    eventStart.setHours(0, 0, 0, 0);
    eventEnd.setHours(0, 0, 0, 0);

    const dayOfWeek = day.getDay();
    const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

    const dayDate = new Date(day.getFullYear(), day.getMonth(), day.getDate());
    dayDate.setHours(0, 0, 0, 0);

    if (isWeekend) {
      // Pour les week-ends, afficher uniquement les événements qui commencent un week-end
      return (eventStart.getDay() === 0 || eventStart.getDay() === 6) && dayDate >= eventStart && dayDate <= eventEnd;
    } else {
      // Pour les jours de semaine, afficher uniquement les événements qui commencent un jour de semaine
      return eventStart.getDay() !== 0 && eventStart.getDay() !== 6 && dayDate >= eventStart && dayDate <= eventEnd;
    }
  },
    getEventsForMonth(resourceName, monthIndex, day) {
      const date = new Date(this.currentYear, monthIndex, day);
      return this.events.filter(
        (event) => event.resource === resourceName && this.isEventOnDay(event, date)
      );
    },
    async fetchResourcesAndEvents(userId) {
  try {
    const response = await axios.get(`${API_URL}/user/${userId}/interventions`, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });
    const data = response.data;
    console.log(data); // Ajoutez cette ligne pour vérifier la structure de la réponse

    // Assurez-vous que `data` est un objet
    if (data && data.id) {
      this.resources = [
        {
          id: data.id,
          name: data.name,
          agence: data.agence,
          type: data.type, // Assurez-vous que le type est inclus
          interventions: data.interventions,
        },
      ];

      // Extraire les événements à partir de `data.interventions`
      this.events = data.interventions.map((intervention) => ({
        id: intervention.id,
        name: intervention.commentaire,
        startDate: intervention.date_debut,
        endDate: intervention.date_fin,
        resource: data.name,
        color: intervention.client_color,
        resourceId: data.id,
        clientAbbreviation: intervention.client_abbreviation,
        clientName: intervention.client_name,
      }));

      // Filtrer les utilisateurs et les jours fériés en fonction de l'agence sélectionnée
      if (this.selectedPays) {
        this.resources = this.resources.filter(
          (resource) => resource.agence && resource.agence.pays === this.selectedPays
        );
        this.joursFeries = this.joursFeries.filter(
          (jour) => jour.agence && jour.agence.pays === this.selectedPays
        );
      }
    } else {
      console.error("La réponse de l'API n'est pas un objet valide:", data);
    }
  } catch (error) {
    console.error("Erreur lors de la récupération des données:", error);
  }
}

,
  getClientColor(color) {
      if (!color || typeof color !== "number") {
        return this.colorMapping.default.class; // Couleur par défaut si la couleur n'est pas définie ou invalide
      }
      return this.colorMapping[color] ? this.colorMapping[color].class : this.colorMapping.default.class;
    },
    getColorName(color) {
      if (!color || typeof color !== "number") {
        return this.colorMapping.default.name; // Nom par défaut si la couleur n'est pas définie ou invalide
      }
      return this.colorMapping[color] ? this.colorMapping[color].name : this.colorMapping.default.name;
    },
calculateStatisticsByColor() {
  const statistics = {};
  let startDate, endDate;

  if (this.viewMode === 'month') {
    startDate = new Date(this.currentYear, this.currentMonth, 1);
    endDate = new Date(this.currentYear, this.currentMonth + 1, 0);
  } else if (this.viewMode === 'year') {
    startDate = new Date(this.currentYear, 0, 1);
    endDate = new Date(this.currentYear, 11, 31);
  }

  // Parcourez chaque jour de la période sélectionnée
  for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
    const dayOfWeek = d.getDay();
    if (dayOfWeek !== 0 && dayOfWeek !== 6 && !this.isJourFerie(d.getDate(), d.getMonth())) {
      this.events.forEach(event => {
        const eventStartDate = new Date(event.startDate);
        const eventEndDate = new Date(event.endDate);

        // Vérifiez si l'événement est actif ce jour-là
        if ((d >= eventStartDate && d <= eventEndDate) || (d.getDate() === eventStartDate.getDate() && d.getMonth() === eventStartDate.getMonth() && d.getFullYear() === eventStartDate.getFullYear())) {
          const clientColor = event.color;
          const clientName = this.getColorName(clientColor);
          const clientClass = this.getClientColor(clientColor); // Obtenez la classe CSS de la couleur

          if (!statistics[clientColor]) {
            statistics[clientColor] = { name: clientName, count: 0, color: clientColor, class: clientClass };
          }
          statistics[clientColor].count++;
        }
      });
    }
  }

  return statistics;
},
  },
};
</script>

<style scoped>
.relative {
  position: relative;
}

.absolute {
  position: absolute;
}

/* Ajoutez ces styles pour une meilleure apparence du commentaire */
.absolute {
  max-width: 200px;
  word-wrap: break-word;
  z-index: 100;
}
.tnf-event {
  width: 20px; /* ajuster la largeur pour qu'elle corresponde à la journée */
  max-width: 20px;
}
</style>