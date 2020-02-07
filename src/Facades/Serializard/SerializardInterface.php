<?php
namespace Itats\Sim\Facades\Serializard;

/** 
 * @author Nanang F. Rozi
 */
interface SerializardInterface
{
    /**
     * Serialize object to a given format
     * 
     * @param object $data
     * @return array|string
     */
    static function serialize($data);
    
    /**
     * Serialize array to an object
     * 
     * @param array $data
     * @return object
     */
    static function unserialize($data);
}

