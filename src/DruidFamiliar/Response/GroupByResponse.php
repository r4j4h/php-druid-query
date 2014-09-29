<?php

namespace DruidFamiliar\Response;

use stdClass;

/**
 * Class GroupByResponse
 * @package   DruidFamiliar\Response
 * @author    Ernesto Spiro Peimbert Andreakis
 * @version   1.0
 * @category  WebPT
 * @copyright Copyright (c) 2014 WebPT, Inc.
 */
class GroupByResponse {
    /**
     * Stores the response data as an array
     * @access protected
     * @var array
     */
    protected $data;
    /**
     * required properties to be present in each of the elements within the data array
     * @var array
     */
    protected $requiredObjectFields = array('version','timestamp','event');

    /**
     * Returns the data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        foreach($data as $anObject){
            $this->addData($anObject);
        }
        return $this;
    }

    /**
     * Adds an element to the data array
     * @param stdClass $data
     *
     * @return $this
     */
    public function addData($data){
        if(is_object($data)){
            $complete = true;
            foreach($this->requiredObjectFields as $property) {
                if(!isset($data->$property)){
                    $complete = false;
                    break;
                }
            }
            if($complete){
                $this->data[] = $data;
            }
        }
        else if(is_array($data)){
            $complete = true;
            foreach($this->requiredObjectFields as $property) {
                if(!isset($data[$property])){
                    $complete = false;
                    break;
                }
            }
            if($complete){
                $this->data[] = $data;
            }
        }
        return $this;
    }
} 