<?php


/**
 * Segéd metódusok
 *
 * @author FisMisi
 */
class Helper 
{
    public static function checkAvailability($value)
    {
        if ($value == 1)
        {
            echo 'Elérhető';
        }else{
            echo 'Nem elérhető'; 
        }
    }
}
