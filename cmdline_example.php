#!/usr/bin/env php
<?php

/**
    * Command-line example usage of FileIdentifier class.
    *
    * Usage:
    *        php <thisfilename> <filename>
    *        ./<thisfilename> <filename>
    *
    * @author        Martin Latter <copysense.co.uk>
    * @copyright     Martin Latter 15/06/2016
    * @version       0.13
    * @license       GNU GPL v3.0
    * @link          https://github.com/Tinram/File-Identifier.git
*/


###################################################
require('classes/fileidentifier.class.php');
require('classes/filesignatures.class.php');
###################################################


use CopySense\FileIdentifier\FileIdentifier;


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
