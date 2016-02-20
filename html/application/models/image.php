<?php

/**
 * @author Faizan Ayubi, Hemant Mann
 */
class Image extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @label x-coordinate of source point
     * @validate required, max(10)
     */
    protected $_usr_x;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @label y-coordinate of source point
     * @validate required, max(10)
     */
    protected $_usr_y;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @label Source width
     * @validate required, max(10)
     */
    protected $_usr_w;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @label Source height
     * @validate required, max(10)
     */
    protected $_usr_h;
}
