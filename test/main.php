<?php 
require 'simple_html_dom.php';
$page = "https://devcode.la/tutoriales/hacer-web-scraping-con-php/";
$page2 = 'http://htmlpurifier.org';

$stringPage = curl('https://www.cocinavital.mx/recetas/pan-casero/como-hacer-masa-para-waffles/');

/*
$dom = new DOMDocument();
$dom-> loadHTML($stringPage);
$tags_to_remove = array('script','style','iframe','link');
foreach($tags_to_remove as $tag){
    $element = $dom->getElementsByTagName($tag);
    foreach($element  as $item){
        $item->parentNode->removeChild($item);
    }
}
$html = $dom->saveHTML();

echo $html;

//$html = file_get_html($page);
*/

//Quitar JS




$stringPage = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $stringPage);
$stringPage = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $stringPage);
//$stringPage = preg_replace('#<code(.*?)>(.*?)</code>#is', '', $stringPage);

//echo $stringPage;

$html = str_get_html($stringPage);


$links [] = $page;

foreach($html->find('a') as $item){
    if($item->href) {
        $links[] = $item->href;
    }
}

var_dump($links);


$stringPage = rip_tags($stringPage);

echo $stringPage;

$stringPageLower = strtolower($stringPage);

$TOKENS = explode(" ",$stringPage);
var_dump($TOKENS);


/*
$dom = new DOMDocument();
$dom -> loadHTML(htmlspecialchars($stringPage));
$dom -> saveHTML();

$href = $dom->getElementsByTagName('a');

var_dump($href);
*/


//$stringPage = preg_replace('/\s+/', '', $stringPage);
//$stringPage = strip_tags($stringPage, '<p><h1><h2><h3>');
//echo  $stringPage ;








/*
$dom = new DOMDocument();
$dom -> loadHTML($stringPage);
*/
//var_dump($dom);



//obtener las otras URL
/*
$href = $dom->getElementsByTagName('a');

var_dump($href);



foreach ($href as $item) {
     $nelson = $item->getAttribute('href');
     print_r($nelson.PHP_EOL) ; 
}
*/

//Remove Scripts


//Remove las etiquetas

//pasar a minuscula

//cambias signo de puntuación


//detectar idioma y quitar stopwords


// singularizar



//Funciones
function curl($url) {
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $info = curl_exec($ch); 
    curl_close($ch); 
    return $info; 
}

function rip_tags($string) {
   
    $string = preg_replace ('/<[^>]*>/', ' ', $string);

    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    return $string;

}


?>