import { createRouter, createWebHashHistory } from 'vue-router';
import UserCalendar from '../components/intervenants/UserCalendar.vue'; // Assurez-vous que le chemin est correct


const BASE_URL = process.env.BASE_URL;

const routes = [
  {
    path: '/forgotComponent',
    name: 'ForgotComponent',
    component: () => import('@/components/Auth/ForgotComponent.vue')
  },
  {
    path: '/reset-password',
    name: 'ResetPassword',
    component: () => import('@/components/Auth/ResetPassword.vue')
  },
  {
    path: '/change-password',
    name: 'ChangePassword',
    component: () => import('@/components/Auth/ChangePassword.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/components/Auth/Login.vue')
  },
  {
    path: '/project',
    name: 'Project',
    component: () => import('@/components/project/AddProject.vue')
  },
  {
    path: '/addps',
    name: 'AddPs',
    component: () => import('@/components/intervenants/AddPs.vue')
  },
  {
    path: '/updateps/:id',
    name: 'UpdatePs',
    component: () => import('@/components/intervenants/UpdatePs.vue'),
    props: true
  },
  {
    path: '/addclient',
    name: 'AddClient',
    component: () => import('@/components/clients/AddClientComponent.vue')
  },
  {
    path: '/domain',
    name: 'DomainCompetence',
    component: () => import('@/components/intervenants/Domain_competence.vue')
  },
  {
    path: '/niveau',
    name: 'NiveauCompetence',
    component: () => import('@/components/intervenants/Niveau_competence.vue')
  },
  {
    path: '/updateclient/:id',
    name: 'UpdateClient',
    component: () => import('@/components/clients/UpdateClientComponent.vue'),
    props: true
  },
  {
    path: '/usercalendar/:userId',
    name: 'UserCalendar',
    component: () => import('@/components/intervenants/UserCalendar.vue'),
    props: true
  },
  {
    path: '/userCalendar/:userId',
    name: 'userCalendar',
    component: UserCalendar,
    props: true
  },
  {
    path: '/updateproject',
    name: 'UpdateProject',
    component: () => import('@/components/project/UpdateProject.vue')
  },
  {
    path: '/myprofile',
    name: 'MyProfile',
    component: () => import('@/components/intervenants/MyProfile.vue')
  },
  {
    path: '/',
    component: () => import('@/views/NavView.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        components: { one: () => import('@/views/HomeView.vue') }
      },
      {
        path: 'projet',
        name: 'Projet',
        components: { one: () => import('@/views/StaffingView.vue') }
      },
      {
        path: 'staff',
        name: 'StaffingUsers',
        components: { one: () => import('@/components/intervenants/StaffingUsers.vue') }
      },
      {
        path: 'login-audit',
        name: 'LoginAudit',
        components: { one: () => import('@/components/Auth/LoginAudit.vue') }
      }, 
      {
        path: 'about',
        name: 'About',
        components: { one: () => import('@/views/AboutView.vue') }
      },
      {
        path: 'listPs',
        name: 'ListPs',
        components: { one: () => import('@/components/intervenants/ListPS.vue') }
      },
      {
        path: 'listPsCP',
        name: 'ListPSCP',
        components: { one: () => import('@/components/intervenants/ListPSCP.vue') }
      },
      {
        path: 'clients',
        name: 'Clients',
        components: { one: () => import('@/components/clients/ClientsComponent.vue') }
      },
      {
        path: 'ferie',
        name: 'JoursFeries',
        components: { one: () => import('@/components/JoursFeries.vue') }
      },
      {
        path: 'mycalendar',
        name: 'MyCalendar',
        components: { one: () => import('@/components/intervenants/MyCalendar.vue') }
      },
      {
        path: 'listAgence',
        name: 'ListAgence',
        components: { one: () => import('@/components/agence/ListAgence.vue') }
      },
      {
        path: 'demande',
        name: 'DemandeUser',
        components: { one: () => import('@/components/demandes/DemandeUser.vue') }
      },
      {
        path: 'demandes',
        name: 'DemandeAdmin',
        components: { one: () => import('@/components/demandes/DemandeAdmin.vue') }
      }
    ]
  }
];

const router = createRouter({
  history: createWebHashHistory(BASE_URL),
  routes
});

export default router;
