./bin/console d:s:d --full-database --force
./bin/console d:m:m --no-interaction
PGPASSWORD=pass psql -h db -U user -d app -a -f ./fixtures/candy.sql