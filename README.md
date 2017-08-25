# php-crontab

php-crontab manipulates crontab entry via PHP. You can use this library To add and update save crontab entries.

## Install

### Composer
composer.json

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/grohiro/php-crontab"
    }
  ],
  "require": {
    "grohiro/php-crontab": "master"
  }
}
```

## Usage

```php
use Grohiro\Crontab\Crontab;

$crontab = new Crontab();
// Read crontab
$crontab->init(Crontab::l());

// Add entries
$crontab->addNewEntry('ID1', '0 4 * * * ls -l /var/log');
$crontab->addNewEntry('ID2', '0 5 * * * ls -l /var/spool/mail');

// Change schedule
$crontab->updateEntry('ID1', '30 4 * * * ls -l /var/log');

// Export crontab
$crontab->save();
```

crontab

```
30 4 * * * ls -l /var/log #phpcrontab:ID1
0 5 * * * ls -l /var/spool/mail #phpcrontab:ID2
```