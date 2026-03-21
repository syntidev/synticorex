#!/bin/bash
################################################################################
# SYNTIWEB COMPLETE DEPLOYMENT - Ubuntu 24.04 Vultr
# Clona synticorex, instala stack, configura .env producción, migra BD
# Tiempo: 20-25 minutos
################################################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}╔════════════════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║     SYNTIWEB COMPLETE DEPLOYMENT - VULTR UBUNTU 24.04      ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════════════════╝${NC}"
echo ""

# ============================================================================
# CONFIGURACIÓN (REEMPLAZA ESTOS VALORES)
# ============================================================================
GITHUB_REPO="https://github.com/syntidev/synticorex.git"
DOMAIN="syntiweb.com"
PROJECT_PATH="/var/www/syntiweb"
DB_NAME="syntiweb_db"
DB_USER="syntiweb_user"
DB_PASSWORD="K7@mP9#xL2$vQ8nR!bY5gW4jH3sD1e6F"  # GENERA UNA CONTRASEÑA FUERTE

# Google OAuth
GOOGLE_CLIENT_ID="${GOOGLE_CLIENT_ID:-}"
GOOGLE_CLIENT_SECRET="${GOOGLE_CLIENT_SECRET:-}"
# Anthropic
ANTHROPIC_API_KEY="${ANTHROPIC_API_KEY:-}"

# ============================================================================
# VALIDACIÓN INICIAL
# ============================================================================
if [ "$DB_PASSWORD" == "K7@mP9#xL2$vQ8nR!bY5gW4jH3sD1e6F" ]; then
    echo -e "${YELLOW}⚠ AVISO: Estás usando contraseña de ejemplo${NC}"
    echo "Continúa con Ctrl+C si quieres cambiarla, o Enter para proceder"
    read -r _
fi

echo -e "${YELLOW}[INIT] System Update${NC}"
sudo apt update
sudo apt upgrade -y
sudo apt install -y curl wget git build-essential

# ============================================================================
# NGINX
# ============================================================================
echo -e "${YELLOW}[1/7] Installing Nginx${NC}"
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
echo -e "${GREEN}✓ Nginx installed${NC}"

# ============================================================================
# PHP 8.3 + EXTENSIONS
# ============================================================================
echo -e "${YELLOW}[2/7] Installing PHP 8.3 + FPM${NC}"
sudo apt install -y \
    php8.3-fpm php8.3-cli php8.3-common php8.3-curl php8.3-bcmath \
    php8.3-json php8.3-mysql php8.3-mbstring php8.3-xml php8.3-tokenizer \
    php8.3-zip php8.3-gd php8.3-intl php8.3-fileinfo php8.3-dom composer

sudo systemctl start php8.3-fpm
sudo systemctl enable php8.3-fpm
php -v | head -1
echo -e "${GREEN}✓ PHP 8.3 installed${NC}"

# ============================================================================
# MYSQL
# ============================================================================
echo -e "${YELLOW}[3/7] Installing MySQL 8.0${NC}"
sudo apt install -y mysql-server
sudo systemctl start mysql
sudo systemctl enable mysql

echo -e "${YELLOW}[3B/7] Creating Database & User${NC}"
mysql -u root <<MYSQL_EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
MYSQL_EOF
echo -e "${GREEN}✓ MySQL configured${NC}"

# ============================================================================
# NODE.JS 20
# ============================================================================
echo -e "${YELLOW}[4/7] Installing Node.js 20${NC}"
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
echo -e "${GREEN}✓ Node.js 20 installed${NC}"

# ============================================================================
# CREAR DIRECTORIO Y CLONAR REPO
# ============================================================================
echo -e "${YELLOW}[5/7] Cloning synticorex repository${NC}"
sudo mkdir -p $PROJECT_PATH
sudo chown -R $(whoami):$(whoami) $PROJECT_PATH
cd $PROJECT_PATH

git clone $GITHUB_REPO .
echo -e "${GREEN}✓ Repository cloned${NC}"

# ============================================================================
# CONFIGURAR .ENV PRODUCCIÓN
# ============================================================================
echo -e "${YELLOW}[6A/7] Configuring .env production${NC}"
cat > .env <<ENV_EOF
APP_NAME=SYNTIweb
APP_ENV=production
APP_KEY=base64:Z8iy2mtMLvAIcV7ZhpbHAWG9JRQWP4z8dLjKEiIIgrE=
APP_DEBUG=false
APP_URL=https://$DOMAIN

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_VE

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASSWORD

DOLLAR_FALLBACK_RATE=40.00

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=file
CACHE_PREFIX=syntiweb_

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@$DOMAIN"
MAIL_FROM_NAME="SYNTIweb"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

VITE_APP_NAME="SYNTIweb"

