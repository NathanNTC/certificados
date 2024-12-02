<template>
  <div class="meus-certificados">
    <h1>Meus Certificados</h1>

    <div v-if="isAuthenticated">
      <ul v-if="checkins.length" class="certificados-list">
        <li v-for="checkin in checkins" :key="checkin.event_id" class="certificado-item">
          <div>
            <strong>{{ checkin.event_name }}</strong>
          </div>
          <div>
            <span>Event ID: {{ checkin.event_id }}</span>
          </div>
          <button @click="showEventDetails(checkin.event_id)">Exibir Detalhes</button>

          <div v-if="eventDetails[checkin.event_id]" class="event-details">
            <p><strong>Nome do Inscrito:</strong> {{ userName }}</p>
            <p><strong>Nome do Evento:</strong> {{ eventDetails[checkin.event_id].nome }}</p>
            <button @click="downloadCertificate(checkin)">Baixar Certificado</button>
          </div>
        </li>
      </ul>
      <p v-else>Você ainda não possui check-ins registrados.</p>
    </div>

    <div v-else>
      <p>
        Você precisa estar logado para acessar esta página.
        <router-link to="/login">Faça login aqui.</router-link>
      </p>
    </div>

    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const CHECKIN_API_URL = import.meta.env.VITE_API_CHECKIN_URL;
const EVENT_API_URL = import.meta.env.VITE_API_EVENT_URL;
const CERTIFICATE_API_URL = import.meta.env.VITE_API_CERTIFICATE_URL;

const checkins = ref([]);
const isAuthenticated = ref(false);
const errorMessage = ref(null);
const eventDetails = ref({});
const email = localStorage.getItem('email');
const token = localStorage.getItem('token');
const userName = ref(localStorage.getItem('userName'));
const router = useRouter();

const checkAuthentication = () => {
  isAuthenticated.value = !!token;
  if (!isAuthenticated.value) {
    router.push('/login');
  }
};

onMounted(async () => {
  checkAuthentication();
  if (isAuthenticated.value) {
    try {
      const checkinResponse = await axios.post(
        `${CHECKIN_API_URL}/events-by-email`,
        { email },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      const eventIds = checkinResponse.data.event_ids || [];

      if (!eventIds.length) {
        console.log('Nenhum evento associado a este e-mail.');
        return;
      }

      const eventPromises = eventIds.map(id =>
        axios.get(`${EVENT_API_URL}/events/${id}`, {
          headers: { Authorization: `Bearer ${token}` }
        })
      );

      const events = await Promise.all(eventPromises);
      checkins.value = events.map(response => ({
        event_id: response.data.id,
        event_name: response.data.name
      }));
    } catch (error) {
      console.error('Erro ao buscar eventos:', error.response?.data || error.message);
      errorMessage.value = 'Erro ao carregar os certificados. Verifique sua conexão ou tente novamente mais tarde.';
    }
  }
});

const showEventDetails = async (eventId) => {
  try {
    const response = await axios.get(`${EVENT_API_URL}/events/${eventId}`, {
      headers: { Authorization: `Bearer ${token}` }
    });
    eventDetails.value = { ...eventDetails.value, [eventId]: response.data };
  } catch (error) {
    console.error('Erro ao buscar detalhes do evento:', error.response?.data || error.message);
    errorMessage.value = 'Erro ao carregar os detalhes do evento.';
  }
};

const downloadCertificate = async (checkin) => {
  try {
    const eventName = eventDetails.value[checkin.event_id]?.nome;
    if (!eventName) {
      alert('Nome do evento não encontrado!');
      return;
    }

    const certificateData = {
      user_name: userName.value,
      event_name: eventName
    };

    console.log('Enviando os seguintes dados:', certificateData);

    const response = await axios.post(
      `${CERTIFICATE_API_URL}/certificates/generate`,
      certificateData,
      { responseType: 'blob' }
    );

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `certificado_${eventName}.pdf`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error('Erro ao gerar certificado:', error.response?.data || error.message || 'Erro desconhecido');
    errorMessage.value = 'Erro ao gerar o certificado. Verifique os dados e tente novamente.';
  }
};
</script>

<style scoped>
/* Estilo do componente */
.error-message {
  color: red;
  font-weight: bold;
}
.certificados-list {
  list-style-type: none;
  padding: 0;
}
.certificado-item {
  margin-bottom: 1rem;
}
.event-details {
  margin-top: 1rem;
}
</style>
