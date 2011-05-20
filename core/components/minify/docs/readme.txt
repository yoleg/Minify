--------------------
Snippet: minify
--------------------
Version: see package details
Since: December 26, 2010
Author: Oleg Pryadko (websitezen.com)
License: GNU GPLv2 (or later at your option)

This component is a css and js minifier for MODx Revolution based on http://modxcms.com/forums/index.php?topic=55983.0.

--------------------
Instructions:
--------------------
Open up the core/components/minify/functions.minify.php to edit the find and replace functions and set paths.
Make sure you add a the word min *after* the main src or href for each CSS or JS file you want to include.

--------------------
Example Javascript (remember to put "min" sometime after the URL) :
--------------------
<script src="my/script/path.js" class="min"></script>
<script src="another/script/path.js" class="min"></script>

--------------------
Example CSS (remember to put "min" sometime after the URL) :
--------------------
<link rel="stylesheet" href="my/css/path.css" type="text/css" media="screen, projection" class="min" /> 
<link rel="stylesheet" href="another/css/path.css" type="text/css" media="print" class="min" /> 
<!-- The css file below will NOT be included because it doesn't have a class of min -->
<!--[if lte IE 7]> <link rel="stylesheet" href="my/ie/css/path.css" type="text/css" media="screen, projection" /> <![endif]--> 

--------------------
Example Asyncronous Javascript (remember to put "min" sometime after the URL) :
--------------------
<script type="text/javascript">
//<![CDATA[
function L(B,D){var A=document.createElement("script"),C=document.documentElement.firstChild;A.type="text/javascript";if(A.readyState){A.onreadystatechange=function(){if(A.readyState=="loaded"||A.readyState=="complete"){A.onreadystatechange=null;D()}}}else{A.onload=function(){D()}}A.src=B;C.insertBefore(A,C.firstChild)};
L("assets/js/example.js",min);
L("example/js/example2.js",min);
L("http://www.example.com/js/example3.js",min);
//]]>
</script>

