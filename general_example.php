<?php

/**
    * General example usage of FileIdentifier class.
    * Martin Latter, 15/06/2016
*/

declare(strict_types=1);

###################################################
require('classes/file_identifier.class.php');
require('classes/file_signatures.class.php');
###################################################

use Tinram\FileIdentifier\FileIdentifier;

$oFileCheck = new FileIdentifier('file_examples/mira.png');
$aResult = $oFileCheck->getResult();

echo $aResult['mimeinfo'] . PHP_EOL;
echo $aResult['fileinfo'] . PHP_EOL;
