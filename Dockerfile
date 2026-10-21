# Imagem do php
FROM php:8.2
# Imagem do composer
FROM composer:2.10

# Define o diretório de trabalho para a aplicação
WORKDIR /usr/local/app

# Copia o arquivo json com as deps
COPY composer.json ./

COPY . .
# Remove a pasta vendor copiada(Evitar conflito de versões)
RUN rm -rf vendor

# Instala as deps do projeto
RUN composer install 

# Libera o container na porta 8000
EXPOSE 8000  

# Inicia a aplicação laravel no host 0.0.0.0:8000(8000 porta padrão)
# 0.0.0.0 Para poder escutar requisições da interface de rede interna do docker e externa do pc host
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]