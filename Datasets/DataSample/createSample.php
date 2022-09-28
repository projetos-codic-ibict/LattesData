<?php

$vars = array('id','name','class','mark','age','gender');
$records = array(
    array(1, 'John', 'C', 80, 15, 'M'),
    array(2, 'Mary', 'B', 90, 17, 'F'),
    array(3, 'Peter', 'A', 70, 22, 'M'),
    array(4, 'Jane', 'C', 85, 18, 'F'),
    array(5, 'Mark', 'A', 75, 17, 'M'),
    array(6, 'Susan', 'B', 95,18,  'F'),
    array(7, 'Bob', 'D', 65, 21,'M'),
    array(8, 'Alice', 'A', 100, 18, 'F'),
    array(9, 'Bill', 'B', 60, 17, 'M'),
    array(10, 'Sarah', 'B', 95, 16, 'F')
);
$sx = '';
for($r=0;$r < count($vars);$r++)
    {
        if ($r > 0) { $sx .= "\t"; }
        $sx .= $vars[$r];
    }
$sx .= chr(10);

foreach($records as $record)
    {
        for($r=0;$r < count($record);$r++)
        {
            if ($r > 0) {
            $sx .= "\t"; }
            $sx .= $record[$r];
        }
        $sx .= chr(10);
    }
file_put_contents('sample.tab', $sx);