import { createApp } from 'vue';
import './style.css';
import App from './App.vue';

// Importe o router configurado corretamente
import router from './router'; // Sem o '/src'

const app = createApp(App);

// Use o Vue Router na instância do Vue
app.use(router);

app.mount('#app');
