<?php
header("Access-Control-Allow-Origin: http://83.212.86.157");  //change the header

//options start
$debug='on';    // if it works, change this value to 'off', so that you don't recieve debug messages
//options end

$ferror=0;
$raw='Raw Open Data Certificate';
$pilot='Pilot Open Data Certificate';
$standard='Standard Open Data Certificate';
$expert='Expert Open Data Certificate';

if($debug=='on') echo '<p style="color:red;">php file loaded succesfully</p>';

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
   if($success=="true" || $ferror==1) break;
   $ar = 'url' . $i;
   if($_GET[$ar]!='') {  
       $entries=0;
       $tmp = file_get_contents($_GET[$ar]);
       if ($tmp=='') { if($debug=='on') echo '<p style="color:red;">check url path: ' . $_GET[$ar] . '</p>'; $ferror=1; break;}
           else if($debug=='on') echo '<p style="color:red;">Searching certificates in ' . $_GET[$ar];
       $res = explode("\n", $tmp);
       if($debug=='on') echo '(' . (count($res)-1) . '_entries)';   
          for($j=0;$j<count($res);$j++) {
              if($debug=='on') $entries++;
              $res2 = explode(",", $res[$j]);
              $res3 = checkline($res2);      
              if($res3!="false") {
                   if($debug=='on') echo ' connection found at line #' . $j . '</p>';
                   $bgurl = file_get_contents('https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.js');
                   if ($bgurl=='') {if($debug=='on') echo '<p style="color:red;">dataset #' . $res3 . ' does not exist(check your csv file) or Open Data Server is down</p>'; $ferror=1; break;}
                   if(file_get_contents('https://certificates.theodi.org/en/datasets' . $res3 . '/certificate')!='') echo '<p style="text-align:center"><a href="https://certificates.theodi.org/en/datasets' . $res3 . '/certificate" target="_blank"><img src="' . 'https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.png"></a>';
                   else {if($debug=='on') echo '<p style="color:red;">could not load image file: https://certificates.theodi.org/en/datasets' . $res3 . '/certificate/badge.png, propably server error, because the certificate exists</p>'; $ferror=1; break;}
                   $res4=$res3;
                   $tmp2 = $bgurl;                  
                   $success="true";
                   break;
               }
       }   
   }else break;
}

if($success=="false" || $ferror==1) {echo '<p></p>'; if($debug=='on') echo '<p style="color:red;">No certificate connection was found</p>';}
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
                                          }else {  echo $expert; }
                       }else {  echo $standard; }
                 }else {  echo $pilot; }
              }else {  echo $raw; }
              echo '</a></p>';
}
if($debug=='on') echo '<p style="color:red;">Debug is ON, if you don\'t want to see this text please change the value of the $debug variable to OFF.</p>';
?>