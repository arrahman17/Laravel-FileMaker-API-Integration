<?php
/**
 * Short description for file: This class contains the database configurations
 *
 * Long description for file: All the logic is implemented here in the controller such accessing the date from the filemaker using Official filemkaker API
 *
 * PHP version 7.4 and later:
 *
 * @category  Trust Promotion
 * @author    Abdur Rahman <abdur.rahman@netmarket.de>
 * @copyright 2021 NetMarket PMS GmbH
 * @license   http://www.netmarket.de NetMarket
 * @link      http://www.netmarket.de
 *
 *
 * Date: 11.02.2021
 * Time: 15:39
 */

namespace Netmarket\FileMaker;


class DatabaseConfiguration
{

    /**
     * @var array
     * File maker connections parameter
     */
    private static $database_connection_pramas = array(
        array('property_name' => 'database','value' =>''),
        array('property_name' => 'hostspec','value' =>''),
        array('property_name' => 'username','value' =>''),
        array('property_name' => 'password','value' =>'')
    );

    /**
     * Returns the database params
     *
     * @return array
     */
    public static function getDatabaseParams()  : array
    {
        return self::$database_connection_pramas;
    }

}
