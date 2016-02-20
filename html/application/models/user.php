<?php

/**
 * @author Faizan Ayubi
 */
class User extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, alpha, min(3), max(32)
     * @label full name
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     * 
     * @validate required, max(100)
     * @label email address
     */
    protected $_email;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     * 
     * @validate required, alpha, min(8), max(100)
     * @label facebook id
     */
    protected $_fbid;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 32
     * @index
     * 
     * @validate required, alpha, min(8), max(32)
     * @label gender
     */
    protected $_gender;
    
    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_admin = false;

}
