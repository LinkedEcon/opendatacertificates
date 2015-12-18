<?php
header("Access-Control-Allow-Origin: http://83.212.86.157");

$raw='Raw Open Data Certificate';
$pilot='Pilot Open Data Certificate';
$standard='Standard Open Data Certificate';
$expert='Expert Open Data Certificate';

function checkline($line) {
$tot=0;
if($line[0]=='true') $tot++;
if($line[1]=='true') $tot++;
if(substr($line[2],- 1 - strlen($_GET['ur'])) == '/' . $_GET['ur']) $tot++;
if($tot==3) {
   $tot2=0; 
   for($n=0;$n<200;$n++) {    
        if(substr($line[3],$n,1)=='/') {       
            $tot2++;
            if($tot2==5) $f=$n;
              else if($tot2==6) { $f2=$n; break;}
              }
           }
        return substr($line[3],$f,$f2-$f);
  }else return "false";
}

$success="false";

for($i=1;$i<200;$i++) { 
   $ar = 'url' . $i;
   if($_GET[$ar]!='') {   
       $tmp = file_get_contents($_GET[$ar]);
       $res = explode("\n", $tmp);   
          for($j=0;$j<count($res);$j++) {
              $res2 = explode(",", $res[$j]);
              $res3 = checkline($res2);      
              if($res3!="false") {
                   $bgurl = file_get_contents('https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.js');
                   echo '<p style="text-align:center"><a href="https://certificates.theodi.org/en/datasets' . $res3 . '/certificate" target="_blank"><img src="' . 'https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.png"></a>';
                   $res4=$res3;
                   $tmp2 = file_get_contents('https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.js');                  
                   $success="true";
                   break;
               }
       }   
   }else break;
}

if($success=="false") echo '<p></p>';
   else {    
              echo '<br><a href="https://certificates.theodi.org/en/datasets' . $res4 . '/certificate" target="_blank">';    
              $pos = strpos($tmp2, $raw);
              if($pos===false) {
                 $pos = strpos($tmp2, $pilot);
                 if($pos===false)  {
                       $pos = strpos($tmp2, $standard);
                       if($pos===false)  {
                               $pos = strpos($tmp2, $expert);
                               if($pos===false)  {
                                       echo 'No Level Certificate'; 
                                          }else {  echo substr($tmp2,$pos,strlen($expert)); }
                       }else {  echo substr($tmp2,$pos,strlen($standard)); }
                 }else {  echo substr($tmp2,$pos,strlen($pilot)); }
              }else {  echo substr($tmp2,$pos,strlen($raw)); }
              echo '</a></p>';
}

?>