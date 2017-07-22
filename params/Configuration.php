<?php
/**
 *
 * User configuration settings.
 *
 * PHP version 7.0
 *
 * by: Darrell Ulm
 */
namespace params;

class Configuration
{
    /**
     * Hostname or IP
     * @var string
     */
    const DB_HOST = '127.0.0.1';
    
    /**
     * DB Username
     * @var string
     */
    const DB_USER = 'grace';

    /**
     * DB Password
     * @var string
     */
    const DB_PASSWORD = 'grace';

    /**
     * SQL(MySQL) port
     * @var string
     */
    const DB_MYSQL_PORT = '3306';

    /**
     * Database schema name
     * @var string
     */
    const DB_SCHEMA = 'my_app';
    
    /**
     * Set to true to output error messages at bottom of page.
     * @var boolean
     */
    const VIEW_ERRORS = true;

    /**
     * Error prefix string for Model/Database errors.
     * @var string
     */
    const DB_ERROR_MSG = 'MODEL ERROR: ';
    
    /**
     * Error prefix string for Controller errors.
     * @var string
     */
    const CONT_ERROR_MSG = 'CONTROLLER ERROR: ';
    
    /**
     * Set to true for developer messages.
     * Set to false during testing.
     *
     * @var boolean
     */
    const DEBUG = false;
    
    /**
     * Set size of TEST data
     *
     * @var boolean
     */
    const TEST_DATA_SIZE = 3;
}
