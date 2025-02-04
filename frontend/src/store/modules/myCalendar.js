import { getWithCache } from '../../api';
import { format } from 'date-fns';
import { API_URL } from '../../config';

const state = {
    currentYear: new Date().getFullYear(),
    currentMonth: new Date().getMonth(),
    currentDay: new Date().getDate(),
    viewMode: 'month',
    resources: [],
    events: [],
    clients: [],
    joursFeries: [],
    bangeResults: [],
    searchStartDate: '',
    searchEndDate: '',
    tnfSearchResults: [],
    periodDays: [], // Ajouter periodDays à l'état initial
};

const mutations = {
    SET_CURRENT_YEAR(state, year) {
        state.currentYear = year;
    },
    SET_CURRENT_MONTH(state, month) {
        state.currentMonth = month;
    },
    SET_CURRENT_DAY(state, day) {
        state.currentDay = day;
    },
    SET_VIEW_MODE(state, mode) {
        state.viewMode = mode;
    },
    SET_RESOURCES(state, resources) {
        state.resources = resources;
    },
    SET_EVENTS(state, events) {
        state.events = events;
    },
    SET_CLIENTS(state, clients) {
        state.clients = clients;
    },
    SET_JOURS_FERIES(state, joursFeries) {
        state.joursFeries = joursFeries;
    },
    SET_BANGE_RESULTS(state, bangeResults) {
        state.bangeResults = bangeResults;
    },
    SET_SEARCH_START_DATE(state, date) {
        state.searchStartDate = date;
    },
    SET_SEARCH_END_DATE(state, date) {
        state.searchEndDate = date;
    },
    SET_TNF_SEARCH_RESULTS(state, results) {
        state.tnfSearchResults = results;
    },
    SET_PERIOD_DAYS(state, days) {
        state.periodDays = days;
    },
};

const actions = {
    async fetchUserData() {
        try {
            const response = await getWithCache(`${API_URL}/me`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error('Erreur lors de la récupération des données de l\'utilisateur:', error);
            return null;
        }
    },
    async fetchResourcesAndEvents({ commit }, userId) {
        try {
            const response = await getWithCache(`${API_URL}/mycalendar?user_id=${userId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            const data = response.data;
            commit('SET_RESOURCES', [
                {
                    id: data.id,
                    name: data.name,
                    interventions: data.interventions,
                },
            ]);
            commit('SET_EVENTS', data.interventions.map(intervention => ({
                id: intervention.id,
                name: intervention.commentaire,
                startDate: intervention.date_debut,
                endDate: intervention.date_fin,
                resource: data.name,
                color: intervention.client_color,
                resourceId: data.id,
                clientAbbreviation: intervention.client_abbreviation,
            })));
        } catch (error) {
            console.error('Erreur lors de la récupération des données:', error.response ? error.response.data : error.message);
        }
    },
    async fetchClients({ commit }) {
        try {
            const response = await getWithCache(`${API_URL}/clients`, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            commit('SET_CLIENTS', response.data);
        } catch (error) {
            console.error('Erreur lors de la récupération des clients:', error);
        }
    },
    async fetchJoursFeries({ commit }) {
        try {
            const response = await getWithCache(`${API_URL}/jours-feries`, {
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
            });
            commit('SET_JOURS_FERIES', response.data);
        } catch (error) {
            console.error('Erreur lors de la récupération des jours fériés:', error);
        }
    },
};

const getters = {
    filteredClients: (state) => (search) => {
        return state.clients.filter(client =>
            client.nom.toLowerCase().includes(search.toLowerCase())
        );
    },
    displayPeriod: (state) => {
        if (state.viewMode === 'month') {
            const firstDayOfMonth = new Date(state.currentYear, state.currentMonth, 1);
            return format(firstDayOfMonth, 'MMMM yyyy');
        } else if (state.viewMode === 'year') {
            const firstDayOfYear = new Date(state.currentYear, 0, 1);
            return format(firstDayOfYear, 'yyyy');
        } else {
            return `${state.currentYear}`;
        }
    },
    formattedJoursFeries: (state) => {
        return state.joursFeries.map(jourFerie => ({
            date: format(new Date(jourFerie.date_ferie), 'yyyy-MM-dd'),
            name: jourFerie.nom_ferie,
        }));
    },
    periodDays: (state) => {
        return state.viewMode === 'month' ? getDaysInMonth(state.currentYear, state.currentMonth) : getDaysInYear(state.currentYear);
    },
    filteredResources: (state) => {
        if (!state.searchStartDate || !state.searchEndDate) {
            return state.resources;
        }
        const startDate = new Date(state.searchStartDate);
        const endDate = new Date(state.searchEndDate);
        return state.resources.filter(resource => {
            const tnfDays = getTNFDaysInPeriod(resource, startDate, endDate);
            return tnfDays.length > 0;
        });
    },
    filteredEventsByDay: (state) => {
        const filteredEvents = {};
        state.resources.forEach(resource => {
            filteredEvents[resource.name] = {};
            state.periodDays.forEach(day => {
                const dayDate = new Date(state.currentYear, state.currentMonth, day);
                filteredEvents[resource.name][day] = state.events.filter(
                    event => event.resource === resource.name && isEventOnDay(event, dayDate)
                );
            });
        });
        return filteredEvents;
    },
};

function getDaysInMonth(year, month) {
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    return Array.from({ length: daysInMonth }, (_, i) => i + 1);
}

function getDaysInYear(year) {
    const months = [];
    for (let month = 0; month < 12; month++) {
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const days = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        months.push({
            month: format(new Date(year, month), 'MMMM'),
            days,
        });
    }
    return months;
}

function getTNFDaysInPeriod(resource, startDate, endDate) {
    const tnfDays = [];
    const resourceEvents = state.events.filter(
        (event) => event.resource === resource.name
    );

    for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
        if (
            isWeekend(d.getDate(), d.getMonth()).isWeekend ||
            isJourFerie(d.getDate())
        ) {
            continue;
        }

        const eventsOnDay = resourceEvents.filter((event) => isEventOnDay(event, d));
        if (eventsOnDay.length === 0) {
            tnfDays.push(new Date(d));
        }
    }

    return tnfDays;
}

function isWeekend(day, month) {
    const date = new Date(state.currentYear, month, day);
    const dayOfWeek = date.getDay();
    return { isWeekend: dayOfWeek === 0 || dayOfWeek === 6, dayOfWeek };
}

function isJourFerie(day) {
    const date = new Date(state.currentYear, state.currentMonth, day);
    const formattedDate = format(date, 'yyyy-MM-dd');
    return state.formattedJoursFeries.some((jour) => jour.date === formattedDate);
}

function isEventOnDay(event, day) {
    const eventStart = new Date(event.startDate);
    const eventEnd = new Date(event.endDate);
    const dayOfWeek = day.getDay();
    const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

    const dayDate = new Date(day.getFullYear(), day.getMonth(), day.getDate());
    const eventStartDate = new Date(
        eventStart.getFullYear(),
        eventStart.getMonth(),
        eventStart.getDate()
    );
    const eventEndDate = new Date(
        eventEnd.getFullYear(),
        eventEnd.getMonth(),
        eventEnd.getDate()
    );

    if (isWeekend) {
        return (
            (eventStart.getDay() === 0 || eventStart.getDay() === 6) &&
            dayDate >= eventStartDate &&
            dayDate <= eventEndDate
        );
    } else {
        return (
            eventStart.getDay() !== 0 &&
            eventStart.getDay() !== 6 &&
            dayDate >= eventStartDate &&
            dayDate <= eventEndDate
        );
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,
};
