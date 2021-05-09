# Nested Object Cache for Wordpress

Providing a universal cache system with monitoring, resource awareness and much more.

## Installation

As for now, this project is under development. Installation steps will be considered
later.

```
git clone https://github.com/LupusMichaelis/wp-nested-cache.git
cd wp-nested-cache
./bin/session init
./bin/session release
```

If using Docker isn't an option for you, you need:

* PHP 7.3 or better
* composer 1.10 or higher

And run the following commands:

```
composer install --no-dev
php bin/build-phar.php
```

The file `./build/object-cache.phar` should then be copied in the `wp-content` of your WordPress
instance, then create the `object-cache.php` file that will load everything.

```
echo '<?php include '\''object-cache.phar'\'';' > wp-content/object-cache.php
```

## Developer onboarding

This project's using Docker and Composer to manage its workflow. Scripts are provided to
help you in running tests, building the release, etc.

Those containers mounts you current working direcory into the containter at `/home/anvil`.
Files created should belong to you and not mess with your filesystem.

First you have to initialize the project. This will create a `.env` file at the root of
the project, in order to provide the Docker images with the data they require to not mess
with your filesystem. Please follow steps described in the installation section.


A typical developer workflow will look like to that:

```
# The first time, it's a good idea to install dependencies
./bin/session composer install --dev

# Lauch phpunit-watcher (with code coverage)
./bin/session composer jon

# Generates documentation on demand
./bin/session composer doc

# Gives a interactive PHP shell inside the container
./bin/session php -a
```

Please note that, if you run `jon` in a terminal, and don't quit it, subsequent calls
through `./bin/session` will be done in the same container, to the exception of `release`.

The `release` session run a separate container that will only mount the `./build`
directory to write the PHAR file in it. This is to avoid development requirements and
artefacts to be embeded into the release.
