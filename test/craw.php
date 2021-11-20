<?php 
require_once 'vendor/autoload.php';
require 'simple_html_dom.php';

use ICanBoogie\Inflector;
use voku\helper\StopWords;

$detector = new LanguageDetector\LanguageDetector();
$stopWords = new StopWords();



$TAGS_TO_DELETE = ['#<script(.*?)>(.*?)</script>#is','#<style(.*?)>(.*?)</style>#is'];
$PUNCTUACION_MARKS = ['¡',"/","-",">","<","|","?","!",",",";",".","¿",":","\"","(",")","[","]","«","»"]; 

//$links = $_POST["links"];
//$arrayLinks = explode(PHP_EOL,$links);


//get Content Links
$stringPage = getWebContent('https://www.cocinavital.mx/opciones-publicidad-pymes/');
//Remove Scripts
$stringPage = remove_specific_tags($TAGS_TO_DELETE, $stringPage);
//GetLinks
$arrayLinks = getLinks2($stringPage);

var_dump($arrayLinks);

//Remove tangs
$stringPage = remove_tangs($stringPage);
//Remove tilde
$stringPage = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),'a',$stringPage);
$stringPage = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),'e',$stringPage);
$stringPage = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),'i',$stringPage);    
$stringPage = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),'o',$stringPage);
$stringPage = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),'u',$stringPage);
$stringPage = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'),array('n', 'N', 'c', 'C'),$stringPage);
$stringPage = preg_replace('/[0-9]+/', '', $stringPage);
//Remove punctuation marks
$stringPage = str_replace($PUNCTUACION_MARKS,'',$stringPage);
//Lower
$stringPage = strtolower($stringPage);
//Detect languaje and remove stopwords
$languaje = $detector->evaluate($stringPage);
$arrayStopWords = $stopWords->getStopWordsFromLanguage($languaje);
$tokens = explode(' ', $stringPage);
$tokensWithOutStopWords = array_diff($tokens,$arrayStopWords,["a","i","l",'',"x"]);
//Singularize text 
$inflector = Inflector::get($languaje);
$length = count($tokensWithOutStopWords);
$TokenzSingularized = [];
foreach ($tokensWithOutStopWords as $word) {
    $singularizeWord = $inflector->singularize($word);
    $TokenzSingularized[] = $singularizeWord;
}

$textClean = implode(' ',$TokenzSingularized);

echo $textClean;

function getWebContent($url) {
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $info = curl_exec($ch); 
    curl_close($ch); 
    return $info; 
}

function remove_tangs($string) {
   
    $string = preg_replace ('/<[^>]*>/', ' ', $string);

    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    return $string;

}

function remove_specific_tags($ArrayTags, $HTML){
    $HTML = preg_replace($ArrayTags, '',  $HTML);
    return  $HTML;
}

function getLinks2($HTML)
{
    $html = str_get_html($HTML);
    $links = [];

    foreach($html->find('a') as $item){
        if($item->href) {
            $links[] = $item->href;
        }
    }

    return $links;
}

?>