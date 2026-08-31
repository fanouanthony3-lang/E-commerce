<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(
        string $nom,
        string $email,
        string $password
    )
    {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "
            INSERT INTO users
            (nom, email, password)
            VALUES (?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $nom,
            $email,
            $passwordHash
        ]);
    }
}