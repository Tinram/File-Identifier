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


$oFileCheck = new FileIdentifier('ascii.txt.gpg');
$aResult = $oFileCheck->getResult();


echo $aResult['mimeinfo'] . '<br>';
echo $aResult['fileinfo'];

?>