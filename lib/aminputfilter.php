<?php

/**
 * This class makes easier 
 * 
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @package Core
 */
class AMInputFilter 
{
    protected  $data;
    
    /**
     * Constructor of AMFilter
     *
     * @param Array $data An array of data to be filtered.
     */
    function __construct($data) 
    {
        if(!is_array($data)) {
            Throw new AMException("AMFilter requires an array as parameter in the constructor method.");
        }
        $this->$data = $data;
    }
    
    /**
     * This function is a wrapper to the filter_data function. 
     * It receives the filter type an parameters and invoke filter_data .
     * 
     * @param int $key The key the position in the array containing data to be filtered.
     * @param int $filter A valid php filter.
     * @param int $mixedOptions Options to the filter_data.
     * @param string $charset The charset of the data to be filtered.
     */
    protected function __applyFilter($key, $filter, $mixedOptions="", $charset="" )
    {
         return filter_data($this->data[$key], $filter, $mixedOptions, $charset);
    }
    
    
    public function getInt($key)
    {
        return $this->__applyFilter($key, FILTER_VALIDATE_INT);
    }
    
    public function getHex($key)
    {
        return $this->__applyFilter($key, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_HEX);
    }
    
    public function getOctal($key)
    {
        return $this->__applyFilter($key, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL);
    }
    
    public function get
    
}