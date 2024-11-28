<template>
  <div class="login-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" v-model="email" id="email" required />
      </div>
      <div class="form-group">
        <label for="password">Senha</label>
        <input type="password" v-model="password" id="password" required />
      </div>
      <div v-if="error" class="error-message">{{ error }}</div>
      <button type="submit" class="btn">Login</button>
    </form>
    <p>
      Não tem uma conta? 
      <router-link to="/register">Registre-se aqui</router-link>
    </p>
  </div>
</template>

<script>
import axios from "axios";
import "../css/login.css";

export default {
  data() {
    return {
      email: "",
      password: "",
      error: null,
    };
  },
  methods: {
    async login() {
        try {
            // Fazer a requisição para o endpoint de login usando a variável de ambiente
            const response = await axios.post(
                `${import.meta.env.VITE_API_AUTH_URL}/login`,
                {
                    email: this.email,
                    password: this.password,
                }
            );

            // Armazenar informações no localStorage
            localStorage.setItem("authToken", response.data.token);
            localStorage.setItem("userName", response.data.user.name);
            localStorage.setItem("email", response.data.user.email);

            // Redirecionar para a página de eventos
            this.$router.push("/eventos");
          } catch (error) {
              // Tratamento de erros detalhado
              if (error.response && error.response.data) {
                  this.error =
                      error.response.data.message || "Erro de autenticação.";
              } else {
                  this.error =
                      "Ocorreu um erro inesperado. Tente novamente mais tarde.";
              }
              console.error("Erro no login:", error);
          }
      },
  },
};
</script>
