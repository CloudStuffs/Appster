<?php

/**
 * The Campaign Model
 *
 * @author Faizan Ayubi
 */
class Campaign extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_user_id;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_title;
	
	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_image;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_description;

    /**
    * @column
    * @readwrite
    * @type boolean
    */
    protected $_fblogin = false;
}