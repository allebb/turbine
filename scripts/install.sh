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
echo "===================================="
echo .
echo "Welcome to the Turbine installer, This installer will automatically install and"
echo "configure the required packages and dependencies required to run the Turbine software."
echo "It is recommended that you only install this on a clean server with no other"
echo "web servers installed and running!"
echo.
echo "Please tell this installer where it can find the extracted download directory "
echo "for example eg. /home/jdoe/downloads/turbine_1.0.0, if you haven't yet extracted"
echo "the download archieve do so with: tar -zxvf turbine_1.x.x.tar.gz"
echo .
echo "If you wish to cancel this installer, please press CTRL+C now!"
echo .
echo -n "Install from: "
read EXTRACTED_FILES
echo .

# LLets see if the directory does not exist..
if [ ! -d "$EXTRACTED_FILES" ]; then
    echo 'That specified directory does not exist, please try again by re-running this install script!';
    echo .
    exit
fi

# Just as another check we'll see if the composer.json file exists in the root of this directory otherwise
# they've probably given us the wrong directory!
if [ ! -f "$EXTRACTED_FILES/composer.json" ]; then
    echo 'It would appear that the folder you have specified is incorrect, it should'
    echo 'be the path to the root of the extracted tar.gz file (no trailing slash),'
    echo 'eg. the folder that contains the composer.json file, please try again'
    echo 'by re-running this install script!'
    echo .
    exit
fi

# We now install the main packages required by the Turbine software.
echo "Installing required packages..."
apt-get -y install nginx php5-fpm php5-curl php5-json php5-sqlite php5-mcrypt

echo "Configuring Nginx..."
# We now need to make some changes to the default nginx.conf file...
echo '# Load the Turbine WebGUI configuration.' >> /etc/nginx/nginx.conf
echo 'include /etc/turbine/configs/common/turbine_nginx.conf' >> /etc/nginx/nginx.conf
sed -i "s/include \/etc\/nginx\/sites-enabled\/\*/include \/etc\/turbine\/configs\/\*\.enabled.conf/g" /etc/nginx/nginx.conf

echo "Configuring PHP-FPM for Nginx..."
# Lets now configure PHP-FPM...
sed -i "s/\;listen = 127\.0\.0\.1\:9000/listen = \/tmp\/php5-fpm\.sock/g" /etc/php5/fpm/pool.d/www.conf

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

# We'll now add a new account for Nginx to run under and will also add that user to the sudoers list (as I can't think of a more secure way to do it at present)
echo 'Adding nginx user to sudoers...'
rm /etc/sudoers.new
cp /etc/sudoers /etc/sudoers.new
echo "$NGINX_USER ALL = NOPASSWD: service nginx reload" >> /etc/sudoers.new
cp /etc/sudoers.new /etc/sudoers
rm /etc/sudoer.new
/etc/init.d/sudo force-reload

# We now start Nginx!
echo "Starting Turbine (nginx)..."
/etc/init.d/php5-fpm restart
/etc/init.d/nginx restart


echo "Installation complete!"
echo .
echo  "You should now be able to login and administer Turbine using the following details:"
echo .
echo "  Address:  http://{$HOSTNAME}:8280"
echo "  Username: admin"
echo "  Password: password"
echo .
echo "Thanks for using Turbine!"
echo .
echo "For help and support please visit the website at: http://bobsta63.github.io/turbine/"