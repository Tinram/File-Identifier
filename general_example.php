<?php

/**
    * General example usage of FileIdentifier class.
    * Martin Latter, 15/06/16
*/

###################################################
require('classes/file_identifier.class.php');
require('classes/file_signatures.class.php');
###################################################


use CopySense\FileIdentifier\FileIdentifier;


$oFileCheck = new FileIdentifier('file_examples/mira.png');
$aResult = $oFileCheck->getResult();


echo $aResult['mimeinfo'] . '<br>';
echo $aResult['fileinfo'];
