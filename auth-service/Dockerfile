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

# Exposição da porta em que o serviço irá rodar
EXPOSE 8000

# Comando para iniciar o serviço
CMD ["npm", "start"]