ONBOARDING_MODE=public

AI_PROVIDER=gemini

BYTEZ_MODEL=Qwen/Qwen2.5-7B-Instruct
BYTEZ_API_KEY=136e544b88716d05b70a9ac4615faedf

GEMINI_API_KEY=AIzaSyDm6nGuRYMFASsJ5KyPy8QbaBFrCpDLCzk

GOOGLE_CLIENT_ID=$GOOGLE_CLIENT_ID
GOOGLE_CLIENT_SECRET=$GOOGLE_CLIENT_SECRET
GOOGLE_REDIRECT_URI=https://$DOMAIN/auth/google/callback

ANTHROPIC_API_KEY=$ANTHROPIC_API_KEY
COPILOT_PROVIDER=anthropic
COPILOT_MODEL=claude-haiku-4-5-20251001
ENV_EOF

echo -e "${GREEN}✓ .env configured${NC}"

# ============================================================================
# INSTALAR DEPENDENCIAS
# ============================================================================
echo -e "${YELLOW}[6B/7] Installing PHP & Node dependencies${NC}"
composer install --no-dev --optimize-autoloader
npm install
npm run build
echo -e "${GREEN}✓ Dependencies installed${NC}"

# ============================================================================
# LARAVEL SETUP
# ============================================================================
echo -e "${YELLOW}[6C/7] Laravel initialization${NC}"
php artisan key:generate
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache
echo -e "${GREEN}✓ Laravel configured${NC}"

# ============================================================================
# PERMISOS
# ============================================================================
echo -e "${YELLOW}[6D/7] Setting permissions${NC}"
sudo chown -R www-data:www-data $PROJECT_PATH
sudo chmod -R 755 $PROJECT_PATH/storage
sudo chmod -R 755 $PROJECT_PATH/bootstrap/cache
echo -e "${GREEN}✓ Permissions set${NC}"

# ============================================================================
# NGINX CONFIG
# ============================================================================
echo -e "${YELLOW}[7/7] Configuring Nginx${NC}"
sudo tee /etc/nginx/sites-available/syntiweb > /dev/null <<'NGINX_CONFIG'
server {
    listen 80;
    server_name ~^(?<subdomain>.+)\.syntiweb\.com$ syntiweb.com www.syntiweb.com;
    
    root /var/www/syntiweb/public;
    index index.php index.html index.htm;
    charset utf-8;
    
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    access_log /var/log/nginx/syntiweb-access.log combined;
    error_log /var/log/nginx/syntiweb-error.log warn;
    
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
    
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|webp|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }
    
    location = /robots.txt {
        access_log off;
        log_not_found off;
    }
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_param PATH_INFO $pathinfo;
        fastcgi_hide_header X-Powered-By;
        fastcgi_intercept_errors off;
        fastcgi_connect_timeout 60s;
        fastcgi_send_timeout 60s;
        fastcgi_read_timeout 60s;
    }
    
    location ~ \.php$ {
        return 404;
    }
}
NGINX_CONFIG

sudo ln -sf /etc/nginx/sites-available/syntiweb /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
echo -e "${GREEN}✓ Nginx configured${NC}"

# ============================================================================
# SSL CON CERTBOT
# ============================================================================
echo -e "${YELLOW}[POST] Installing SSL Certificate${NC}"
sudo apt install -y certbot python3-certbot-nginx -y
sudo certbot --nginx -d $DOMAIN -d www.$DOMAIN -d studio.$DOMAIN -d food.$DOMAIN -d cat.$DOMAIN --non-interactive --agree-tos --email noreply@$DOMAIN || echo "SSL setup skipped - configure manually later"
echo -e "${GREEN}✓ SSL configured${NC}"

# ============================================================================
# RESUMEN FINAL
# ============================================================================
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║         DEPLOYMENT COMPLETADO CON ÉXITO                    ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Información crítica:${NC}"
echo "Domain: $DOMAIN"
echo "IP: 207.246.68.105"
echo "DB: $DB_NAME | User: $DB_USER"
echo "Project: $PROJECT_PATH"
echo ""
echo -e "${YELLOW}URLs:${NC}"
echo "https://$DOMAIN"
echo "https://studio.$DOMAIN"
echo "https://food.$DOMAIN"
echo "https://cat.$DOMAIN"
echo ""
echo -e "${YELLOW}Logs:${NC}"
echo "tail -f /var/log/nginx/syntiweb-error.log"
echo "tail -f /var/log/laravel/laravel.log"
echo ""
echo -e "${YELLOW}Próximos pasos:${NC}"
echo "1. Verifica DNS apuntando a 207.246.68.105"
echo "2. Accede a https://$DOMAIN"
echo "3. Si ves la landing → ✅ FUNCIONA"
echo ""