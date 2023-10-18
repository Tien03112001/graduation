#!/bin/sh
rm -rf ee.bizapp/.env
cp ee.bizapp/.env.$1 ee.bizapp/.env

rm -rf ee.chatapp/.env
cp ee.chatapp/.env.$1 ee.chatapp/.env

rm -rf ee.chatws/.env
cp ee.chatws/.env.$1 ee.chatws/.env

rm -rf ee.supervisor/.env
cp ee.supervisor/.env.$1 ee.supervisor/.env

rm -rf ee.theme/.env
cp ee.theme/.env.$1 ee.theme/.env

rm -rf ee.webapp/.env
cp ee.webapp/.env.$1 ee.webapp/.env

rm -rf ee.webcdn/.env
cp ee.webcdn/.env.$1 ee.webcdn/.env

rm -rf .env
cp .env.$1 .env

chown -R 33:33 webcdn/

#docker-compose exec kafka kafka-topics --create --bootstrap-server localhost:9092 --if-not-exists --replication-factor 1 --partitions 1 --topic logging
#docker-compose exec kafka kafka-topics --create --bootstrap-server localhost:9092 --if-not-exists --replication-factor 1 --partitions 1 --topic fb_tracking
#docker-compose up -d --build
