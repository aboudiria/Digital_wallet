<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $fullname;
    public $email;
    public $phone;
    public $password;
    public $id_document;
    public $is_verified;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new user record
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET fullname = :fullname, email = :email, phone = :phone, password = :password, id_document = :id_document";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id_document = htmlspecialchars(strip_tags($this->id_document));

        // bind values
        $stmt->bindParam(":fullname", $this->fullname);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":id_document", $this->id_document);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Check if a user with a given email already exists
    public function emailExists() {
        $query = "SELECT id, fullname, password, phone, id_document, is_verified 
                  FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->fullname = $row['fullname'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->id_document = $row['id_document'];
            $this->is_verified = $row['is_verified'];
            return true;
        }
        return false;
    }
}
?>
