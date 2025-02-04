<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="p-4 bg-white rounded-xl shadow-md w-full max-w-md md:w-[30%]">
        <h3 class="mb-4 text-lg font-semibold">Modifier Agence</h3>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block mb-2">Nom</label>
            <input v-model="currentAgence.name" type="text" class="w-full p-2 border rounded border-slate-300" />
          </div>
          <div class="mb-4">
            <label class="block mb-2">Pays</label>
            <input v-model="currentAgence.pays" type="text" class="w-full p-2 border rounded border-slate-300" />
          </div>
          <div class="flex justify-center gap-4">
            <button type="button" @click="closeModal" class="px-4 py-2 text-gray-700 transition-colors duration-300 bg-gray-200 rounded hover:bg-gray-700">Annuler</button>
            <button type="submit" class="px-4 py-2 text-white transition-colors duration-300 bg-blue-500 rounded hover:bg-blue-700">Modifier</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      show: Boolean,
      agence: Object,
    },
    data() {
      return {
        currentAgence: { ...this.agence },
      };
    },
    watch: {
      agence(newAgence) {
        this.currentAgence = { ...newAgence };
      },
    },
    methods: {
      closeModal() {
        this.$emit('close');
      },
      submitForm() {
        this.$emit('update', this.currentAgence);
        this.closeModal();
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add your custom styles here */
  </style>
  