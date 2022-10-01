#!/bin/bash

cd /var/www/html/
sudo chown -R  ec2-user:apache vectorERP
sudo chmod 0750 vectorERP
chmod 777 vectorERP/logs/
chmod 777 vectorERP/storage


sudo /etc/init.d/httpd restart
