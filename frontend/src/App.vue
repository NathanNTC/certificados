<template>
  <div class="app-container">
    <Sidebar />
    <div class="main-content">
      <router-view />
    </div>
  </div>
</template>

<script setup>
  // Importação dos componentes
  import Login from './components/Login.vue'; 
  import Register from './components/Register.vue'; 
  import Sidebar from './components/Sidebar.vue'; // Importando a Sidebar
</script>

<style scoped>
.app-container {
  display: flex;
}

.main-content {
  margin-left: 250px; /* Dá espaço para a sidebar */
  padding: 20px;
  width: 100%;
}
</style>
