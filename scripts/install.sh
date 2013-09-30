#!/bin/bash

###########################################################
# Turbine Appliance Installer Script for Ubuntu 12.04 LTS #
# http://bobsta63.github.io/turbine/                      #
# Created by: Bobby Allen (ballen@bobbyallen.me)          #
###########################################################

TURBINE_VERSION='1.0 alpha'
HOSTNAME=`cat /etc/hostname`

echo "Turbine Installer v.$TURBINE_VERSION"

# We now install the main packages required by the Turbine software.
echo "Installing required packages..."
apt-get -y install nginx php5-fpm php5-curl php5-json php5-sqlite php5-mcrypt

echo "Configuring Nginx..."
# We now need to make some changes to the default nginx.conf file...
echo '# Load the Turbine WebGUI configuration.' >> /etc/nginx/nginx.conf
echo 'include /etc/turbine/configs/webapp/turbine_nginx.conf' >> /etc/nginx/nginx.conf
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
mkdir /etc/turbine/configs/webapp # The main webGUI configuration for Nginx.
mkdir /etc/turbine/configs/common # I've added this to hold the 'common' Laravel 4 nginx config.
mkdir /var/log/turbine # Nginx VHOST access and error files will be stored here!

# Now we will copy the application files over to the /etc/turbine/app directory


# Now we set any required directory permissions as required.


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