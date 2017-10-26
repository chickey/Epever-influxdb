mkdir epeverlogger
cd epeverlogger

sudo apt-get install apt-transport-https curl
curl https://bintray.com/user/downloadSubjectPublicKey?username=bintray | sudo apt-key add -
echo "deb https://dl.bintray.com/fg2it/deb-rpi-1b jessie main" | sudo tee -a /etc/apt/sources.list.d/grafana.list
sudo apt-get update
sudo apt-get install grafana

sudo apt-get install influxdb
sudo apt-get install socat

sudo apt-get install php-fpm
sudo apt-get install php-curl

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
composer require influxdb/influxdb-php

wget https://github.com/toggio/PhpEpsolarTracer/archive/master.zip
unzip -j master.zip
rm master.zip
wget https://github.com/chickey/Epever-influxdb/raw/master/logger.php

crontab -l | { cat; echo "0 0 0 0 0 php /home/pi/epeverlogger/logger.php"; } | crontab -
