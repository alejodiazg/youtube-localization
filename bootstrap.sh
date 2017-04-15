apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo "deb http://repo.mongodb.org/apt/ubuntu "$(lsb_release -sc)"/mongodb-org/3.0 multiverse" > /etc/apt/sources.list.d/mongodb-org-3.0.list

apt-get update
DEBIAN_FRONTEND=noninteractive apt-get -y -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" dist-upgrade
apt-get install -y zip curl git vim

apt-get install -y python-software-properties
yes | add-apt-repository ppa:ondrej/php
apt-get update
apt-get install -y mongodb-org

apt-get install -y php7.0 php7.0-cli php7.0-curl php7.0-dev php7.0-json php7.0-opcache php7.0-pgsql php7.0-mbstring php-mongodb php7.0-xml

apt-get install -y nginx
# http://www.falexandrou.com/2014/02/13/vagrant-apache-or-nginx-serving-corrupt-javascript-and-css-files/

# Setup PHP
sed -i.bak -e 's/www-data/vagrant/' /etc/php/7.0/fpm/pool.d/www.conf
echo "cgi.fix_pathinfo=0" >> /etc/php/7.0/fpm/php.ini
/etc/init.d/php7.0-fpm restart

# Update SSL
apt-get install -y --only-upgrade openssl
apt-get install -y --only-upgrade libssl-dev

# Setup nginx
#sh /var/www/localizator/ssl/generate.sh
sed -i'' -e 's/www-data/vagrant/' /etc/nginx/nginx.conf
sed -i'' -e 's/sendfile on/sendfile off/' /etc/nginx/nginx.conf
cp /var/www/localizator/nginx.conf.vagrant /etc/nginx/sites-enabled/default
service nginx restart
update-rc.d nginx defaults

# Correct permissions for Laravel storage
# chmod -R 775 /var/www/localizator/storage

# Copy Laravel environment config
cp /var/www/localizator/.env.vagrant /var/www/localizator/.env

# Setup composer
cp /var/www/localizator/composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
su - vagrant -c "cd /var/www/localizator; composer install --ignore-platform-reqs"
su - vagrant -c "cd /var/www/localizator; composer update --ignore-platform-reqs"

# Setup Node
echo "deb https://deb.nodesource.com/node_6.x precise main" > /etc/apt/sources.list.d/nodejs.6.list
echo "deb-src https://deb.nodesource.com/node_6.x precise main" >> /etc/apt/sources.list.d/nodejs.6.list
curl -s https://deb.nodesource.com/gpgkey/nodesource.gpg.key | apt-key add -
apt-get update
apt-get install -y nodejs

# Setup Webpack / Gulp
npm install -g webpack
npm install -g gulp-cli

# NPM Install
su - vagrant -c "cd /var/www/localizator; npm install"

# Yarn
#npm install yarn -g
#su - vagrant -c "cd /var/www/localizator; yarn"

# Needed for marino theme
npm install -g grunt-cli
npm install -g bower
apt-get install -y ruby-full
gem install sass
# Needed for uncss
npm install -g gulp
npm install -g uncss
npm install -g gulp-uncss
npm install

# Prepare bash profile
echo "cd /var/www/localizator" >> /home/vagrant/.bashrc
echo "export LC_ALL=C" >> /home/vagrant/.bashrc
