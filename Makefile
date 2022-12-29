.PHONY: dev
dev:
	DEBUG=1 php -S 0.0.0.0:8000 -t public

.PHONY: prod
prod:
	php -S 0.0.0.0:8000 -t public
