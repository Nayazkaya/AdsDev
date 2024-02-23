<?php
namespace Nayazkaya\AdsDev;
use PDO;

class User
{
    // Créer un nouvel utilisateur
    public static function create(string $firstName, string $lastName, string $email, string $password, string $status, string $role): bool
    {
        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer la requête SQL
        $sql = 'INSERT INTO users (first_name, last_name, email, password, status, role) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = Database::getPdo()->prepare($sql);

        // Exécuter la requête SQL
        return $stmt->execute([$firstName, $lastName, $email, $hashedPassword, $status, $role]);
    }

    // Modifier un utilisateur existant
    public static function update(int $id, string $firstName, string $lastName, string $email, string $status, string $role): bool
    {
        // Préparer la requête SQL
        $sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ?, status = ?, role = ? WHERE id = ?';
        $stmt = Database::getPdo()->prepare($sql);

        // Exécuter la requête SQL
        return $stmt->execute([$firstName, $lastName, $email, $status, $role, $id]);
    }

    // Supprimer un utilisateur
    public static function delete(int $id): bool
    {
        // Préparer la requête SQL
        $sql = 'DELETE FROM users WHERE id = ?';
        $stmt = Database::getPdo()->prepare($sql);

        // Exécuter la requête SQL
        return $stmt->execute([$id]);
    }

    // Réinitialiser le mot de passe d'un utilisateur
    public static function resetPassword(int $id, string $newPassword): bool
    {
        // Hasher le nouveau mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Préparer la requête SQL
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        $stmt = Database::getPdo()->prepare($sql);

        // Exécuter la requête SQL
        return $stmt->execute([$hashedPassword, $id]);
    }
}
