import { getWithCache } from '../../api';
import axios from 'axios';
import { API_URL } from '@/config'; // Importez l'URL de l'API depuis le fichier de configuration

const state = () => ({
    apiUrl: API_URL, // Utilisez l'URL de l'API depuis le fichier de configuration
    users: [],
    agencies: [],
    showCompetenceModal: false,
    selectedUser: {},
    selectedAgency: null,
    currentPage: 1,
    itemsPerPage: 10,
});

const mutations = {
    setUsers(state, users) {
        state.users = users;
    },
    setAgencies(state, agencies) {
        state.agencies = agencies;
    },
    setShowCompetenceModal(state, show) {
        state.showCompetenceModal = show;
    },
    setSelectedUser(state, user) {
        state.selectedUser = user;
    },
    setSelectedAgency(state, agency) {
        state.selectedAgency = agency;
    },
    setCurrentPage(state, page) {
        state.currentPage = page;
    },
};

const actions = {
    async fetchUsers({ commit, state }) {
        try {
            let url = `${state.apiUrl}/users/type-zero`;
            if (state.selectedAgency) {
                url += `?agence_id=${state.selectedAgency}`;
            }
            const response = await getWithCache(url, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
            commit('setUsers', response.data);
        } catch (error) {
            console.error("Error fetching users:", error);
        }
    },
    async fetchAgencies({ commit, state }) {
        try {
            const response = await getWithCache(`${state.apiUrl}/agences`, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
            commit('setAgencies', response.data.agences);
        } catch (error) {
            console.error("Error fetching agencies:", error);
        }
    },
    async toggleUserStatus({ commit, state }, user) {
        try {
            const endpoint = user.active
                ? `${state.apiUrl}/users/${user.id}/deactivate`
                : `${state.apiUrl}/users/${user.id}/activate`;

            await axios.post(endpoint, null, {
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });

            commit('setUsers', state.users.map(u => u.id === user.id ? { ...u, active: !u.active } : u));
        } catch (error) {
            console.error("Error toggling user status:", error);
        }
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
};
