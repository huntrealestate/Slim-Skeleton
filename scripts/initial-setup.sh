#!/bin/sh

cd /etc/nginx/conf.d
sudo ln -f -s /home/ec2-user/huntbid/etc/nginx/huntbid.conf .

sudo chmod 755 /home/ec2-user

cd /home/ec2-user/huntbid

CONFIG=/home/ec2-user/huntbid/bin/app-settings.php

HOST=$($CONFIG db.huntbiddb.host)
PORT=$($CONFIG db.huntbiddb.port)
NAME=$($CONFIG db.huntbiddb.database)
USER=$($CONFIG db.huntbiddb.username)
PASS=$($CONFIG db.huntbiddb.password)

echo "creating database $NAME"
#mysqladmin -u root password devpass314
#echo "mysql -h $HOST -P $PORT -uroot -pdevpass314 $NAME"
#exit;
mysql -uroot -pdevpass314 -h "$HOST" -P "$PORT" -e"CREATE DATABASE $NAME;"
mysql -uroot -pdevpass314 -h "$HOST" -P "$PORT" -e"GRANT ALL PRIVILEGES ON $NAME.* TO '$USER'@'localhost' IDENTIFIED BY '$PASS' WITH GRANT OPTION;"
mysql -uroot -pdevpass314 -h "$HOST" -P "$PORT" -e"FLUSH PRIVILEGES;"

echo updating database to latest version

/home/ec2-user/huntbid/scripts/update-database.sh migrate

echo restarting dev things
/home/ec2-user/huntbid/scripts/restart-dev-things.sh

echo checking log access
touch /home/ec2-user/huntbid/logs/error.log
chmod a+rw /home/ec2-user/huntbid/logs/error.log
touch /home/ec2-user/huntbid/logs/app.log
chmod a+rw /home/ec2-user/huntbid/logs/app.log
