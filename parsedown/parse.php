<link rel="stylesheet" href="parsedown/prism.css" />

<?php

    $uri = $_SERVER['REQUEST_URI'];
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    
    $file = file_get_contents($docRoot.$uri);
    if ($file === false) die ("File Not Found!");
    
    require "parsedown.php";
    
    $Parsedown = new Parsedown();

    echo $Parsedown->text($file);
    
?>

<script src="parsedown/prism.js"></script>