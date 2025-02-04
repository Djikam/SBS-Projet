<template>
    <div class="grid justify-center bg-white center bg- bg hello">
    <h1>{{ msg }}</h1>
    <div class="max-w-sm p-6 mx-auto space-y-4 bg-white bg-center bg-cover shadow-md rounded-xl">
      <img alt="Vue logo" src="../assets/image.jpeg">
      <h1 class="text-2xl font-bold text-center">
        Bienvenue dans Affectation staffing
      </h1>    
      <p class="text-black-500">
        Ici vous aurez tout à disposition pour affecter vos projets
      </p>
    </div>
    <div class="h-screen custom-bg">
      <h1 class="text-4xl font-bold text-white">Bienvenue!</h1>
    </div>
    
  </div>
</template>

<script>
export default {
  watch: {},
  name: "HelloWorld",
  props: {
    msg: String,
  },
};
</script>
