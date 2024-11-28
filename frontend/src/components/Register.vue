<template>
    <div class="register-container">
      <h2>Register</h2>
      <form @submit.prevent="register">
        <div class="form-group">
          <label for="name">Nome</label>
          <input type="text" v-model="name" id="name" required />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" v-model="email" id="email" required />
        </div>
        <div class="form-group">
          <label for="password">Senha</label>
          <input type="password" v-model="password" id="password" required />
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirme sua senha</label>
          <input type="password" v-model="password_confirmation" id="password_confirmation" required />
        </div>
        <div v-if="error" class="error-message">{{ error }}</div>
        <button type="submit" class="btn">Register</button>
      </form>
      <p>Ja tem uma conta? <router-link to="/login">Login</router-link></p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import '../css/register.css'; // Importando o arquivo CSS
  
  export default {
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        error: null,
      };
    },
    methods: {
      async register() {
        // Verifica se as senhas coincidem
        if (this.password !== this.password_confirmation) {
          this.error = 'Passwords do not match';
          return;
        }

        try {
          // Obtém as URLs da API de autenticação e e-mails
          const authApiUrl = `${import.meta.env.VITE_API_AUTH_URL}/register`;
          const emailApiUrl = `${import.meta.env.VITE_API_EMAIL_URL}/send-confirmation-email`;

          // Faz a requisição POST para registrar o usuário
          const response = await axios.post(authApiUrl, {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          });

          // Envia o e-mail de confirmação
          await axios.post(emailApiUrl, {
            email: this.email,
          });

          // Salva o token no localStorage
          localStorage.setItem('token', response.data.token);

          // Redireciona o usuário para a página de eventos
          this.$router.push('/eventos');
        } catch (error) {
          if (error.response && error.response.data) {
            this.error = error.response.data.error || 'An error occurred during registration.';
          } else {
            this.error = 'An error occurred. Please try again later.';
          }
        }
      },
    },
  };
  </script>
  