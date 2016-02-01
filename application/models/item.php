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
     * @type text
     * @length 100
     */
    protected $_looklike_id;

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