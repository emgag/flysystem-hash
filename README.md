# Flysystem Plugin for generating hash value of file

![build](https://github.com/emgag/flysystem-hash/workflows/build/badge.svg)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/emgag/flysystem-hash.svg)](https://packagist.org/packages/emgag/flysystem-hash)

A plugin for the [Flysystem](https://github.com/thephpleague/flysystem) file
system abstraction library to generate hash values of a file. See [hash_algos()](http://php.net/manual/en/function.hash-algos.php) function for supported hash algorithms. 

Tested with [Local](http://flysystem.thephpleague.com/adapter/local/) and [WebDav](http://flysystem.thephpleague.com/adapter/webdav/) adapters, but as the hash is built directly from the stream resource it should work for all other adapters as well.  

## Installation

```bash
composer require emgag/flysystem-hash
```

## Usage

```php
use Emgag\Flysystem\Hash\HashPlugin;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem; 

$fs = new Filesystem(new Local(__DIR__));
$fs->addPlugin(new HashPlugin);

// sha256 is default
$sha256 = $fs->hash('file.txt');
$sha256 = $fs->hash('file.txt', 'sha256');
$md5    = $fs->hash('file.txt', 'md5');
$sha1   = $fs->hash('file.txt', 'sha1');
```

## License

flysystem-hash is licensed under the [MIT License](http://opensource.org/licenses/MIT).
                                                                           
