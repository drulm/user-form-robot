<?php

/**
 * 
 * User configuration settings.
 *
 * PHP version 7.0
 */
class Configuration
{
    /**
     * Hostname or IP
     * @var string
     */
    const DB_HOST = '192.168.59.76';

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
     * DB Username
     * @var string
     */
    const DB_USER = 'my_app';

    /**
     * DB Password
     * @var string
     */
    const DB_PASSWORD = 'secret';
    
    /**
     * Set to true to output error messages at bottom of page.
     * @var boolean
     */
    const VIEW_ERRORS = true;
    
    /**
     * Set to true for developer messages.
     * @var boolean
     */
    const DEBUG = false;
    
    /**
     * Error prefix string for Model/Database errors.
     * @var string
     */
    const DB_ERROR_MSG = "MODEL ERROR: ";
    
    /**
     * Error prefix string for Controller errors.
     * @var string
     */
    const CONT_ERROR_MSG = "CONTROLLER ERROR: ";

}
