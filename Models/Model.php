<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET nameS 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }



    public function getData($mail) {
        // Préparation de la requête pour récupérer les informations de l'utilisateur
        $req = $this->bd->prepare("
            SELECT 
                p.nom,
                p.prenom,
                CASE
                    WHEN d.id_personne IS NOT NULL THEN 'Directeur'
                    WHEN ed.id_personne IS NOT NULL THEN 'Equipe de Direction'
                    WHEN e.id_personne IS NOT NULL THEN 'Enseignant'
                    WHEN s.id_personne IS NOT NULL THEN 'Secretaire'
                    ELSE 'Autre'
                END AS role
            FROM 
                personne p
            LEFT JOIN 
                enseignant e ON p.id_personne = e.id_personne
            LEFT JOIN 
                secretaire s ON p.id_personne = s.id_personne
            LEFT JOIN 
                directeur d ON p.id_personne = d.id_personne
            LEFT JOIN 
                equipedirection ed ON p.id_personne = ed.id_personne
            WHERE 
                p.email = :email
        ");
        $req->bindValue(':email', $mail, PDO::PARAM_STR);
        $req->execute();

        // Récupération des informations de l'utilisateur
        $result = $req->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getPersonne()
    {
        $req = $this->bd->prepare(" SELECT 
            p.id_personne,
            p.nom,
            p.email,
            p.prenom,
            CASE
                WHEN d.id_personne IS NOT NULL THEN 'Directeur'
                WHEN ed.id_personne IS NOT NULL THEN 'Equipe de direction'
                WHEN e.id_personne IS NOT NULL THEN 'Enseignant'
                WHEN s.id_personne IS NOT NULL THEN 'Secrétaire'
                ELSE 'Personnel'
            END AS role
            FROM 
                personne p
            LEFT JOIN 
                directeur d ON p.id_personne = d.id_personne
            LEFT JOIN 
                equipedirection ed ON p.id_personne = ed.id_personne
            LEFT JOIN 
                enseignant e ON p.id_personne = e.id_personne
            LEFT JOIN 
                secretaire s ON p.id_personne = s.id_personne
            
     ");
        $req->execute();
        return $req->fetchall();
    }

    public function connect($mail)
    {
        $req=$this->bd->prepare("SELECT motdepasse from personne WHERE email = :email");
        $req->bindValue(':email', $mail);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
            
        return $result;
    }
    
    /*public function rechercheEnseignants($recherche) {
        // Préparation de la requête combinée
        $req = $this->bd->prepare("SELECT 
                                        p.id_personne
                                    FROM 
                                        personne p
                                    LEFT JOIN 
                                        enseignant e ON p.id_personne = e.id_personne
                                    LEFT JOIN 
                                        secretaire s ON p.id_personne = s.id_personne
                                    LEFT JOIN 
                                        directeur d ON p.id_personne = d.id_personne
                                    LEFT JOIN
                                        equipedirection ed ON p.id_personne = ed.id_personne
                                    WHERE
                                        p.nom LIKE :recherche OR p.prenom LIKE :recherche");
                                        // Liaison du terme de recherche
                            $req->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);

                            // Exécution de la requête et vérification de la réussite
                            if ($req->execute()) {
                                // Récupération et retour des résultats en cas de succès
                                return $req->fetchAll(PDO::FETCH_ASSOC);
                            } else {
                                // Gestion de l'échec de la requête
                                // Vous pouvez retourner un message d'erreur ou gérer l'erreur différemment
                                return null;}
    }*/
    public function rechercheEnseignants($recherche){
        $req = $this->bd->prepare("SELECT 
                            p.id_personne
                        FROM 
                            personne p
                        WHERE
                            p.nom LIKE :recherche OR p.prenom LIKE :recherche");
                    $req->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);

                    if ($req->execute()) {
                    return $req->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                    return null;}
    }

    public function add_person($id, $nom, $prenom, $email, $mdp) {
        $req = $this->bd->prepare("INSERT INTO personne (id_personne, nom, prenom, email, motDePasse) VALUES (:id, :nom, :prenom, :email, :mdp)");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
        $req->bindValue(':prenom', $prenom);
        $req->bindValue(':email', $email);
        $req->bindValue(':mdp', $mdp);
    
        // Exécuter la requête
        $success = $req->execute();
    
        // Retourner true si l'insertion a réussi, false sinon
        return $success;
    }

    public function ajouterRole($id, $tableRole) {
        
        $sql = "INSERT INTO " . $tableRole . " (id_personne) VALUES (:id)";
        $req = $this->bd->prepare($sql);
        $req->bindValue(':id', $id);
    
            // Exécuter la requête et retourner le résultat
        return $req->execute();
    }
    
    
    public function getDiscipline($nomDiscipline) {
        $req = $this->bd->prepare("SELECT idDiscipline FROM discipline WHERE libelleDisc = :nomDiscipline");
        $req->bindValue(':nomDiscipline', $nomDiscipline);
        $req->execute();
        return $req->fetchColumn();
    }

    public function getCategorie($sigleCategorie) {
        $req = $this->bd->prepare("SELECT id_categorie FROM categorie WHERE sigleCat = :sigleCategorie");
        $req->bindValue(':sigleCategorie', $sigleCategorie);
        $req->execute();
        return $req->fetchColumn();
    }
    
    public function ajouterEnseignant($idPersonne, $idDiscipline, $idCategorie,$annee) {
        $req = $this->bd->prepare("INSERT INTO enseignant (id_personne, idDiscipline, id_categorie, AA) VALUES (:idPersonne, :idDiscipline, :idCategorie, :annee)");
        $req->bindValue(':idPersonne', $idPersonne);
        $req->bindValue(':idDiscipline', $idDiscipline);
        $req->bindValue(':idCategorie', $idCategorie);
        $req->bindValue(':annee', $annee);

    
        return $req->execute();
    }
    
    public function getLastId() {
        $req = $this->bd->prepare("SELECT id_personne FROM personne ORDER BY id_personne DESC LIMIT 1;");
        $req->execute();
        $count = $req->fetchColumn();
        return $count;
    }

    public function getMatiere($matiere) {
        // Préparer la requête SQL pour récupérer les IDs des personnes associées à une discipline spécifique
        $req = $this->bd->prepare("SELECT id_personne FROM enseignant WHERE idDiscipline = (SELECT idDiscipline FROM discipline WHERE libelleDisc = :matiere)");
        $req->bindValue(':matiere', $matiere);
        $req->execute();
    
        // Retourner les IDs des personnes
        return $req->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getRole($role) {
        $sql = "";
    
        switch ($role) {
            case 'directeur':
                $sql = "SELECT id_personne FROM directeur";
                break;
            case 'secretaire':
                $sql = "SELECT id_personne FROM secretaire";
                break;
            case 'enseignant':
                $sql = "SELECT id_personne FROM enseignant";
                break;
            case 'equipedirection':
                $sql = "SELECT id_personne FROM equipedirection";
                break;
            // Ajouter d'autres rôles si nécessaire
            default:
                return []; // Retourner un tableau vide si le rôle n'est pas reconnu
        }
    
        $req = $this->bd->prepare($sql);
        $req->execute();
    
        // Retourner les IDs des personnes
        return $req->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    

    public function getChartCategory() {
        // Exemple de requête SQL pour récupérer les données
        $req = $this->bd->prepare('SELECT
                                        cat.sigleCat AS categorie,
                                        COUNT(e.id_personne) AS nombre_enseignants
                                    FROM
                                        categorie cat
                                    LEFT JOIN
                                        enseignant e ON cat.id_categorie = e.id_categorie
                                    GROUP BY
                                        cat.sigleCat;');
    
        $req->execute();
    
        $results = $req->fetchAll(PDO::FETCH_ASSOC);
    
        return $results;
    }
    
    public function chartCategoryByDepartement() {
        $req = $this->bd->prepare('SELECT
                                        cat.sigleCat AS categorie,
                                        COUNT(e.id_personne) AS nombre_enseignants
                                    FROM
                                        categorie cat
                                    LEFT JOIN
                                        enseignant e ON cat.id_categorie = e.id_categorie
                                    LEFT JOIN
                                        departement dep ON e.id_personne = dep.id_personne
                                    WHERE
                                        dep.sigleDept = \'INFO\'
                                    GROUP BY
                                        cat.sigleCat;');

        $req->execute();
        

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function chartHoursByDiscipline() {
        $req = $this->bd->prepare('SELECT
                                    d.libelleDisc AS discipline,
                                    SUM(en.nbHeureEns) AS total_heures
                                FROM
                                    enseigne en
                                JOIN
                                    discipline d ON en.idDiscipline = d.idDiscipline
                                GROUP BY
                                    d.libelleDisc;');
        
        $req->execute();

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPersonnesInfos($ids,$order) {
        if (empty($ids)) {
            return []; // Retourner un tableau vide si aucun ID n'est fourni
        }

        // Convertir le tableau d'IDs en une chaîne pour la requête SQL
        $idsStr = implode(',', array_map('intval', $ids)); // Assurer la sécurité en convertissant les ID en entiers

        // Construire la requête SQL
        $sql = "SELECT p.nom, p.prenom, p.email, 
                    CASE WHEN d.id_personne IS NOT NULL THEN 'directeur'
                            WHEN s.id_personne IS NOT NULL THEN 'secretaire'
                            WHEN e.id_personne IS NOT NULL THEN 'enseignant'
                            WHEN ed.id_personne IS NOT NULL THEN 'equipedirection'
                            ELSE 'Autre'
                        END AS role
                FROM personne p
                LEFT JOIN directeur d ON p.id_personne = d.id_personne
                LEFT JOIN secretaire s ON p.id_personne = s.id_personne
                LEFT JOIN enseignant e ON p.id_personne = e.id_personne
                LEFT JOIN equipedirection ed ON p.id_personne = ed.id_personne
                WHERE p.id_personne IN ($idsStr)
                ORDER BY p.nom $order, p.prenom $order";

        $req = $this->bd->prepare($sql);
        $req->execute();

        // Retourner les résultats
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerSecretaire($id_personne)
    {
    $sql = "DELETE FROM secretaire WHERE id_personne= :id_personne" ;
    $req= $this->bd->prepare($sql);
    $req->bindParam(':id_personne', $id_personne);
    $req->execute();
    return (bool) $req->rowCount();
    }
    public function supprimerEnseignant($id_personne) {
        try {
            // Démarrer une transaction
            $this->bd->beginTransaction();

            // Vérifier les associations
            $check_association = $this->bd->prepare('SELECT 1 
                                                    FROM departement 
                                                    WHERE id_personne = :id_personne 
                                                    UNION ALL
                                                    SELECT 1 
                                                    FROM directeur 
                                                    WHERE id_personne = :id_personne 
                                                    UNION ALL
                                                    SELECT 1 
                                                    FROM equipedirection 
                                                    WHERE id_personne = :id_personne;');
            $check_association->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
            $check_association->execute();

            if ($check_association->rowCount() > 0) {
                $this->bd->rollBack();
                return "L'enseignant est un chef de département, un directeur ou un membre de l'équipe de direction. Vous ne pouvez pas le supprimer.";
            } else {
                $tab = ['equipedirection', 'directeur', 'assigner', 'connaitAussi', 'enseigne', 'enseignant', 'personne'];
                foreach ($tab as $t) {
                    $req = $this->bd->prepare('DELETE FROM ' . $t . ' WHERE id_personne = :id_personne;');
                    $req->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
                    $req->execute();
                }

                // Appliquer les modifications
                $this->bd->commit();
                return 'La personne a bien été supprimée.';
            }
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            $this->bd->rollBack();
            // Gérer l'erreur ou la renvoyer
            throw $e;
        }
    }

    public function getPersonneById($id) {
        $req = $this->bd->prepare("
            SELECT 
                p.id_personne,
                p.nom,
                p.email,
                p.prenom,
                CASE
                    WHEN d.id_personne IS NOT NULL THEN 'directeur'
                    WHEN ed.id_personne IS NOT NULL THEN 'equipedirection'
                    WHEN e.id_personne IS NOT NULL THEN 'enseignant'
                    WHEN s.id_personne IS NOT NULL THEN 'secretaire'
                    ELSE 'personne'
                END AS role
            FROM 
                personne p
            LEFT JOIN 
                directeur d ON p.id_personne = d.id_personne
            LEFT JOIN 
                equipedirection ed ON p.id_personne = ed.id_personne
            LEFT JOIN 
                enseignant e ON p.id_personne = e.id_personne
            LEFT JOIN 
                secretaire s ON p.id_personne = s.id_personne
            WHERE 
                p.id_personne = :id
        ");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function getPersonnesByIds($ids, $order = 'asc') {
        // Convertir le tableau d'ID en une chaîne de caractères séparée par des virgules
        $idsString = implode(',', array_map('intval', $ids));
        if (empty($ids)) {
            return 'Aucune personne'; 
        }
    
        // Définir l'ordre de tri
        $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
    
        $req = $this->bd->prepare("
            SELECT 
                p.id_personne,
                p.nom,
                p.email,
                p.prenom,
                CASE
                    WHEN d.id_personne IS NOT NULL THEN 'directeur'
                    WHEN ed.id_personne IS NOT NULL THEN 'equipedirection'
                    WHEN e.id_personne IS NOT NULL THEN 'enseignant'
                    WHEN s.id_personne IS NOT NULL THEN 'secretaire'
                    ELSE 'personne'
                END AS role
            FROM 
                personne p
            LEFT JOIN 
                directeur d ON p.id_personne = d.id_personne
            LEFT JOIN 
                equipedirection ed ON p.id_personne = ed.id_personne
            LEFT JOIN 
                enseignant e ON p.id_personne = e.id_personne
            LEFT JOIN 
                secretaire s ON p.id_personne = s.id_personne
            WHERE 
                p.id_personne IN ($idsString)
            ORDER BY 
                p.nom $order
        ");
        
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function besoinHeuresParDepartement() {
        $req = $this->bd->prepare('SELECT b.besoin_heure, d.libelledisc FROM besoin b JOIN discipline d ON b.iddiscipline = d.iddiscipline;');
        $req->execute();
    
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    public function modifyUser($id, $nom, $prenom, $email) {
        try {
            $req = $this->bd->prepare("UPDATE personne SET nom = :nom, prenom = :prenom, email = :email WHERE id_personne = :id");
    
            // Liaison des paramètres
            $req->bindParam(':id', $id);
            $req->bindParam(':nom', $nom);
            $req->bindParam(':prenom', $prenom);
            $req->bindParam(':email', $email);
    
            $req->execute();
    
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false; 
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    
}