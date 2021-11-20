<?php 

class DBgetData
 {
    public function get_Array_Result_ASSOC($connection, $query)
    {
        $statement= $connection-> prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_Array_Result_Group($connection, $query)
    {
        $statement= $connection-> prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_GROUP);
        return $result;
    }
 }


?>