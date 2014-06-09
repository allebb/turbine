Turbine
=======

Turbine is an easy to set-up and manage load-balancer and reverse proxy management interface complete with RESTful API to enable automated third-party application integration.

Installation on Ubuntu 12.04 LTS
--------------------------------
A very simple installation guide can be found here:

[http://bobbyallen.me/turbine/]()

Instalation on Ubuntu 14.04 LTS
-------------------------------

The current installer is designed to work with Ubuntu 12.04 LTS however with a few minor updates this also works with Ubuntu 14.04 LTS too, simply run the standard installation script (see above), until I've had a chance to upgrade the installer please ensure you carry out the following in after to running the standard installer:

1) Create Mycrpt module aliases for PHP:
```
ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/cli/conf.d/mcrypt.ini
ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/fpm/conf.d/mcrypt.ini
```

2) Update php5-fpm socket location:

```
nano /etc/turbine/configs/common/laravel4_shared.conf
```

Then update the ``fastcgi_pass`` line to match:

```
fastcgi_pass                    unix:/var/run/php5-fpm.sock;
```

3) Lastly restart both Nginx and the PHP-FPM daemons like so:

```
service nginx restart
service php5-fpm restart
```

4) All done! - You should then be able to nativate to the Turbine control panel as per normal!
