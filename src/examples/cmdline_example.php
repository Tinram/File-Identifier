#!/usr/bin/env php
<?php

/**
    * Command-line example usage of FileIdentifier class.
    *
    * Usage:
    *        php <thisfilename> <filename>
    *        ./<thisfilename> <filename>
    *
    * @author        Martin Latter
    * @copyright     Martin Latter 15/06/2016
    * @version       0.17
    * @license       GNU GPL v3.0
    * @link          https://github.com/Tinram/File-Identifier.git
*/

declare(strict_types=1);

###################################################
require('../FileIdentifier.php');
require('../FileSignatures.php');
###################################################

use Tinram\FileIdentifier\FileIdentifier;

if ( ! isset($_SERVER['argv'][1]))
{
    $sUsage =
        PHP_EOL . ' ' .
        basename(__FILE__, '.php') .
        PHP_EOL . PHP_EOL .
        "\tusage: php " . basename(__FILE__) . ' <filename>' .
        PHP_EOL . PHP_EOL;

    die($sUsage);
}

$sFile = $_SERVER['argv'][1];

if ( ! file_exists($sFile))
{
    die('\'' . $sFile . '\' does not exist in this directory!' . PHP_EOL);
}
else
{
    $oFileCheck = new FileIdentifier($sFile);
    $aResult = $oFileCheck->getResult();

    echo $sFile . ':' . PHP_EOL;
    echo $aResult['mimeinfo'] . PHP_EOL;
    echo $aResult['fileinfo'] . PHP_EOL;
}
