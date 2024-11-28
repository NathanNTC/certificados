<template>
  <!-- Exibir a sidebar apenas se o usuário estiver logado -->
  <div v-if="isAuthenticated" class="sidebar">
    <div class="sidebar-header">
      <h2>Logo</h2>
      <!-- Exibir nome do usuário logado -->
      <p v-if="userName">Bem-vindo, {{ userName }}!</p>
    </div>
    <nav class="sidebar-nav">
      <ul>
        <li><router-link to="/eventos">Eventos</router-link></li>
        <li><router-link to="/certificados">Meus Certificados</router-link></li>
        <li><router-link to="/inscricoes">Minhas inscrições</router-link></li>
        <!-- Botão para redirecionar para a página de validação -->
        <li>
          <button @click="goToValidateCertificate">Validar Certificado</button>
        </li>
        <!-- Logout -->
        <li><a href="#" @click="logout">Logout</a></li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Estado para verificar se o usuário está logado
const isAuthenticated = ref(false);

// Nome do usuário (do localStorage)
const userName = ref(localStorage.getItem('userName') || 'Usuário');

// Verificar se o usuário está logado ao carregar o componente
onMounted(() => {
  // Verificar se o token de autenticação está no localStorage
  if (localStorage.getItem('authToken')) {
    isAuthenticated.value = true;  // Se estiver, o usuário está logado
    userName.value = localStorage.getItem('userName') || 'Usuário';  // Definir o nome do usuário
  } else {
    isAuthenticated.value = false;  // Caso contrário, o usuário não está logado
  }
});

// Função de logout
const logout = () => {
  // Remover o token e o nome do usuário do localStorage
  localStorage.removeItem('authToken');
  localStorage.removeItem('userName');
  
  // Atualizar o estado para que a sidebar não seja mais exibida
  isAuthenticated.value = false;

  // Redirecionar para a página de login
  router.push('/');
};

// Função para redirecionar para a página de validação de certificado
const goToValidateCertificate = () => {
  router.push('/validar-certificado');
};
</script>

<style src="../css/sidebar.css"></style>
