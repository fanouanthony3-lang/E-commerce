<?php

require_once __DIR__ . "/../Models/User.php";

class AuthController
{
    private User $user;

    public function __construct(PDO $db)
    {
        $this->user = new User($db);

        session_start();
    }

    public function register()
    {
        // inscription 
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (
        empty($nom) ||
        empty($email) ||
        empty($password)
    ) {
        return [
            "success" => false,
            "message" => "Tous les champs sont obligatoires."
        ];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            "success" => false,
            "message" => "Adresse email invalide."
        ];
    }

    $existingUser = $this->user->findByEmail($email);

    if ($existingUser) {
        return [
            "success" => false,
            "message" => "Cet email est déjà utilisé."
        ];
    }

    $created = $this->user->create(
        $nom,
        $email,
        $password
    );

    if ($created) {
        return [
            "success" => true,
            "message" => "Inscription réussie."
        ];
    }

    return [
        "success" => false,
        "message" => "Une erreur est survenue."
    ];
    }

    public function login()
    {
        // connexion

        $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        return [
            "success" => false,
            "message" => "Email et mot de passe obligatoires."
        ];
    }

    $user = $this->user->findByEmail($email);

    if (!$user) {
        return [
            "success" => false,
            "message" => "Email ou mot de passe incorrect."
        ];
    }

    if (!password_verify($password, $user["password"])) {
        return [
            "success" => false,
            "message" => "Email ou mot de passe incorrect."
        ];
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["nom"] = $user["nom"];
    $_SESSION["email"] = $user["email"];
    $_SESSION["role"] = $user["role"];

    return [
        "success" => true,
        "message" => "Connexion réussie."
    ];
    }

    public function logout()
    {
        // déconnexion

         $_SESSION = [];

    if (ini_get("session.use_cookies")) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    return [
        "success" => true,
        "message" => "Déconnexion réussie."
    ];
    }

    public function checkAuth():bool
    {
    // vérifier si l'utilisaeur est connecté
        return isset($_SESSION["user_id"]);
    
    }
    public function getCurrentUser() { 
    // récupérer l'utilisateur connecté 
    if (!$this->checkAuth()) {
         return null; 
         } return [ 
          "id" => $_SESSION["user_id"],
          "nom" => $_SESSION["nom"], 
          "email" => $_SESSION["email"], 
          "role" => $_SESSION["role"] 
          ]; 
    }
    public function isAdmin(): bool { 
    //Vérifier si l'utilisateur est administrateur
        return (
            $this->checkAuth() &&
            $_SESSION["role"] === "admin" ); 
    }

    public function isClient(): bool {
        //Vérifier si l'utilisateur est client
         return (
            $this->checkAuth() &&
            $_SESSION["role"] === "client" ); 
        }
    
    public function requireAuth() {
        //Progéger la page
         if (!$this->checkAuth()) {
             header("Location: /boutique/views/login.php"); exit;
            } 
        }
    
     public function requireAdmin() {
         if (!$this->checkAuth()) { 
            header("Location: /boutique/views/login.php");
             exit;
            } 
        if (!$this->isAdmin()) {
            http_response_code(403);
            die("Accès interdit."); 
            } 
        }   
}