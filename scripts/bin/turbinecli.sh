#!/usr/bin/env bash

#############################################################
# Turbine Appliance CLI Admin Utility                       #
# http://bobsta63.github.io/turbine/                        #
# Created by: Bobby Allen (ballen@bobbyallen.me) 17/10/2013 #
#############################################################

# Function to echo out the help and info text.
function display_help {
    echo ""
    echo "Turbine CLI Admin Utiltiy"
    echo "Written by Bobby Allen, October 2013"
    echo ""
    echo "Available commands:-"
    echo ""
    echo "   $0 start        Starts the dependent Turbine daemons"
    echo "   $0 stop         Stops the dependent Turbine daemons"
    echo "   $0 status       Displays the current daemon statuses"
    echo "   $0 restart      Restart the dependent Turbine daemons"
    echo "   $0 reload       Reloads the dependent Turbine daemons"
    echo "   $0 factoryreset Restores configuration back to default"
    echo "   $0 generatekey  Generates a new random API key"
    echo "   $0 adminreset   Resets the 'admin' password"
    echo "   $0 --help       Displays this help screen"
    echo ""
}

# Function to execute a custom command via. artisan.
function execute_artisan_cmd() {
    php /etc/turbine/webapp/artisan turbine:$1
}

# Lets work out what we need to do!
case "$1" in
        start)
            service php5-fpm start
            service nginx start
            ;;

        stop)
            service php5-fpm stop
            service nginx stop
            ;;

        status)
            service nginx status
            service php5-fpm status
            ;;
        restart)
            service php5-fpm restart
            service nginx restart
            ;;
        reload)
            service php5-fpm reload
            service nginx reload
            ;;
        factoryreset)
            execute_artisan_cmd $1
            ;;
        generatekey)
            execute_artisan_cmd $1
            ;;
        adminreset)
            execute_artisan_cmd $1
            ;;
        *)
            display_help $1
            exit 1
esac

