<?php 
class QueryCreator
{

    public function builder($rawQuery)
    {

        $QueryStatement = "SELECT link,snipped,content, MATCH(content) AGAINST('\"$rawQuery\"' IN BOOLEAN MODE) AS score FROM documents WHERE MATCH(content) AGAINST ('\"$rawQuery\"' IN BOOLEAN MODE) ORDER BY score DESC";

        return $QueryStatement;
    }
}


?>