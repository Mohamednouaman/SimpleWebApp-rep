<?php

function function_group(array $categorie,$id):string{
    return $categorie[$id];
}
function verify(array $table_key,$arg):bool{ 
   
    foreach($table_key as $value){
             $aide=(string)$value;
             if($arg==$aide){
            return 1;
        }
    }
        return 0;
    
}
