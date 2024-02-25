<?php
namespace Nayazkaya\AdsDev;
use PDO;
use PDOException;

class UserManager
{
    // Une instance de la classe PDO
    private PDO $pdo;

    // Le constructeur initialise l'instance de PDO
    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    // Cette méthode crée un nouvel utilisateur dans la base de données
    public function createUser(string $nom, string $prenom, string $email, string $mot_de_passe, string $role): bool
    {
        try {
            // Hache le mot de passe
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Génère un login unique à partir du nom
            $login = $this->generateLogin($nom);

            // Convertit la première lettre de chaque partie du prénom en majuscule
            $prenom = mb_convert_case($prenom, MB_CASE_TITLE, "UTF-8");

            // Prépare la requête SQL pour insérer le nouvel utilisateur
            $query = 'INSERT INTO Utilisateurs (nom, prenom, login, email, mot_de_passe_hash, role) VALUES (?, ?, ?, ?, ?, ?)';
            $statement = $this->pdo->prepare($query);

            // Exécute la requête SQL et retourne le résultat
            return $statement->execute([$nom, $prenom, $login, $email, $mot_de_passe_hash, $role]);
        } catch (PDOException $e) {
            // Gère les erreurs PDO et affiche un message d'erreur
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // Cette méthode génère un login unique
    private function generateLogin(string $nom): string
    {
        $login = $nom;

        // Si le login existe déjà, ajoute un incrémentiel
        if ($this->loginExists($login)) {
            $count = 1;
            while ($this->loginExists($login . $count)) {
                $count++;
            }
            $login .= $count;
        }

        // Retourne le login
        return $login;
    }

    // Cette méthode vérifie si un login existe déjà dans la base de données
    private function loginExists(string $login): bool
    {
        try {
            // Prépare la requête SQL pour compter le nombre d'occurrences du login
            $query = 'SELECT COUNT(*) FROM Utilisateurs WHERE login = ?';
            $statement = $this->pdo->prepare($query);
            $statement->execute([$login]);

            // Retourne vrai si le login existe, faux sinon
            return $statement->fetchColumn() > 0;
        } catch (PDOException $e) {
            // Gère les erreurs PDO et affiche un message d'erreur
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
}