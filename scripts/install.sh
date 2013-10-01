#!/usr/bin/env bash

###########################################################
# Turbine Appliance Installer Script for Ubuntu 12.04 LTS #
# http://bobsta63.github.io/turbine/                      #
# Created by: Bobby Allen (ballen@bobbyallen.me)          #
###########################################################

TURBINE_VERSION='0.1.1 dev'
NGINX_USER='www-data'
HOSTNAME=$(cat /etc/hostname)

echo "Turbine Installer (v.$TURBINE_VERSION)"
echo -e "====================================\n"
echo -e "Welcome to the Turbine installer, this installer will automatically "
echo -e "install and configure the required packages and dependencies required"
echo -e " to run the Turbine software. It is recommended that you only install"
echo -e "this on a clean server with no other web servers installed and running!\n"
echo -e "Please tell this installer where it can find the extracted download"
echo -e "directory for example eg. /home/jdoe/downloads/turbine_1.0.0.\n"
echo -e "If you wish to cancel this installer, please press CTRL+C now!\n"
echo -n "Install from: "
read EXTRACTED_FILES
echo -e "\n"

# LLets see if the directory does not exist..
if [ ! -d "$EXTRACTED_FILES" ]; then
    echo -e 'That specified directory does not exist, please try again by re-running this'
    echo -e 'install script!\n'
    exit
fi

# Just as another check we'll see if the composer.json file exists in the root of this directory otherwise
# they've probably given us the wrong directory!
if [ ! -f "$EXTRACTED_FILES/composer.json" ]; then
    echo 'It would appear that the folder you have specified is incorrect, it should'
    echo 'be the path to the root of the extracted tar.gz file (no trailing slash),'
    echo 'eg. the folder that contains the composer.json file, please try again'
    echo -e 'by re-running this install script!\n'
    exit
fi

# We now install the main packages required by the Turbine software.
echo "Installing required packages..."
apt-get update
apt-get -y install nginx php5-fpm php5-curl php5-json php5-sqlite php5-mcrypt

echo "Configuring Nginx..."
# We now need to make some changes to the default nginx.conf file...
sed -i "s/include \/etc\/nginx\/sites-enabled\/\*/include \/etc\/turbine\/configs\/common\/turbine_nginx\.conf/g" /etc/nginx/nginx.conf

echo "Configuring PHP-FPM for Nginx..."
# Lets now configure PHP-FPM...
sed -i "s/\listen = 127\.0\.0\.1\:9000/listen = \/tmp\/php5-fpm\.sock/g" /etc/php5/fpm/pool.d/www.conf

echo "Creating directory structures..."
# Lets now create the base folders which we need
mkdir /etc/turbine # The main application path.
mkdir /etc/turbine/static # A place to store nginx html file eg. maintenance.html page.
mkdir /etc/turbine/webapp # This is where the main web app code lives!
mkdir /etc/turbine/configs # Nginx VHOST NLB/Proxy configs will be stored here!
mkdir /etc/turbine/configs/common # I've added this to hold the 'common' Laravel 4 nginx config.
mkdir /var/log/turbine # Nginx VHOST access and error files will be stored here!

# Now we will copy the application files over to the /etc/turbine/app directory (we'll pull latest from GitHub).
cp -fr $EXTRACTED_FILES/* /etc/turbine/webapp/
cp -fr /etc/turbine/webapp/scripts/common/* /etc/turbine/configs/common/

# Now we set any required directory permissions as required.
chmod -R 777 /etc/turbine/webapp/app/storage
chmod -R 777 /etc/turbine/configs
chmod 777 /etc/turbine/webapp/app/database
chmod 777 /etc/turbine/webapp/app/database/production.sqlite

# We'll now add a new account for Nginx to run under and will also add that user to the sudoers list (as I can't think of a more secure way to do it at present)
echo 'Adding Nginx user to sudoers...'
echo "$NGINX_USER ALL=NOPASSWD: /usr/bin/service nginx reload" > /etc/sudoers.d/turbine
chmod 0440 /etc/sudoers.d/turbine

# May have to use as a work around for other Linux OSes (that may now have an standard 'include' directory)..
#rm /etc/sudoers.new
#cp /etc/sudoers /etc/sudoers.new
#echo "$NGINX_USER ALL=NOPASSWD: /usr/bin/service nginx reload" >> /etc/sudoers.new
#cp /etc/sudoers.new /etc/sudoers
#rm /etc/sudoer.new
# Not sure if we need to restart the sudo service for the changes to take effect so I'll keep this here for now.
/etc/init.d/sudo force-reload

# We now start Nginx!
echo "Starting Turbine (nginx deamon)..."
/etc/init.d/php5-fpm restart
/etc/init.d/nginx restart


echo -e "Installation complete!\n"
echo -e "You should now be able to login and administer Turbine using the following"
echo -e "details:\n"
echo -e "  Address:  http://$HOSTNAME:8280"
echo -e "  Username: admin"
echo -e "  Password: password\n"
echo -e "Thanks for using Turbine!\n"
echo "Visit the website at: http://bobsta63.github.io/turbine/"
