<?php

/**
 * The Item Model
 *
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
    protected $_key;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_value;

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