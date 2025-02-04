// src/store/modules/loginAudit.js

import { getWithCache } from '@/api';

const state = {
    audits: []
};

const mutations = {
    SET_AUDITS(state, audits) {
        state.audits = audits;
    }
};

const actions = {
    async fetchAudits({ commit }) {
        try {
            const response = await getWithCache('/login-audits', {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            });
            commit('SET_AUDITS', response.data);
        } catch (error) {
            console.error('Erreur lors de la récupération des audits :', error);
        }
    }
};

const getters = {
    audits: state => state.audits
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
};
