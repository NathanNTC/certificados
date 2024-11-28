<template>
  <div class="meus-certificados">
    <h1>Meus Certificados</h1>

    <div v-if="isAuthenticated">
      <ul v-if="checkins.length" class="certificados-list">
        <li v-for="checkin in checkins" :key="checkin.event_id" class="certificado-item">
          <div>
            <strong>{{ checkin.event_name }}</strong> <!-- Exibe o nome do evento -->
          </div>
          <div>
            <span>Event ID: {{ checkin.event_id }}</span> <!-- Exibe o número do event_id -->
          </div>
          <button @click="showEventDetails(checkin.event_id)">Exibir Detalhes</button>
          
          <!-- Exibe os detalhes do evento, caso haja algum evento carregado -->
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
      <p>Você precisa estar logado para acessar esta página. 
        <router-link to="/login">Faça login aqui.</router-link>
      </p>
    </div>

    <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

// URLs das APIs
const CHECKIN_API_URL = import.meta.env.VITE_API_CHECKIN_URL;
const EVENT_API_URL = import.meta.env.VITE_API_EVENT_URL;
const CERTIFICATE_API_URL = import.meta.env.VITE_API_CERTIFICATE_URL;

// Variáveis de estado
const checkins = ref([]);
const isAuthenticated = ref(false);
const errorMessage = ref(null);
const eventDetails = ref({});  // Armazenar detalhes dos eventos

// Obter dados do usuário
const email = localStorage.getItem("email");
const token = localStorage.getItem("token");
const userName = ref(localStorage.getItem('userName'));  // Obter o nome do usuário logado
const router = useRouter();

// Verificar autenticação
const checkAuthentication = () => {
  if (token) {
    isAuthenticated.value = true;
  } else {
    isAuthenticated.value = false;
    router.push("/login");
  }
};

// Carregar dados de check-ins e eventos
onMounted(async () => {
  checkAuthentication();

  if (isAuthenticated.value) {
    try {
      const checkinResponse = await axios.post(
        `${CHECKIN_API_URL}/events-by-email`,
        { email },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      const eventIds = checkinResponse.data.event_ids || [];

      if (eventIds.length === 0) {
        console.log("Nenhum evento associado a este e-mail.");
        return;
      }

      // Aqui, buscamos os detalhes de todos os eventos
      const eventPromises = eventIds.map((id) =>
        axios.get(`${EVENT_API_URL}/events/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
      );

      // Aguarda todas as requisições dos eventos
      const events = await Promise.all(eventPromises);

      // Atualiza os dados de checkins com o nome do evento
      checkins.value = events.map((response) => ({
        event_id: response.data.id,
        event_name: response.data.name, // Nome do evento
      }));
    } catch (error) {
      console.error("Erro ao buscar eventos:", error.response?.data || error.message);
      errorMessage.value =
        "Não foi possível carregar os certificados. Verifique sua conexão ou tente novamente mais tarde.";
    }
  }
});

// Função para exibir detalhes do evento
const showEventDetails = async (eventId) => {
  try {
    const response = await axios.get(
      `${EVENT_API_URL}/events/${eventId}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    // Atualiza os detalhes do evento sem sobrescrever os dados já existentes
    eventDetails.value = {
      ...eventDetails.value,
      [eventId]: response.data,  // Adiciona ou atualiza os detalhes do evento
    };
  } catch (error) {
    console.error("Erro ao buscar detalhes do evento:", error.response?.data || error.message);
    errorMessage.value = "Erro ao carregar os detalhes do evento.";
  }
};

// Baixar certificado
const downloadCertificate = async (checkin) => {
  try {
    // Obtendo o nome do evento diretamente de eventDetails
    const eventName = eventDetails.value[checkin.event_id]?.nome;

    if (!eventName) {
      alert("Nome do evento não encontrado!");
      return;
    }

    // Verificando os dados que serão enviados
    console.log("Nome do Evento:", eventName);

    // Preparando os dados para enviar
    const certificateData = {
      user_name: userName.value,  // Nome do usuário
      event_name: eventName,  // Nome do evento vindo de eventDetails
    };

    // Mostrar os dados no alerta antes de enviar a requisição
    alert(`Enviando os seguintes dados:\n\nNome do Participante: ${certificateData.user_name}\nNome do Evento: ${certificateData.event_name}`);

    // Enviar a requisição para gerar o certificado
    const response = await axios.post(
      `${CERTIFICATE_API_URL}/certificates/generate`, 
      certificateData,  // Dados no formato correto
      { responseType: "blob" }
    );

    // Criação do arquivo de PDF
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", `certificado_${eventName}.pdf`); // Usar o nome correto para o arquivo
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } catch (error) {
    console.error("Erro ao gerar certificado:", error.response?.data || error.message || "Erro desconhecido");
    errorMessage.value =
      "Erro ao gerar o certificado. Verifique os dados e tente novamente.";
  }
};

</script>

<style src="../css/Certificate.css" scoped></style>
