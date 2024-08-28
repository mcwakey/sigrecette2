
---

### Manuel Technique d'Installation de l'Application SIGRECETTE sur un Serveur Linux Apache

Ce manuel est destiné à un informaticien.
Ce guide détaille les étapes nécessaires pour installer l'application SIGRECETTE sur un serveur Linux avec Apache.
Ce manuel est basé sur un serveur Linux, plus précisément Ubuntu Server, choisi pour ses meilleures performances par
rapport à un serveur Windows. Suivez ces instructions pour configurer l'environnement et déployer l'application.

#### Prérequis

- Un serveur web (Ubuntu server et apache sont utilisé dans cet exemple)
- Accès root ou utilisateur avec des privilèges sudo
- Un nom de domaine pointant vers le serveur ou une adresse IP publique(facultatif)
- Un serveur de base de données (MySQL/MariaDB)
- PHP 8.0.2 ou supérieur
- Composer
- Node.js

#### Étape 1 : Mettre à jour le Système

Mettez à jour les paquets de votre système :

```bash
sudo apt update
sudo apt upgrade -y
```

#### Étape 2 : Installer Apache

Installez Apache :

```bash
sudo apt install apache2 -y
```

Démarrez et activez Apache pour qu'il s'exécute au démarrage :

```bash
sudo systemctl start apache2
sudo systemctl enable apache2
```

#### Étape 3 : Installer PHP et les Extensions

Ajoutez le dépôt de PHP et installez PHP 8.0.2 et les extensions nécessaires :

```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.0 php8.0-cli php8.0-fpm php8.0-mysql php8.0-xml php8.0-mbstring php8.0-curl php8.0-zip php8.0-bcmath php8.0-json php8.0-gd -y
```

#### Étape 4 : Installer Composer

Téléchargez et installez Composer :

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Étape 5 : Installer et Configurer MySQL/MariaDB

Installez MySQL ou MariaDB :

```bash
sudo apt install mysql-server -y
```

Sécurisez l'installation :

```bash
sudo mysql_secure_installation
```

Créez une base de données et un utilisateur pour SIGRECETTE :

```bash
sudo mysql -u root -p

CREATE DATABASE sigrecette_db;
CREATE USER 'sigrecette_user'@'localhost' IDENTIFIED BY 'votre_password';
GRANT ALL PRIVILEGES ON sigrecette_db.* TO 'sigrecette_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Étape 6 : Télécharger l'Application SIGRECETTE

Si le projet est dans un dépôt Git, placez-vous dans le répertoire `/var/www` et clonez votre dépôt SIGRECETTE :

```bash
cd /var/www
sudo git clone https://github.com/your-repo/sigrecette-app.git
sudo chown -R www-data:www-data sigrecette-app
cd sigrecette-app
```

Si le projet est fourni sous forme d'un fichier zip, téléchargez et extrayez le fichier dans le répertoire `/var/www` :

```bash
cd /var/www
sudo wget http://example.com/your-sigrecette-app.zip -O sigrecette-app.zip
sudo unzip sigrecette-app.zip -d sigrecette-app
cd sigrecette-app
```
Définissez les permissions appropriées pour les répertoires de stockage et de cache :

```bash
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache

```
Installez les dépendances avec Composer :

```bash
composer install
```

#### Étape 7 : Configurer l'Environnement Laravel

Copiez le fichier `.env.example` en `.env` et modifiez les informations de base de données et autres configurations nécessaires :

```bash
cp .env.example .env
nano .env
```

Générez la clé de l'application :

```bash
php artisan key:generate
```

#### Étape 8 : Configurer Apache

Créez un fichier de configuration Apache pour l'application SIGRECETTE :

```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Ajoutez le contenu suivant :

```apache
<VirtualHost *:80>
    ServerAdmin admin@example.com
    ServerName example.com
    DocumentRoot /var/www/sigrecette-app/public

    <Directory /var/www/sigrecette-app/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sigrecette-app-error.log
    CustomLog ${APACHE_LOG_DIR}/sigrecette-app-access.log combined
</VirtualHost>
```

Activez la nouvelle configuration et mod_rewrite :

```bash
sudo a2ensite 000-default.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Étape 9 : Déployer l'Application

Exécutez les migrations et les seed de base de données pour charger la configuration par défaut :

```bash
php artisan migrate --seed
```



Créez un lien symbolique pour le dossier `storage` :

```bash
php artisan storage:link
```
Puis veillez creer un adminstrateur system avec cette commande
```bash
php artisan make-admin username youremail@forfonfig.com password
```
Puis veillez vous loger puis charger le fichers des contribuables si disponible et
verifier si tout fonction puis créer le compte de
l'adminstrateur de la commune.
Puis veillez suprimé l'admin system avec cette commande.
```bash
php artisan delete-admin
```
#### Warnings Importants

- **Compte Admin Système :** Assurez-vous de suprimer l'accès au compte admin système après la configuration du compte de l'adminstateur ou à la fin de l'instalation. Évitez d'utiliser ce compte pour des tâches quotidiennes.
- **Compte Admin Système :** Ne sert qu'a la configuration de base du logicel ou le dévéloppement!!!

#### Conclusion

Votre application SIGRECETTE devrait maintenant être accessible via votre nom de domaine ou l'adresse IP du serveur. Testez l'accès en ouvrant un navigateur et en naviguant vers `http://example.com`.

Si vous rencontrez des problèmes, consultez les journaux Apache :

```bash
sudo tail -f /var/log/apache2/error.log
```

N'hésitez pas à ajuster les configurations selon vos besoins spécifiques.

---
