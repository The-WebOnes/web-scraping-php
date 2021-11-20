<?php 
require_once 'vendor/autoload.php';
require 'simple_html_dom.php';
require_once("../dataBase/DBConnection.php");

use ICanBoogie\Inflector;
use voku\helper\StopWords;

$DOCUMENTS_PATH = "../documents/";
$TXT_NAME = "links.txt";
$STORE_DOCUMENTS_SQL_STATEMENT = "INSERT INTO documents (link, snipped, content) VALUES (?,?,?)";
$TAGS_TO_DELETE = ['#<script(.*?)>(.*?)</script>#is','#<style(.*?)>(.*?)</style>#is'];

$ObjectConnection = new DBConnection();
$connection = $ObjectConnection->get_connection();

$links = $_POST["links"];
$arrayLinks = json_decode($links);
echo 'aver:';
var_dump($arrayLinks);

$listLink = '' ;
$urlStoredArray = [];

if (file_exists($DOCUMENTS_PATH.$TXT_NAME)) {
    $listLink = file_get_contents($DOCUMENTS_PATH.$TXT_NAME);
    $urlStoredArray = explode(PHP_EOL,$listLink);
    var_dump($urlStoredArray);

    $arrayLinks = array_diff($arrayLinks,$urlStoredArray);
}

$size = count($urlStoredArray);

$ContentTXT = $listLink;
$arraySubLinkslv1 = [];

var_dump($arrayLinks);

foreach ($arrayLinks as $link) {

    $ContentTXT .= $link.PHP_EOL;
    //get Content Links
    $stringPage = getWebContent($link);
    //Remove Scripts
    $stringPage = remove_specific_tags($TAGS_TO_DELETE, $stringPage);
    //GetLinks
    $arraySubLinkslv1 = obteinlink($stringPage);
     //Get Text
    $stringPage = getHTMLContent($stringPage);
     //Remove StopWords
    $textClean = removeStopWordsFromText($stringPage);
     //Save in the BD
    $sniped = substr($textClean,0,51);
    $statement = $connection->prepare($STORE_DOCUMENTS_SQL_STATEMENT);
    $statement -> execute([$link,$sniped,$textClean]);

    foreach ($arraySubLinkslv1 as $subLink) {
        $ContentTXT .= $subLink.PHP_EOL;
        $stringSubPage = getWebContent($subLink);
        $stringSubPage = remove_specific_tags($TAGS_TO_DELETE, $stringSubPage);
        $stringSubPage = getHTMLContent($stringSubPage);
        $subtextClean = removeStopWordsFromText($stringSubPage);
        $sniped = substr($subtextClean,0,51);
        $statement = $connection->prepare($STORE_DOCUMENTS_SQL_STATEMENT);
        $statement -> execute([$subLink,$sniped,$subtextClean]);
        
    }
}
file_put_contents($DOCUMENTS_PATH.$TXT_NAME, $ContentTXT);




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

function obteinlink($HTML)
{
    $html = str_get_html($HTML);
    $links = [];
    $MAX_SIZE = 2;
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

function obteinlink2($HTML)
{
    $html2 = str_get_html($HTML);
    $links = [];
    $MAX_SIZE = 2;
    $legth = 0;
    foreach($html2->find('a') as $item){
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
    $PUNCTUACION_MARKS = ['¡',"/","-",">","<","|","?","!",",",";",".","¿",":","\"","(",")","[","]","«","»"]; 

    //Remove tangs
    $rawText = remove_tangs($HTML);
    
    //Remove tilde
    $rawText = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),'a',$rawText);
    $rawText = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),'e',$rawText);
    $rawText = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),'i',$rawText);    
    $rawText = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),'o',$rawText);
    $rawText = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),'u',$rawText);
    $rawText = str_replace(array('ñ', 'Ñ', 'ç', 'Ç'),array('n', 'N', 'c', 'C'),$rawText);
    $rawText = preg_replace('/[0-9]+/', '', $rawText);
    
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
     try {
        $languaje = $detector->evaluate($text)->getLanguage();
        $arrayStopWords = $stopWords->getStopWordsFromLanguage($languaje);
        $tokens = explode(' ', $text);
        $tokensWithOutStopWords = array_diff($tokens,$arrayStopWords,["a","i","l",'',"x"]);
        //Singularize text. 
        $inflector = Inflector::get($languaje);
       
     } catch (Exception  $e) {
        $arrayStopWords = $stopWords->getStopWordsFromLanguage('es');
        $tokens = explode(' ', $text);
        $tokensWithOutStopWords = array_diff($tokens,$arrayStopWords,["a","i","l",'',"x"]);
        //Singularize text. 
        $inflector = Inflector::get('es');
     }

     $TokenzSingularized = [];
 
     foreach ($tokensWithOutStopWords as $word) {
         $singularizeWord = $inflector->singularize($word);
         $TokenzSingularized[] = $singularizeWord;
     }
 
     $textClean = implode(' ',$TokenzSingularized);

     return $textClean;
}

function FunctionName()
{
    # code...
}



?>