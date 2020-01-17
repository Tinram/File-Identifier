<?php

declare(strict_types=1);

namespace Tinram\FileIdentifier;

use RuntimeException;


final class FileIdentifier
{
    /**
        * Identify a file through MIME type and file signature bytes.
        *
        * Coded to PHP 7.2+
        *
        * @author        Martin Latter
        * @copyright     Martin Latter 04/05/2016
        * @version       0.26
        * @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
        * @link          https://github.com/Tinram/File-Identifier.git
        * @throws        RuntimeException
    */


    /** @var integer $iFileBytesToRead, number of header file bytes to read */
    private $iFileBytesToRead = 16;

    /** @var array<mixed> $aResults, results holder with null defaults - provides output separation of file MIME info and byte info */
    private $aResults = ['mimeinfo' => null, 'fileinfo' => null];

    /** @var string $sMimeTypeInfo, message */
    private $sMimeTypeInfo = 'File MIME type: ';

    /** @var string $sNoMimeInfo, message */
    private $sNoMimeInfo = 'No MIME type information.';

    /** @var string $sFileMatch, message */
    private $sFileMatch = 'File match found: ';

    /** @var string $sNoFileMatch, message */
    private $sNoFileMatch = 'No file match found.';

    /** @var mixed $sBytes */
    private $sBytes = '';

    /** @var string $sClassName */
    private $sClassName = '';

    /** @var string $sMethodName */
    private $sMethodName = '';

    /** @var string $sArrayName */
    private $sArrayName = '';

    /** @var mixed $sFileName */
    private $sFileName = '';


   /**
        * Constructor.
        *
        * @param   string|null $sFile
        * @param   string $sClassName
        * @param   string $sMethodName
        * @param   string $sArrayName
    */

    public function __construct(?string $sFile = null, string $sClassName = 'FileSignatures', string $sMethodName = 'getSignature', string $sArrayName = 'aSignatures')
    {
        $this->sClassName = $sClassName;
        $this->sMethodName = $sMethodName;
        $this->sArrayName = $sArrayName;
        $this->sFileName = $sFile;
        $this->loadFile();
    }


    /**
        * Get file analysis results.
        *
        * @return   array<string>, message
    */

    public function getResult(): array
    {
        return $this->aResults;
    }


    /**
        * Attempt to load the file to be parsed, or throw exception on access problem.
    */

    private function loadFile(): void
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

        $this->aResults = $this->process();
    }


    /**
        * Process the file - search file signature bytes, and if PHP's file module is active, MIME types.
        *
        * @return   array<string>, MIME and byte information
    */

    private function process(): array
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
                $aSignatures = $this->sClassName::$sMN();

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

        /* use PHP file MIME analysis if 'php_fileinfo' module is available and enabled */
        if (function_exists('finfo_file'))
        {
            $rFileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $sMimeInfo .= $this->sMimeTypeInfo . finfo_file($rFileInfo, $this->sFileName);
            finfo_close($rFileInfo);
        }

        /* create byte sequence from read bytes to conform to the signature format */
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
    }


    /**
        * Report exceptions generated in this class.
        *
        * @param    RuntimeException $e
    */

    private static function reportException(RuntimeException $e): void
    {
        echo $e->getMessage() . PHP_EOL . '(' . $e->getFile() . ', line ' . $e->getLine() . ')' . PHP_EOL;
    }
}
