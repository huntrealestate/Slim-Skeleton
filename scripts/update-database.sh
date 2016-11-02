
#!/bin/sh

CONFIG=/home/ec2-user/huntbid/bin/app-settings.php

HOST=$($CONFIG db.huntbiddb.host)
PORT=$($CONFIG db.huntbiddb.port)
NAME=$($CONFIG db.huntbiddb.database)
USER=$($CONFIG db.huntbiddb.username)
PASS=$($CONFIG db.huntbiddb.password)

cd /home/ec2-user/huntbid/database/schema/
set -x
liquibase --url="jdbc:mysql://$HOST:$PORT/$NAME?createDatabaseIfNotExist=true" --username="$USER" --password="$PASS" --changeLogFile=huntbid.xml "$@"
