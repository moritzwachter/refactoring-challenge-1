install:
	@composer install --no-interaction --no-progress

lint:
	# Running PHPCS
	@php vendor/bin/phpcs --standard=PSR2 --extensions=php --encoding=UTF-8 --ignore="*vendor*" --ignore="*AppBundle/Migrations/*"  --tab-width=120 -p src/
	# Running PHPStan, temporarily deactivated because of build duration
	#@php vendor/bin/phpstan analyse -c .phpstan.neon -l 1 src/ || true
	# Linting Twig files
	@php bin/console lint:twig src/
	# Linting YAML files
	@php bin/console lint:yaml src/
