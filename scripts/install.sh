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
#apt-get -y install nginx php5-fpm php5-curl php5-json php5-sqlite php5-mcrypt

echo "Configuring Nginx..."
# We now need to make some changes to the default nginx.conf file...
sed -i "s/\/etc/nginx/sites-enabled/*;/\/etc/turbine/configs/*.enabled.conf;/g" /etc/nginx/nginx.conf

echo "Configuring PHP..."
# Lets now configure PHP-FPM...

echo "Creating directory structures..."
# Lets now create the base folders which we need
mkdir /etc/turbine # The main application path.
mkdir /etc/turbine/app # This is where the main web app code lives!
mkdir /etc/turbine/configs # Nginx VHOST NLB/Proxy configs will be stored here!
mkdir /var/log/turbine/ # Nginx VHOST access and error files will be stored here!

# Now we will copy the application files over to the /etc/turbine/app directory


# Now we set any required directory permissions as required.

# We now start Nginx!
echo "Starting Turbine (nginx)..."
/etc/init.d/nginx start

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