<?php 
$DOCUMENTS_PATH = "../documents/";
$TXT_NAME = "links.txt";

if (file_exists($DOCUMENTS_PATH.$TXT_NAME)) {
    $links = file_get_contents($DOCUMENTS_PATH.$TXT_NAME);

    echo $links;
}else{
    echo "";
}


?>