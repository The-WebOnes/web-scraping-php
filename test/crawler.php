<?php 
require_once 'vendor/autoload.php';
require 'simple_html_dom.php';
require_once("../dataBase/DBConnection.php");

use ICanBoogie\Inflector;
use voku\helper\StopWords;

$DOCUMENTS_PATH = "../documents/";
$STORE_DOCUMENTS_SQL_STATEMENT = "INSERT INTO documents (link, snipped, content) VALUES (?,?,?)";
$TAGS_TO_DELETE = ['#<script(.*?)>(.*?)</script>#is','#<style(.*?)>(.*?)</style>#is'];

$ObjectConnection = new DBConnection();
$connection = $ObjectConnection->get_connection();

$links = $_POST["links"];
$arrayLinks = explode(PHP_EOL,$links);

foreach ($arrayLinks as $link) {
    //get Content Links
    $stringPage = getWebContent($link);
    //Remove Scripts
    $stringPage = remove_specific_tags($TAGS_TO_DELETE, $stringPage);
    //GetLinks
    $arraySubLinks = getLinks($stringPage);
    //Get Text
    $stringPage = getHTMLContent($stringPage);
    //Remove StopWords
    $textClean = removeStopWordsFromText($stringPage);
    //Save in the BD
    $sniped = substr($textClean,0,51);
    $statement = $connection->prepare($STORE_DOCUMENTS_SQL_STATEMENT);
    $statement -> execute([$link,$sniped,$textClean]);

    foreach ($arraySubLinks as $subLink) {
        
        $stringPage = getWebContent($link);
        $stringPage = remove_specific_tags($TAGS_TO_DELETE, $stringPage);
        $arraySubLinks = getLinks($stringPage);
        $stringPage = getHTMLContent($stringPage);
        $textClean = removeStopWordsFromText($stringPage);
        $sniped = substr($textClean,0,51);
        $statement = $connection->prepare($STORE_DOCUMENTS_SQL_STATEMENT);
        $statement -> execute([$link,$sniped,$textClean]);
    }

}

//Functions
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

function getLinks($HTML)
{
    $html = str_get_html($HTML);
    $links = [];
    $MAX_SIZE = 4;
    $legth = 0;
    foreach($html->find('a') as $item){
        if($item->href) {
            $links[] = $item->href;
        }
        if($legth==$MAX_SIZE){
            break;
        }
        $legth++;
    }
    return $links;
}


function getHTMLContent($HTML)
{
    $PUNCTUACION_MARKS = ['??',"/","-",">","<","|","?","!",",",";",".","??",":","\"","(",")","[","]","??","??"]; 

    //Remove tangs
    $rawText = remove_tangs($HTML);
    
    //Remove tilde
    $rawText = str_replace(array('??', '??', '??', '??', '??', '??', '??', '??', '??'),'a',$rawText);
    $rawText = str_replace(array('??', '??', '??', '??', '??', '??', '??', '??'),'e',$rawText);
    $rawText = str_replace(array('??', '??', '??', '??', '??', '??', '??', '??'),'i',$rawText);    
    $rawText = str_replace(array('??', '??', '??', '??', '??', '??', '??', '??'),'o',$rawText);
    $rawText = str_replace(array('??', '??', '??', '??', '??', '??', '??', '??'),'u',$rawText);
    $rawText = str_replace(array('??', '??', '??', '??'),array('n', 'N', 'c', 'C'),$rawText);
    $rawText = preg_replace('/[0-9]+/', '', $HTML);
    
    //Remove punctuation marks
    $rawText = str_replace($PUNCTUACION_MARKS,'',$rawText);
    
    //Lower
    $rawText = strtolower($rawText);

    return $rawText;
}


function removeStopWordsFromText($text)
{
    $detector = new LanguageDetector\LanguageDetector();
    $stopWords = new StopWords();
     //Detect languaje and remove stopwords
     $languaje = $detector->evaluate($text);
     $arrayStopWords = $stopWords->getStopWordsFromLanguage($languaje);
     $tokens = explode(' ', $text);
     $tokensWithOutStopWords = array_diff($tokens,$arrayStopWords,["a","i","l",'',"x"]);
     
     //Singularize text. 
     $inflector = Inflector::get($languaje);
    
     $TokenzSingularized = [];
 
     foreach ($tokensWithOutStopWords as $word) {
         $singularizeWord = $inflector->singularize($word);
         $TokenzSingularized[] = $singularizeWord;
     }
 
     $textClean = implode(' ',$TokenzSingularized);

     return $textClean;
}
