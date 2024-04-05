<?php

class Controller_connection extends Controller
{

    public function action_connection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identifiant'], $_POST['mdp']) && !empty($_POST['identifiant']) && !empty($_POST['mdp']))
        {
        $m = Model::getModel();
        $mail = $_POST['identifiant'];
        $password = $_POST['mdp'];
        
        // On récupère le modèle
        $data_user = $m->getData($mail);
        
        
        $hash = $m->connect($mail);
        $verify = password_verify($password, $hash['motdepasse']);

        
        if ($verify) {
            
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user'] = [
                'sess_prenom' => $data_user['prenom'],
                'sess_nom' => $data_user['nom'],
                'sess_role' => $data_user['role'],
            ];

            header("Location: ?controller=staffs");
            exit(); 

        } else {
            $this->render('connection', [
                "error" => "Identifiant ou mot de passe incorrect"
            ]);
        }
    }else{

        $this->render('connection', [
            "error" => "Identifiant ou mot de passe incorrect"
        ]);
    }
}


    public function action_default()
    {
        $this->render('connection');
    }
}
