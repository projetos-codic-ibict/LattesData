<?php
function hexdump($string)
    {
        $sx = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $sx .= str_pad(dechex(ord($string[$i])), 2, '0', STR_PAD_LEFT);
            $sx .= ' ';
        }        
        return $sx;
    }
function romano($n)
{
    $n = sonumero($n);
    $r = '';
    $u = array('','I','II','III','IV','V','VI','VII','VII','IX');
    $d = array('','X','XX','XXX','XL','L','LX','LXX','LXXX','XC');
    $c = array('','C','CC','CCC','CD','D,','DC','DCC','DCCC','CM');
    $m = array('','M','MM','MMM');
    
    if ($n < 3000)
    {
        $v1 = round(substr($n,strlen($n)-1,1));
        $r .= $u[$v1];
        $n = substr($n,0,strlen($n)-1);
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $d[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $c[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }                
        if (strlen($n) > 0)
        {
            $v1 = round(substr($n,strlen($n)-1,1));
            $r = $m[$v1].$r;
            $n = substr($n,0,strlen($n)-1);                        
        }
    } else {
        $r = 'ERRO '.$n;
    }
    return($r);               
}

function brtos($dt)
    {
        $dt = sonumero($dt);
        $dt = substr($dt,4,4).substr($dt,2,2).substr($dt,0,2);
        return $dt;
    }

function df($N,$pre='',$pos='')
    {
        if (defined($N) == true)
            {
                $var = constant($N);
                $var = $pre.$var.$pos;
                return $var;
            }
        return '';
    }