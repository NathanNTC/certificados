<template>
    <div class="validate-container">
      <h1>Verifique seu Certificado</h1>
      <form @submit.prevent="validateCertificate">
        <input
          v-model="certificateId"
          type="text"
          placeholder="Digite o código do certificado"
          class="certificate-input"
          required
        />
        <button type="submit" class="validate-button">Verificar</button>
      </form>
      <p v-if="message" :class="messageClass">{{ message }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  // URLs da API
  const CERTIFICATE_API_URL = import.meta.env.VITE_API_CERTIFICATE_URL;
  
  // Variáveis de estado
  const certificateId = ref('');
  const message = ref('');
  const messageClass = ref('');
  const router = useRouter();
  
  // Obter o token do localStorage
  const token = localStorage.getItem('token');
  
  // Função para validar o certificado
  const validateCertificate = async () => {
    if (!token) {
      message.value = 'Você precisa estar logado para verificar o certificado.';
      messageClass.value = 'error-message';
      router.push('/login');
      return;
    }
  
    try {
      const response = await axios.get(
        `${CERTIFICATE_API_URL}/certificates/validate/${certificateId.value}`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
  
      message.value = `Certificado válido! Detalhes: ${response.data.data.user_name} - ${response.data.data.event_name}`;
      messageClass.value = 'valid-message';
    } catch (error) {
      if (error.response && error.response.status === 404) {
        message.value = 'Certificado não emitido pelo nosso sistema.';
        messageClass.value = 'invalid-message';
      } else {
        message.value = 'Erro ao validar o certificado. Tente novamente mais tarde.';
        messageClass.value = 'error-message';
      }
    }
  };
  </script>
  
  <style src="../css/validate.css" scoped></style>
  