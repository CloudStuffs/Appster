<?php

/**
 * The Text Model
 *
 * @author Faizan Ayubi
 */
class Text extends Shared\Model {

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
     * @label The font size. Depending on your version of GD, this should be specified as the pixel size (GD1) or point size (GD2)
     */
    protected $_font_size;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The angle in degrees, with 0 degrees being left-to-right reading text. Higher values represent a counter-clockwise rotation. For example, a value of 90 would result in bottom-to-top reading text
     */
    protected $_angle;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The coordinates given by x and y will define the basepoint of the first character (roughly the lower-left corner of the character). This is different from the imagestring(), where x and y define the upper-left corner of the first character. For example, "top left" is 0, 0
     */
    protected $_x;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The y-ordinate. This sets the position of the fonts baseline, not the very bottom of the character.
     */
    protected $_y;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The color index
     */
    protected $_color;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The path to the TrueType font you wish to use
     */
    protected $_fontfile;

	/**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @label The text string in UTF-8 encoding
     */
    protected $_content;
}