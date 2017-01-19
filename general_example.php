<?php

/**
    * General example usage of FileIdentifier class.
    * Martin Latter, 15/06/16
*/

###################################################
require('classes/fileidentifier.class.php');
require('classes/filesignatures.class.php');
###################################################

use CopySense\FileIdentifier\FileIdentifier;


$oFileCheck = new FileIdentifier('file_examples/mira.png');
$aResult = $oFileCheck->getResult();


echo $aResult['mimeinfo'] . '<br>';
echo $aResult['fileinfo'];
