<?php

/**
 * The Image Model
 *
 * @author Faizan Ayubi
 */
class Image extends Shared\Model {

	/**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_campaign_id;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label Source image link resource
     */
    protected $_src_im;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label x-coordinate of destination point
     */
    protected $_dst_x;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label y-coordinate of destination point
     */
    protected $_dst_y;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label x-coordinate of source point
     */
    protected $_src_x;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label y-coordinate of source point
     */
    protected $_src_y;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label Source width
     */
    protected $_src_w;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label Source height
     */
    protected $_src_h;

}