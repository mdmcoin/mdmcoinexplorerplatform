# MDMCOIN Explorer Platform

[MDMCOIN Explorer Platform]  MDMCOIN Explorer Platform blockchain explorer based on PHP and SQLite.

## Requeriment
- composer install (Ubuntu: curl -sS https://getcomposer.org/installer -o composer-setup.php | HASH=`curl -sS https://composer.github.io/installer.sig` | php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" | sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer)
- php-sqlite3 (Ubuntu:  sudo apt-get install php5-sqlite3)

## Basic usage
- first run (fast, minimal indexes): `php w8_updater.php`
- indexer (after first run): `php w8_updater.php indexer`
- run in background: `php w8_updater.php`
- rollback (if needed): `php w8_updater.php rollback <block_number>`

Then route all to `index.php` except `static` folder.
