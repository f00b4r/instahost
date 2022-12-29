.PHONY: dev
dev:
	DEBUG=1 php -S 0.0.0.0:8000 index.php

.PHONY: prod
prod:
	php -S 0.0.0.0:8000 index.php
