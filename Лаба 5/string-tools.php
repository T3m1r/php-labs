<?php
declare(strict_types=1);
function stringInfo(string $value,string $needle):array {
 $position=mb_stripos($value,$needle,0,'UTF-8');
 return ['length'=>mb_strlen($value,'UTF-8'),'first'=>mb_substr($value,0,1,'UTF-8'),'contains'=>$position!==false,'position'=>$position];
}