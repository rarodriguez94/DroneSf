#!/bin/bash

#php app/console doctrine:database:drop  --env=test --force
#php app/console doctrine:database:create  --env=test

if [ ${MYSQL_PASSWORD} ]; then
    echo "executing drone drone_test"
    mysql -u root -e 'CREATE DATABASE drone_test;'
    mysql -u root -D drone_test < ./test/sql/db-structure-test.sql
    mysql -u root -D drone_test < ./test/sql/db-init-test.sql
else
    mysql -u root -proot -h localhost drone_test < ./test/sql/db-structure-test.sql
    mysql -u root -proot -h localhost drone_test < ./test/sql/db-init-test.sql

fi
php app/console cache:warmup --env=test
phpunit -c app
php bin/behat
