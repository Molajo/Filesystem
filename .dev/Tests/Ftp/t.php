<?php

require_once __DIR__ . '/ftp.class.php';


try {
    $ftp = new Ftp;

    // Opens an FTP connection to the specified host
    $ftp->connect('localhost');

    // Login with username and password
    $ftp->login('amystephen', 'madsam');

    echo '<pre>';
       var_dump($ftp);
    die;
    // Download file 'README' to local temporary file
    $temp = tmpfile();
    $ftp->fget($temp, 'todo.txt', Ftp::ASCII);

    // echo file
    echo '<pre>';
    fseek($temp, 0);
    fpassthru($temp);

} catch (FtpException $e) {
    echo 'Error: ', $e->getMessage();
}
