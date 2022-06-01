<?php

$file2 = 'dgp_iso.txt';
$txt = '';
for ($r=1;$r <= 9;$r++)
	{
	$file1 = 'dgp_iso_0'.$r.'.txt';
	$txt .= file_get_contents($file1).chr(13);
	}
file_put_contents($file2,utf8_decode($txt));
echo "OK";