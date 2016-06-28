<?php

/**
    * Example usage of FileIdentifier class.
    * Martin Latter, 15/06/16
*/

###################################################
require('fileidentifier.class.php');
require('filesignatures.class.php');
###################################################

use CopySense\FileIdentifier\FileIdentifier;


$oFileCheck = new FileIdentifier('mira.png');
$aResult = $oFileCheck->getResult();


echo $aResult['mimeinfo'] . '<br>';
echo $aResult['fileinfo'];

?>