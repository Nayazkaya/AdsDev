<?php
namespace Nayazkaya\AdsDev;
use PDO;

class Database
{
    // Une instance de la classe PDO
    private static ?PDO $instance = null;

    // Cette méthode retourne une instance de la classe PDO
    public static function getPdo(): PDO
    {
        // Si l'instance n'a pas encore été créée
        if (self::$instance === null)
        {
            // On récupère les paramètres de configuration de la base de données
            $config = require 'config.php';

            // On crée la chaîne de connexion à la base de données
            $strConn = 'mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'] . ';charset=utf8';
            
            // On définit des paramètres supplémentaires pour la connexion
            $extraParam = [
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ];
            
            // On crée une nouvelle instance de PDO
            self::$instance = new PDO($strConn, $config['username'], $config['password'], $extraParam);
        }
        
        // On retourne l'instance de PDO
        return self::$instance;
    }
}