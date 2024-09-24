#!/bin/sh

# Instala as dependências do Composer
echo "Instalando dependências do Composer..."
composer install --optimize-autoloader

# Executa as migrações
echo "Executando as migrações..."
php artisan migrate --force

php artisan db:seed
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=RankSeeder

# Ajusta permissões para o diretório storage e bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Inicia o Supervisor
echo "Iniciando o Supervisor..."
supervisord -c /etc/supervisor/conf.d/supervisord.conf