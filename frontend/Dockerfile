# Etapa 1: Build do projeto
FROM node:18-alpine AS build-stage

# Configuração do diretório de trabalho
WORKDIR /app

# Copia os arquivos de configuração do projeto
COPY package*.json ./

# Instala as dependências do projeto
RUN npm install

# Copia todo o código do projeto
COPY . .

# Realiza o build do projeto Vue.js para produção
RUN npm run build

# Etapa 2: Servindo o projeto com o servidor `serve`
FROM node:18-alpine AS production-stage

# Configuração do diretório de trabalho para produção
WORKDIR /app

# Copia os arquivos de build do estágio anterior
COPY --from=build-stage /app/dist ./dist

# Instala o `serve`, um servidor para arquivos estáticos
RUN npm install -g serve

# Exposição da porta 5173 (pode ser alterada conforme sua necessidade)
EXPOSE 5173

# Comando para iniciar o servidor `serve`
CMD ["serve", "-s", "dist", "-l", "5173"]
