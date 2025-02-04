<template>
    <div>
        <!-- component -->
<div class="flex items-center justify-center p-4 ">
        
    <form id="form" class="px-8 pt-6 pb-8 mb-4 bg-white rounded shadow-md ">
        <h1 class="block mb-2 text-xl font-bold text-center text-gray-700">AJOUTER UN PROJET</h1>
        <br>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="projectName">
                Nom du Projet
            </label>
            <input
                class="w-full px-4 py-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                name="projectName" id="projectName" type="text" placeholder="Nom du Projet" required>
        </div>
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="projectDescription">
                Description du Projet
            </label>
            <textarea
                class="w-[700px] px-4 py-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                name="projectDescription" id="projectDescription" placeholder="Description du Projet" rows="6" required></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="startDate">
                Date de Début
            </label>
            <input
                class="w-full px-4 py-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                name="startDate" id="startDate" type="date" required>
        </div>
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="endDate">
                Date de Fin
            </label>
            <input
                class="w-full px-4 py-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                name="endDate" id="endDate" type="date" required>
        </div>
        
        <div class="mb-4">
            <label class="block mb-2 text-sm font-bold text-gray-700" for="importance">
                Cote d'Importance (0 à 9)
            </label>
            <input
                class="w-full px-4 py-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                name="importance" id="importance" type="number" min="0" max="9" required>
        </div>
        
        <div class="flex items-center justify-between">
            <button id="submit"
                class="px-4 py-2 font-bold text-white bg-blue-900 rounded hover:bg-blue-950 focus:outline-none focus:shadow-outline"
                type="submit">
                 Enregistrer
            </button>
            <button id="submit"
            class="px-4 py-2 font-bold text-white bg-blue-700 rounded hover:bg-blue-600 focus:outline-none focus:shadow-outline"
            type="submit">
             Retour
        </button>
        </div>


    </form>
    
</div>
    </div>
</template>
<script>
export default {
    
}
</script>