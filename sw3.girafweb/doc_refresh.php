<html>
<body>
<pre>
<?php

$curDir = getcwd();

chdir(__DIR__);
chdir("..");

$input = `git pull`;

$input .= `doxygen doc_java.conf`;

$input .= `doxygen doc_php.conf`;

echo $input;

chdir($curDir);

?>
</pre>
</body>
</pre>
