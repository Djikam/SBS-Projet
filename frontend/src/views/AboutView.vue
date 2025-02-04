<template>
  <div class="about">
    <h1>This is an about page</h1>
  </div>
  <div class="grid justify-center home">
    <div class="flex space-x-4">
      <img alt="Vue logo" src="../assets/logo.jpeg" class="flex w-20 h-20 "><h1 class="inline mt-5 font-serif text-4xl text-pink-600">SBS</h1>
    </div>
    <HelloWorld msg="Welcome to Your Vue.js App"/>
    <div class="h-screen bg-gray-500">
      <div class="flex items-center justify-center h-full"></div></div>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  data () {
    return {
    }
  },
  name: 'AboutView',
  components: {
    HelloWorld
  }
}
</script>