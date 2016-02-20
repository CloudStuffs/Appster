<?php

/**
 * @author Faizan Ayubi
 */
class Item extends Shared\Model {

	/**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_looklike_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_meta_key;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_meta_value;

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
     * @length 100
     */
    protected $_text;

}