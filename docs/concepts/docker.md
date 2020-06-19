# Docker

This project uses Docker containers to build the local development environment. This environment
is managed using the file `dev/docker/docker-compose.yml`.

The Docker Compose configuration will launch all the containers necessary to run a default SquareOne project. These
services include:

* nginx
* PHP-FPM
* Memcached
* Instances of the above specific to running automated tests
* Selenium with Chrome for use in webdriver tests


## Adding Services

Some projects may require different or additional services. These can be added to `docker-compose.yml`.

As a general pattern, set up a base configuration using an `x-*` extension field and assign a YAML anchor to it.

```yaml
x-someservice: &someservice
  image: some-service:latest
```

Then create a service using aliases to reference the definition created above.

```yaml
services:
  something:
    <<: *proxynetwork
    <<: *internaldns
    <<: *someservice
```

If you need another instance of the service running for tests, you can reference the same anchors.

```yaml
services:
  something-tests:
    <<: *proxynetwork
    <<: *internaldns
    <<: *someservice
```

Individual instances of the service may require additional configuration to override or extend the base configuration.
This can be appended as needed.

```yaml
services:
  something-tests:
    <<: *proxynetwork
    <<: *internaldns
    <<: *someservice
    environment:
      - SOME_VAR='this section completely overrides the environment array from the base configuration'
```

Note that arrays do not merge as they do with `docker-compose.override.yml`. Instead, the entire key is replaced.

## Service Recipes

These recipes can be used for some commonly used services that we do not include by default.

### Redis

```yaml
x-redis: &redis
  image: redis:alpine

services:
  # ...
  redis:
    <<: *redis
    <<: *proxynetwork
  redis-tests:
    <<: *redis
    <<: *proxynetwork
```

Be sure to add links from the `php-fpm` and `php-tests` containers to the `redis` and `redis-tests` containers,
respectively. Chances are high that if you are using Redis, you can remove Memcached.

```yaml
services:
  # ...
  php-fpm:
    # ...
    links:
      - redis
  php-tests:
    # ...
    links:
      - redis-tests:redis
```

### Elasticsearch

```yaml
x-elasticsearch: &elasticsearch
  image: docker.elastic.co/elasticsearch/elasticsearch:7.7.1
  environment:
    - cluster.name=square1
    - xpack.security.enabled=false
    - bootstrap.memory_lock=true
    - script.inline=false
    - script.stored=false
    - script.file=true
    - script.engine.mustache.inline.search=true
    - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
  ulimits:
    memlock:
      soft: -1
      hard: -1
    nofile:
      soft: 65536
      hard: 65536
  ports:
    - "9200"
  volumes:
    - ./elasticsearch/data:/usr/share/elasticsearch/data:delegated


services:
  # ...
  elasticsearch:
    <<: *elasticsearch
    <<: *proxynetwork
  elasticsearch-tests:
    <<: *elasticsearch
    <<: *proxynetwork
```

Note that the `volumes` configuration expects a directory at ` dev/docker/elasticsearch`. Create that directory
and give it a simple `.gitignore` file that looks like:

```gitignore
data
```

Be sure to add links from the `php-fpm` and `php-tests` containers to the `elasticsearch` and `elasticsearch-tests`
containers, respectively.

```yaml
services:
  # ...
  php-fpm:
    # ...
    links:
      - memcached
      - elasticsearch
  php-tests:
    # ...
    links:
      - memcached-tests:memcached
      - elasticsearch-tests:elasticsearch
```

### Localstack

```yaml
x-localstack: &localstack
  image: localstack/localstack
  networks:
    - proxy
  ports:
    - "4567-4596:4567-4596"
    - "${PORT_WEB_UI-4699}:${PORT_WEB_UI-4699}"
  environment:
    - LOCALSTACK_SERVICES=s3,sqs,sns,lambda,cloudwatch,logs
    - HOSTNAME=localstack
    - HOSTNAME_EXTERNAL=localstack
    - AWS_ACCESS_KEY_ID=squareone
    - AWS_SECRET_ACCESS_KEY=squareone
  volumes:
    - "./localstack:/tmp/localstack"

services:
  localstack:
    <<: *localstack
    <<: *proxynetwork
```

Be sure to add a link from the `php-fpm` container to the `localstack` container.

```yaml
services:
  # ...
  php-fpm:
    # ...
    links:
      - memcached
      - localstack
```

## Overriding Containers

Ideally, we can find containers on Docker Hub that we can use on our projects with just some customization in
`docker-compose.yml`. Sometimes, though, we need to override how the container is built, extending it with our
own `Dockerfile`. The most common scenario is a project that needs special extensions added to the PHP container.

To extend the SquareOne container image, create a new `Dockerfile` at `dev/docker/phpdocker/php-fpm/Dockerfile`.
This can be configured however it makes sense for the project, but typically it will look something like:

```dockerfile
FROM moderntribe/squareone-php:74-1.1.0

# Install applications/extensions required for this project
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
       tideways-php \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
```

Your service defintion for PHP will need to change in the project's `dev/docker/docker-compose.yml` to build the
new image.

```yaml
x-php: &php
  build:
    context: .
    dockerfile: phpdocker/php-fpm/Dockerfile
  image: thisproject-php:7.4-rev0
```

The `image` key is used to distinguish this from images used on other projects. The revision number should increment
every time a change is committed to the `Dockerfile` to automatically trigger a fresh build when a coworker pulls your
changes.

### Making Upstream Changes

If an update should make its way upstream to the base squareone-php image, submit a pull request on the
[SquareOne Docker Images repo](https://github.com/moderntribe/squareone-docker-images). The documentation there
will provide more details about tagging and releasing new versions. Once the new version has been tagged,
update the image for the PHP services in `dev/docker/docker-compose.yml` to point to the new tag. 

## Local Configuration Overrides

The services defined in `dev/docker/docker-compose.yml` can be extended locally with a `docker-compose.override.yml`
in the same directory. This file will be ignored by git and can include any customizations necessary to adapt the
shared Docker configuration to the eccentricities of your local environment.

For example, if you wanted to use NFS mounts instead of osxfs to take advantage to the performance improvements,
you could manage that configuration in your override file.

```yaml
version: '3.4'

volumes:
  squareonenfs:
    driver: local
    driver_opts:
      type: nfs
      o: addr=host.docker.internal,rw,nolock,hard,nointr,nfsvers=3
      device: ":/path/to/your/project"

services:
  php-fpm:
    volumes:
      - squareonenfs:/application/www
  php-tests:
    volumes:
      - squareonenfs:/application/www
```
