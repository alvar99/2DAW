# PFC

## Requirments

* docker-compose

## To Start

Install dependencies on local:

```shell
cd api && npm i && cd ..
cd e2e && npm i && cd ..
cd front && npm i && cd ..
```

Create `.env` file on root path and set variables for environment. Can copy `.env.example` variables and change it.

> Be sure that service on ports 3306 (like mysql for database) and 4200 (for front) are stopped

```shell
docker-compose build
docker-compose up -d
```

> Front project run on [http://localhost:4200](http://localhost:4200)

> Api project run on [http://localhost:8080/api](http://localhost:8080/api)

## Run all test and linter
```shell
./run-test.sh
```
