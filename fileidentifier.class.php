<?php


namespace CopySense\FileIdentifier;

use RuntimeException;


final class FileIdentifier
{
    /**
        * Class to identify a file through MIME type and file signature bytes.
        *
        * Coded to PHP 5.4+
        *
        * Example usage:
        *                require('fileidentifier.class.php');
        *                require('filesignatures.class.php');
        *                use CopySense\FileIdentifier\FileIdentifier;
        *                $f = new FileIdentifier('x.png');
        *                $r = $f->getResult();
        *                echo $r['mimeinfo'] . PHP_EOL . $r['fileinfo'];
        *
        * @author        Martin Latter <copysense.co.uk>
        * @copyright     Martin Latter 04/05/2016
        * @version       0.21
        * @license       GNU GPL v3.0
        * @link          https://github.com/Tinram/File-Identifier.git
        * @throws        RuntimeException
    */


    /** @var integer $iFileBytesToRead number of header file bytes to read */
    private $iFileBytesToRead = 12;

    /* @var array results holder with null defaults - provides output separation of file MIME info and byte info */
    private $aResults = ['mimeinfo' => null, 'fileinfo' => null];

    /* @var string message */
    private $sMimeTypeInfo = 'File MIME type: ';

    /* @var string message */
    private $sNoMimeInfo = 'No MIME type information.';

    /* @var string message */
    private $sFileMatch = 'File match found: ';

    /* @var string message */
    private $sNoFileMatch = 'No file match found.';

    /* @var string */
    private $sBytes = '';

    /* @var string */
    private $sClassName = '';

    /* @var string */
    private $sMethodName = '';

    /* @var string */
    private $aArrayName = '';

    /* @var string */
    private $sFileName = '';


    public function __construct($sFile = NULL, $sClassName = 'FileSignatures', $sMethodName = 'getSignature', $sArrayName = 'aSignatures')
    {
        $this->sClassName = $sClassName;
        $this->sMethodName = $sMethodName;
        $this->sArrayName = $sArrayName;
        $this->sFileName = $sFile;
        $this->loadFile();
    }


    public function __destruct() {}


    /**
        * Get file analysis results.
        *
        * @return   string, message
    */

    public function getResult()
    {
        return $this->aResults;
    }


    /**
        * Attempt to load the file to be parsed, throw exception on access problem.
    */

    private function loadFile()
    {
        try
        {
            if ( ! file_exists($this->sFileName))
            {
                throw new RuntimeException($this->sFileName . ' could not be found.');
            }
            else if ( ! is_file($this->sFileName))
            {
                throw new RuntimeException($this->sFileName . ' is not a normal file.');
            }
            else if (filesize($this->sFileName) < 4)
            {
                throw new RuntimeException('The size of the file: ' . $this->sFileName . ' is too small to analyze.');
            }
        }
        catch (RuntimeException $e)
        {
            self::reportException($e);
            return;
        }

        $rHandle = fopen($this->sFileName, 'r');
        $this->sBytes = fread($rHandle, $this->iFileBytesToRead);
        fclose($rHandle);

        $this->aResults = $this->process($this->sClassName);

    } # end loadFile()


    /**
        * Process the file - search file signature bytes, and if PHP's file module is active, MIME types.
        *
        * @return   associative array, MIME and byte information
    */

    private function process()
    {
        $sMimeInfo = null;
        $sFileByteInfo = null;
        $aByteSeq = [];
        $aSignatures = [];
        $aParseResults = [];

        /* validate the calls from the helper class */

        try
        {
            if ( ! method_exists($this->sClassName, $this->sMethodName))
            {
                throw new RuntimeException($this->sMethodName . '() method does not exist in the class ' . $this->sClassName . ' passed to ' . __METHOD__ . '()');
            }
            else if ( ! property_exists($this->sClassName, $this->sArrayName))
            {
                throw new RuntimeException($this->sArrayName . ' property does not exist in the class ' . $this->sClassName . ' passed to ' . __METHOD__ . '()');
            }
            else
            {
                $sMN = $this->sMethodName;

                $aSignatures = call_user_func( [ $this->sClassName, $sMN ] );
                    /* PHP 7 can resolve the following, PHP 5 cannot: $aSignatures = $this->sClassName::$sMN(); */

                if ( ! is_array($aSignatures))
                {
                    throw new RuntimeException('The file signature data is not an array.');
                }
            }
        }
        catch (RuntimeException $e)
        {
            self::reportException($e);
        }


        /* use PHP file MIME analysis if 'php_fileinfo' module available and enabled */
        if (function_exists('finfo_file'))
        {
            $rFileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $sMimeInfo .= $this->sMimeTypeInfo . finfo_file($rFileInfo, $this->sFileName);
            finfo_close($rFileInfo);
        }

        /* create byte sequence from read bytes to conform to signature format */
        for ($i = 0, $iLen = strlen($this->sBytes); $i < $iLen; $i++)
        {
            $aByteSeq[] = bin2hex($this->sBytes[$i]);
        }

        $sB = join(' ', $aByteSeq);


        /* iterate over file signatures */
        foreach ($aSignatures as $sName => $sSig)
        {
            if (stripos($sB, $sSig) !== false)
            {
                $sFileByteInfo = $sName;
                break;
            }
        }

        if ( ! is_null($sMimeInfo))
        {
            $aParseResults['mimeinfo'] = $sMimeInfo;
        }
        else
        {
            $aParseResults['mimeinfo'] = $this->sNoMimeInfo;
        }

        if ( ! is_null($sFileByteInfo))
        {
            $aParseResults['fileinfo'] = $this->sFileMatch . $sFileByteInfo;
        }
        else
        {
            $aParseResults['fileinfo'] = $this->sNoFileMatch;
        }

        return $aParseResults;

    } # end fileProcess()


    /**
        * Report exceptions generated in this class.
        *
        * @param    RuntimeException $e
    */

    private static function reportException(RuntimeException $e)
    {
        echo $e->getMessage() . PHP_EOL . '(' . $e->getfile() . ', line ' . $e->getline() . ')' . PHP_EOL;
    }

} # end {}
