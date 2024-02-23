<?php
namespace Nayazkaya\AdsDev;
use PDO;

class Database
{
    private static ?PDO $instance = null;

    public static function getPdo(): PDO
    {
        if (self::$instance === null)
        {
            $config = require 'config.php';

            $strConn = 'mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'] . ';charset=utf8';
            $extraParam = [
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ];
            self::$instance = new PDO($strConn, $config['username'], $config['password'], $extraParam);
        }
        return self::$instance;
    }
}