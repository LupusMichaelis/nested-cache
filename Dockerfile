# syntax=docker/dockerfile-upstream:master-labs
FROM lupusmichaelis/alpine-php7-composer:1.1.3 as base

RUN <<eos
apk update
apk upgrade --no-cache

declare -ar packages=(
	php7-pecl-apcu
	php7-memcache
	php7-xdebug
)
apk add --no-cache ${packages[@]}

echo 'apc.enable_cli = 1' >> \
	/etc/php7/conf.d/apcu.ini
eos

FROM base as release

CMD [ "composer", "build-release" ]

RUN <<eos
sed -e '/^;zend/s/;//' \
	-i /etc/php7/conf.d/*xdebug.ini

echo 'xdebug.mode=coverage' >> /etc/php7/conf.d/*xdebug.ini

eos

COPY ./bin ${ANVIL}/bin
COPY \
	./composer.json \
	${ANVIL}/
COPY ./src ${ANVIL}/src

FROM base as dev
USER 0
RUN apk add --no-cache doxygen
