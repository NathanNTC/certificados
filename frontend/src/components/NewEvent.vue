<template>
    <div class="new-event-container">
      <h1>Criar Novo Evento</h1>
      <form @submit.prevent="addEvent">
        <div class="form-group">
          <label for="nome">Nome:</label>
          <input type="text" id="nome" v-model="eventData.nome" required />
        </div>
        <div class="form-group">
          <label for="data">Data e Hora:</label>
          <input type="datetime-local" id="data" v-model="eventData.data" required />
        </div>
        <div class="form-group">
          <label for="local">Local:</label>
          <input type="text" id="local" v-model="eventData.local" required />
        </div>
        <button type="submit">Criar Evento</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  
  const eventData = ref({
    nome: '',
    data: '',
    local: ''
  });
  
  // Função para adicionar um novo evento
  const addEvent = async () => {
  try {
    // Obtém a URL da API de eventos a partir da variável de ambiente
    const apiUrl = `${import.meta.env.VITE_API_EVENT_URL}/events`;

    // Faz a requisição POST para criar o evento
    const response = await axios.post(apiUrl, eventData.value);
    alert('Evento criado com sucesso!');
    
    // Limpa os dados do formulário após o sucesso
    eventData.value = { nome: '', data: '', local: '' };
  } catch (error) {
    console.error('Erro ao criar evento:', error);
    alert('Erro ao criar evento.');
  }
};
  </script>
  
  <style src="../css/newevent.css" scoped></style>
  