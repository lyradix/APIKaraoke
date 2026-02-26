# pre-requirements

composer require orm
composer require symfony/security-bundle
composer require symfony/serializer
composer require symfony/property-access
composer require symfony/property-info
composer require nelmio/cors-bundle
composer require endroid/qr-code
composer require --dev symfony/maker-bundle

# launch the app 
symfony server:stop
symfony serve --no-tls --port=8000
php -S 0.0.0.0:8000 -t public

check if LAN accessible
netstat -ano | findstr :8000


