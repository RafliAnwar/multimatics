# Multimatics Development

## Getting Started

We assume you have docker and vscode installed on your machine. If not, please install docker by following the instructions [here](https://docs.docker.com/get-docker/) and vscode by following the instructions [here](https://code.visualstudio.com/download).

First, clone the repository by running the following command in your terminal:

```bash
git clone https://github.com/RafliAnwar/multimatics.git
```

Then, navigate to the project directory:

```bash
cd multimatics
code .
```

Next, press `CTRL + SHIFT + P` and type `Dev Container: Rebuild and Reopen in Container`. This will build the docker container and open the project in a containerized environment.

## Laravel Development

We'll use [Laravel Sail](https://laravel.com/docs/11.x/sail) to create devcontainer for our project. To start with, we need to create project directory by running the following command:

```bash
export PROJECT_NAME=laravel001
export DEPS="mysql"
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    --user $(id -u):$(id -g) \
    laravelsail/php84-composer:latest \
    bash -c "laravel new $PROJECT_NAME --no-interaction && cd $PROJECT_NAME && php ./artisan sail:install --with=$DEPS"
```

You can replace `PROJECT_NAME` with your desired project name and `DEPS` with the dependencies you need for your project. 

The available dependencies are `mysql`, `pgsql`, `redis`, `memcached`, `meilisearch`, `minio`, `mailhog`, and `selenium`.
