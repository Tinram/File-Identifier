<?php

declare(strict_types=1);

final class FileSignatures
{
    /**
        * Static class to hold an array of file signature hex bytes.
        *
        * @author        Martin Latter
        * @copyright     Martin Latter 04/05/2016
        * @version       0.16
        * @license       GNU GPL version 3.0 (GPL v3); http://www.gnu.org/licenses/gpl.html
        * @link          https://github.com/Tinram/File-Identifier.git
    */

    /** @var array<string> $aSignatures */
    private static $aSignatures =
    [
        /**
             * Example file signatures (only common files, OS X binaries omitted).
             * Main sources:
             *               https://en.wikipedia.org/wiki/List_of_file_signatures
             *               http://asecuritysite.com/forensics/magic
        */

        '7-Zip' => '37 7a bc af 27 1c',
        'ARW (Sony RAW)' => '49 49 2a 00 08', /* one type of Sony RAW */
        'AVI / WAV' => '52 49 46 46',
        'shell script' => '23 21',
        'BMP' => '42 4d',
        'bzip2' => '42 5a 68',
        'CAB' => '4d 53 43 46',
        'compressed (LZW)' => '1f 9d',
        'compressed (LZH)' => '1f a0',
        'compressed (LZMA)' => '5d 00 00 80',
        'cpio' => 'c7 71 14 00 fc',
        'CR2 (Canon RAW)' => '49 49 2a 00 10 00 00 00 43 52',
        'dat' => '50 4d 4f 43 43 4d 4f 43',
        'dex (Dalvic)' => '64 65 78 0a 30 33 35 00',
        'epub' => '50 4B 03 04 0A 00 02 00',
        'EXE (MZ: DOS / Windows)' => '4d 5a',
        'EXE (ELF x32: *nix)' => '7f 45 4c 46 01',
        'EXE (ELF x64: *nix)' => '7f 45 4c 46 02',
        'FLAC' => '66 4c 61 43',
        'FLV' => '46 4c 56',
        'GIF87a' => '47 49 46 38 37 61',
        'GIF89a' => '47 49 46 38 39 61',
        'GZip' => '1f 8b',
        'ICO' => '00 00 01 00',
        'ISO (CD)' => '43 44 30 30 31',
        'JAR' => '50 4b 03 04 14 00 08 00 08 00',
        'JPG' => 'ff d8 ff db',
        'JPG (JFIF)' => 'ff d8 ff e0',
        'JPG (EXIF)' => 'ff d8 ff e1',
        'JPG 2000' => '00 00 00 0c 6a 50 20 20 0d 0a 87 0a',
        'midi' => '4d 54 68 64',
        'MP3' => 'ff fb',
        'MP3 (ID3)' => '49 44 33',
        'MP4' => '00 00 00 18 66 74 79 70',
        'MSI (MS installer)' => 'd0 cf 11 e0 a1 b1 1a e1',
        'MS Office (legacy)' => 'd0 cf 11 e0 a1 b1 1a e1', /* same as .msi, confirmed */
        'MS Office (2010+)' => '50 4b 03 04',
        'obj' => '4c 01',
        'OGG' => '4F 67 67 53',
        'PDF' => '25 50 44 46',
        'PHP' => '3c 3f 70 68 70',
        'PNG' => '89 50 4e 47 0d 0a 1a 0a',
        'PostScript' => '25 21 50 53',
        'PSD' => '38 42 50 53',
        'PSP (PaintShop)' => '50 61 69 6e 74 20 53 68 6f 70 20 50',
        'PST' => '21 42 44 4E 42',
        'RAR (old)' => '52 61 72 21 1a 07 00',
        'RAR (v.5+)' => '52 61 72 21 1a 07 01 00',
        'RPM' => 'ed ab ee db',
        'RTF' => '7b 5c 72 74 66 31',
        'SQLite 3' => '53 51 4c 69 74 65 20 66 6f 72 6d 61 74 20 33 00',
        'SWF' => '43 57 53',
        'TAR' => '75 73 74 61 72 00 30 30',
        'TAR2' => '75 73 74 61 72 20 20 00',
        'tcpdump (v.2)' => 'd4 c3 b2 a1 02',
        'TGA' => '01 00 0a 00 00 00 00 18',
        'TIFF (LE)' => '49 49 2a 00',
        'TIFF (BE) / NEF (Nikon)' => '4d 4d 00 2a',
        'TTF' => '00 01 00 00 00',
        'Unicode BOM' => 'ef bb bf',
        'WAR (Web Archive)' => '50 4b 03 04 14',
        'WebP image' => '52 49 46 46 08 8c 00 00 57 45 42 50',
        'WMA / WMV' => '30 26 b2 75 8e 66 cf',
        'WMF' => 'd7 cd c6 9a',
        'XCF (GIMP)' => '67 69 6d 70 20 78 63 66 20',
        'XPM' => '2f 2a 20 58 50 4d',
        'XZ' => 'fd 37 7a 58 5a',
        'ZIP' => '50 4b 03 04',
        'ZIP (empty)' => '50 4b 05 06',
        'ZIP (spanned)' => '50 4b 07 08',

        /* GPG and file ciphers, source: my analysis */
        'GnuPG (IDEA)' => '8c 0d 04 01',
        'GnuPG (3DES)' => '8c 0d 04 02',
        'GnuPG (CAST5)' => '8c 0d 04 03',
        'GnuPG (Blowfish)' => '8c 0d 04 04',
        'GnuPG (AES)' => '8c 0d 04 07',
        'GnuPG (AES192)' => '8c 0d 04 08',
        'GnuPG (AES256)' => '8c 0d 04 09',
        'GnuPG (Twofish)' => '8c 0d 04 0a',
        'GnuPG (Camellia128)' => '8c 0d 04 0b',
        'GnuPG (Camellia192)' => '8c 0d 04 0c',
        'GnuPG (Camellia256)' => '8c 0d 04 0d'
    ];


    /**
        * Return class array.
        *
        * @return   array<string>, file signatures
    */

    public static function getSignature(): array
    {
        return self::$aSignatures;
    }
}
