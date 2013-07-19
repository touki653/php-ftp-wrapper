# Common usage

**Note:** All along the file we are assuming:

 * `$ftp` is an instance of `Touki\FTP\FTP`
 * `Directory` is an alias of `Touki\FTP\Model\Directory`
 * `File` is an alias of `Touki\FTP\Model\File`

## Find filesystem existance

```php
<?php

$ftp->fileExists(new File('/foo'));
$ftp->fileExists(new File('/non/existant/file'))
$ftp->directoryExists(new Directory('/folder'))
$ftp->directoryExists(new Directory('/bar'))

?>
```

## Fetching all filesystems

```php
<?php

$list = $ftp->findFilesystems(new Directory("/"));
var_dump($list);

?>
```

Will output:

```
array(3) {
  [0] => object (Touki\FTP\Model\File) {
    protected $realpath => string(10) "/file1.txt"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(4)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(1) "5"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-15 09:17:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
  [1] => object (Touki\FTP\Model\File) {
    protected $realpath => string(10) "/file2.txt"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(6)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(4)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(1) "5"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-15 09:11:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
  [2] => object (Touki\FTP\Model\Directory) {
    protected $realpath => string(7) "/folder"
    protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(7)
    }
    protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(7)
    }
    protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
      protected $flags => int(5)
    }
    protected $owner => string(9) "ftp-tests"
    protected $group => string(9) "guillaume"
    protected $size => string(4) "4096"
    protected $mtime => object (DateTime) {
      public $date => string(19) "2013-07-17 13:18:00"
      public $timezone_type => int(3)
      public $timezone => string(13) "Europe/Berlin"
    }
  }
}

?>
```

## Fetching a single file

```php
<?php

$file  = $ftp->findFileByName('file1.txt');
$file2 = $ftp->findFileByName('nonexistant');
$file3 = $ftp->findFileByName('folder/file3.txt');

var_dump($file);
var_dump($file2);
var_dump($file3);

?>
```

Will output

```
object (Touki\FTP\Model\File) {
  protected $realpath => string(10) "/file1.txt"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(4)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(1) "5"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-15 09:17:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}

NULL

object (Touki\FTP\Model\File) {
  protected $realpath => string(17) "/folder/file3.txt"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(6)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(4)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(1) "5"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-15 09:11:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}
```

## Fetching a directory

```php
<?php

$dir = $ftp->findDirectoryByName('/folder');
var_dump($dir);

?>
```

Will output

```
object (Touki\FTP\Model\Directory) {
  protected $realpath => string(7) "/folder"
  protected $ownerPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(7)
  }
  protected $groupPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(7)
  }
  protected $guestPermissions => object (Touki\FTP\Model\Permissions) {
    protected $flags => int(5)
  }
  protected $owner => string(9) "ftp-tests"
  protected $group => string(9) "guillaume"
  protected $size => string(4) "4096"
  protected $mtime => object (DateTime) {
    public $date => string(19) "2013-07-17 13:18:00"
    public $timezone_type => int(3)
    public $timezone => string(13) "Europe/Berlin"
  }
}
```

## Downloading a file

```php
<?php

$file = $ftp->findFileByName('file1.txt');

if (null === $file) {
    return;
}

// To a file
$ftp->download('/path/to/download/file1.txt', $file);

// To an handle
$handle = fopen('/path/to/download/file1.txt', 'w+');
$ftp->download($handle, $file);

?>
```

You can also specify options passed to it

```
<?php

$options = array(
    FTP::NON_BLOCKING  => false,     // Whether to deal with a callback while downloading
    FTP::NON_BLOCKING_CALLBACK => function() { }, // Callback to execute
    FTP::START_POS     => 0,         // File pointer to start downloading from
    FTP::TRANSFER_MODE => FTP_BINARY // Transfer Mode 
);

$ftp->download('/path/to/download/file1.txt', $file, $options);

?>
```

## Uploading a file

```php
<?php

// From a file
$ftp->upload(new File('newfile.txt'), '/path/to/upload/file1.txt');

// To an handle
$handle = fopen('/path/to/upload/file1.txt', 'w+');
$ftp->upload(new File('newfile.txt'), $handle);

?>
```

You can also specify options passed to it

```
<?php

$options = array(
    FTP::NON_BLOCKING  => false,     // Whether to deal with a callback while uploading
    FTP::NON_BLOCKING_CALLBACK => function() { }, // Callback to execute
    FTP::START_POS     => 0,         // File pointer to start uploading from
    FTP::TRANSFER_MODE => FTP_BINARY // Transfer Mode
);

$ftp->download('/path/to/upload/file1.txt', $file, $options);

?>
```