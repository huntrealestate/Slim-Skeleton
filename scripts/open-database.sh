#!/bin/sh

CONFIG=/home/ec2-user/huntbid/bin/app-settings.php

HOST=$($CONFIG db.huntbiddb.host)
PORT=$($CONFIG db.huntbiddb.port)
NAME=$($CONFIG db.huntbiddb.database)
USER=$($CONFIG db.huntbiddb.username)
PASS=$($CONFIG db.huntbiddb.password)

mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$NAME"

