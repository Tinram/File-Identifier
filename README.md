
# File Identifier

#### Identify a file through MIME type and file signature detection.


## Purpose

Detect a file's type through MIME type information and file signature (header) bytes.

(File extensions are ignored.)


## Uses

*nix systems have the `file` command, which is a powerful file identifier, and callable from PHP's shell functions.

Nevertheless, this command is not always available.

The main uses of **File Identifier** are:

+ For \*nix servers where PHP shell functions are disabled in *php.ini* ( `disable_functions=` )
+ PHP running on Windows, where no `file` command natively exists
+ When older versions of `file` (e.g. v. 5.09) are installed on the \*nix server, and which do not recognise some files such as *.gpg*
+ Where custom, old, or rare file types cannot be identified by the `file` command.


## Example Usage

```php
    require('FileIdentifier.php');
    require('FileSignatures.php');

    use Tinram\FileIdentifier\FileIdentifier;

    $f = new FileIdentifier('mira.png');
    $r = $f->getResult();
    echo $r['mimeinfo'] . PHP_EOL . $r['fileinfo'];
```


## File Signatures

The file signature data (*FileSignatures.php*) contain a limited range of common file type signatures in hexadecimal bytes.

This data array can be easily extended with additional and custom file signatures.


## License

File Identifier is released under the [GPL v.3](https://www.gnu.org/licenses/gpl-3.0.html).
