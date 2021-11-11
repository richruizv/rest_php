<?php
//this code works with sending the uid parameter
$time = time();
$secret = 'Sh!! No se lo cuentes a nadie!';

echo "time: $time".PHP_EOL."Hash ".sha1($argv[1].$time.$secret);