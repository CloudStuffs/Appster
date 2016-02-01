<?php

/**
 * The LookLike Model
 *
 * @author Faizan Ayubi
 */
class LookLike extends Shared\Model {

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
     * @type text
     * @length 100
     * @label Base image link resource
     */
    protected $_base_im;

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

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label x-coordinate of source point
     */
    protected $_usr_x;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label y-coordinate of source point
     */
    protected $_usr_y;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label Source width
     */
    protected $_usr_w;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label Source height
     */
    protected $_usr_h;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label x-coordinate of destination point
     */
    protected $_txt_x;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label y-coordinate of destination point
     */
    protected $_txt_y;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The font size. Depending on your version of GD, this should be specified as the pixel size (GD1) or point size (GD2)
     */
    protected $_txt_size;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The angle in degrees, with 0 degrees being left-to-right reading text. Higher values represent a counter-clockwise rotation. For example, a value of 90 would result in bottom-to-top reading text
     */
    protected $_txt_angle;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The color index
     */
    protected $_txt_color;
}