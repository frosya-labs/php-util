<?php
namespace FrosyaLabs\Util\Facades\Serializard;

use Thunder\Serializard\FormatContainer\FormatContainer;
use Thunder\Serializard\Format\ArrayFormat;
use Thunder\Serializard\Format\JsonFormat;
use Thunder\Serializard\HydratorContainer\FallbackHydratorContainer;
use Thunder\Serializard\NormalizerContainer\FallbackNormalizerContainer;
use Thunder\Serializard\Serializard;

/**
 * <p>Data serialization and deserialization (un-serialization) facade using 
 * [thunderer/serializard] library.</p>
 * 
 * @author Nanang F. Rozi
 */
class SerializardFacade
{
    /**
     * @var FormatContainer
     */
    private static $formats;
    /**
     * @var string
     */
    private $class;
    /**
     * @var Serializard
     */
    private $serializard;

    /**
     * @param string $class   Type of hydration
     * @param string $handler Hydration handler
     */
    public function __construct($class, $handler)
    {
        if (is_null(self::$formats)) {
            self::$formats = new FormatContainer();
            self::$formats->add('array', new ArrayFormat());
            self::$formats->add('json', new JsonFormat());
        }
     
        $this->class = $class;
        $hydrators = new FallbackHydratorContainer();
        $hydrators->add($this->class, $handler.'::unserialize');
        $normalizers = new FallbackNormalizerContainer();
        $normalizers->add($this->class, $handler.'::serialize');
        
        $this->serializard = new Serializard(
            self::$formats, 
            $normalizers, 
            $hydrators
        );
    }
    
    /**
     * Serialize object
     * 
     * @param object $var
     * @return string
     */
    public function serialize($var) {
        return $this->serializeAsJson($var);
    }
    
    /**
     * Serialize object to JSON 
     * 
     * @param object $var
     * @return string
     */
    public function serializeAsJson($var)
    {
        return $this->serializard->serialize($var, 'json');
    }
    
    /**
     * Extract data from object as array
     * 
     * @param object $var
     * @return array
     */
    public function extract($var)
    {
        return $this->serializeAsArray($var);
    }
    
    /**
     * Serialize object to array
     * 
     * @param object $var
     * @return array
     */
    public function serializeAsArray($var)
    {
        return $this->serializard->serialize($var, 'array');
    }
    
    /**
     * Deserialize to object as configured in [class] property
     * 
     * @param string|array $var
     * @return object
     */
    public function deserialize($var)
    {
        return $this->unserialize($var);
    }
    
    /**
     * Deserialize to object as configured in [class] property
     * 
     * @param string|array $var
     * @return object
     */
    public function unserialize($var)
    {
        // If the data is JSON (string)
        if (is_string($var)) {
            return $this->unserializeFromJson($var);
        }
        
        return $this->unserializeFromArray($var);
    }
    
    /**
     * Deserialize from array
     * 
     * @param array $var
     * @return object
     */
    private function unserializeFromArray($var)
    {
        return $this->serializard->unserialize($var, $this->class, 'array');
    }
    
    /**
     * Deserialize from JSON
     * 
     * @param string $var
     * @return object
     */
    private function unserializeFromJson($var)
    {
        return $this->serializard->unserialize($var, $this->class, 'json');
    }
}