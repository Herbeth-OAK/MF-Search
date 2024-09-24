# Usa a imagem oficial do PHP com FPM
FROM php:8.2-fpm

# Instala dependências do sistema, PHP, Nginx e Supervisor
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_pgsql zip

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o Composer do contêiner oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia os arquivos da aplicação para o contêiner
COPY . .

# Copia o arquivo de configuração do Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Copia o script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Copia o arquivo de configuração do Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Adiciona permissões de execução ao script de entrada
RUN chmod +x /usr/local/bin/entrypoint.sh

# Cria o diretório de logs e define permissões
RUN mkdir -p /var/log/nginx && chown -R www-data:www-data /var/log/nginx

# Cria o diretório de uploads e ajusta as permissões
RUN mkdir -p /var/www/html/public/uploads/users \
    && chown -R www-data:www-data /var/www/html/public/uploads/users \
    && chmod -R 775 /var/www/html/public/uploads/users

# Expõe a porta 80 para o Nginx
EXPOSE 80

# Define o script de entrada como o comando de inicialização
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]