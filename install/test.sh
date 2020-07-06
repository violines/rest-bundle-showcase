./bin/generate-keys.sh
./bin/console d:s:d --full-database --force --env=test
./bin/console d:m:m --no-interaction --env=test
PGPASSWORD=pass psql -h db -U user -d app_test -a -f ./fixtures/test.sql