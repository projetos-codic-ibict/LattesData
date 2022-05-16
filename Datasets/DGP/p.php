<?php
$file = 'dgp_iso.txt';
$file = 'dgp_iso_amostra.txt';
$txt = file_get_contents($file);
file_put_contents($file.'_uft8d'.'.txt',utf8_decode($txt));
file_put_contents($file.'_uft8e'.'.txt',utf8_encode($txt));

echo "OK";