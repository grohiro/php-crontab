<?php
namespace Grohiro\Crontab;
require __DIR__.'/../vendor/autoload.php';

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
