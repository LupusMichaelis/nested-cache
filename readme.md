# Nested Object Cache for Wordpress

Providing a universal cache system with monitoring, resource awareness and much more.

## Installation

As for now, this project is under development. Installation steps will be considered
later.

## Developer onboarding

This project's using Docker and Composer to manage its workflow. Scripts are provided to
help you in running tests, building the release, etc.

First you have to initialize the project. This will create a `.env` file at the root of
the project, in order to provide the Docker images with the data they require to not mess
with your filesystem:


```
git clone https://github.com/LupusMichaelis/wp-nested-cache.git
cd wp-nested-cache
./bin/build init

```

To build the `PHAR` file:

```
./bin/build release
```

For development workflow, you first need to build the development image, then access to
composer, lauch tests, build documentation, or all of it at the same time:

```
./bin/build dev
./bin/session composer
./bin/session tests
./bin/session doc
./bin/session dev
```
