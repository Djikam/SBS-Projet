// src/store/index.js
import { createStore } from 'vuex';
import listPSCP from './modules/listPSCP';
import listPs from './modules/listPs';
import loginAudit from './modules/loginAudit';

const store = createStore({
  modules: {
    listPSCP,
    listPs,
    loginAudit,
  },
});

export default store;
