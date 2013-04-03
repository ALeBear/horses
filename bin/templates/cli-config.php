<?php

require __DIR__ . '/vendor/autoload.php';

use horses\Kernel;

$DIContainer = Kernel::factory()->run(__DIR__, array('doctrine', 'locale'), true);

use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

$helperSet = new HelperSet(array(
    'em' => new EntityManagerHelper($DIContainer->get('entity_manager'))
));

