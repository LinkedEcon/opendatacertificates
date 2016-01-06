# opendatacertificates
Automation for Open Data Certificates (https://certificates.theodi.org/en/). 

v1.0
    
This module provides a simple connection to CKAN with the ODI Certificates using the .csv files produced from your campaigns pages(e.g. https://certificates.theodi.org/campaigns/32).

Steps:

1.	Place the getcert.php file to your server

2.	Place the csv file(s) of your campaigns in the same folder as the getcert.php

3.	Place the following code into the file "license.html" (ckan/templates/snippets/license.html). You may choose a different place, "organization.html" is also a good pick.
     

```
 <a href="http://certificates.theodi.org" title="{{ _('ODI Certificates') }}">          
                       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
                       <div id="certs"> Loading... </div>                        
                       <script>                       
                       function glast(){ a = window.location.href; c=0; for(i=1;i<a.length;i++) { if(a.substring(a.length-i-1,a.length-i)=='/')  { c = a.substring(a.length-i,a.length); break; } } return c; } 
                       var ur="<link to the getcert.php file here>?ur=" + glast() + "&url1=<link to your certificates csv file here>&url2=<link to your certificates csv file here>";
                       $.get(ur, null,function(data) { $("#certs").html(data); });                    
                       </script>
        </a> 

```
Where: 

```<link to the getcert.php file here>```: is the link to getcert.php file on your server. If necessary change its header by adding this line: header("Access-Control-Allow-Origin: http://ckan.linkedeconomy.org"); where instead of http://ckan.linkedeconomy.org use your own server header.

```<link to your certificates csv file here>```: is the link to the certificates csv file. If you have only one, just use only the url1 attribute. If you have two or more csv’s add them as &url2=…..   &url3=…….  etc. 
<br><br> 4.   The connection should be working now. If it’s not working you may consider to be advised from my files which can be found in the example folder.
<br><br> 5.   Options in getcert.php file:
```
//options start
$debug='off';     // on/off, if it works, change this value to 'off', so that you don't recieve debug messages
$method=-1;     // -1/0/1/2/3,  choose a method to read the js file containing the certificate info, 
               //            VALUES:  -1:test all methods,0:cURL,1:fopen,2:file_get_contents,3:http_get()
//options end
```

You should turn the debug off inside the getcert.php file so you won't see anymore the debug info on your page. You may change the method to the one that works, so it could be more responsive. I've implemented different methods because of the different server/php configurations.
