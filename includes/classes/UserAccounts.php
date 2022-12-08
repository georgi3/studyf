<?php
class UserAccounts{
    private $conn;
    private $errorArr = array();

    public function __construct($conn) {
        $this->conn = $conn;
    }   

    public function register($fName, $lName, $username, $userType, $email1, $email2, $password1, $password2){
        $this->validateFirstName($fName);
        $this->validateLastName($lName);
        $this->validateUsername($username);
        $this->validateEmails($email1, $email2);
        $this->validatePasswords($password1, $password2);
        if (empty($this->errorArr)) {
        return $this->createNewAccount($fName, $lName, $username, $userType, $email1, $password1);
        }
        return false;
    }

    public function login($username, $password){
        $password = hash("sha512", $password);
        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password;");
        $query->bindValue(":username", $username);
        $query->bindValue(":password", $password);
        $query->execute();
        if($query->rowCount() == 1){
            $result = $query->fetch();
            $this->debug_to_console($result);
            return [true, $result];
        }
        array_push($this->errorArr, Constants::$incorrectCredentials);
        return false;
    }
    public function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    private function createNewAccount($fName, $lName, $username, $userType, $email, $password){
        $password = hash("sha512", $password);
        $query = $this->conn->prepare("INSERT INTO 
                                        users (firstName, lastName, username, email, password, isTeacher)
                                        VALUES (:fn, :ln, :un, :em, :pw, :it);");
        $query->bindValue(":fn", $fName);
        $query->bindValue(":ln", $lName);
        $query->bindValue(":un", $username);
        $query->bindValue(":em", $email);
        $query->bindValue(":pw", $password);
        $query->bindValue(":it", $userType);
//         Debugging query
//        $query->execute();
//        var_dump($query->errorInfo());
//        return false;
        return $query->execute();
    }

    public function getError($error){
        if(in_array($error, $this->errorArr)){
            return "<span class='errorMessage'>$error</span><br>";
        }
    }

    private function validateFirstName($fName){
        if(strlen($fName) < 2 || strlen($fName) > 50){
            array_push($this->errorArr, Constants::$firstNameCharacters);
        }
    }

    private function validateLastName($lName){
        if(strlen($lName) < 2 || strlen($lName) > 50){
            array_push($this->errorArr, Constants::$lastNameCharacters);
        }
    }

    private function validateUsername($username){
        if(strlen($username) < 2 || strlen($username) > 50){
            array_push($this->errorArr, Constants::$usernameCharacters);
            return;   // not to make query
        }

        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:username;");
        $query->bindValue(":username", $username);
        $query->execute();
        if($query->rowCount() != 0){
            array_push($this->errorArr, Constants::$usernameTaken);
        }
    }

    private function validateEmails($email1, $email2){
        if ($email1 != $email2){
            array_push($this->errorArr, Constants::$emailDoNotMatch);
            return;
        }
        if (!filter_var($email1, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArr, Constants::$invalidEmail);
            return;
        }
        $query = $this->conn->prepare("SELECT * FROM users WHERE email=:email;");
        $query->bindValue(":email", $email1);
        $query->execute();
        if($query->rowCount() != 0){
            array_push($this->errorArr, Constants::$emailAlreadyInUse);
        }
    }

    private function validatePasswords($password1, $password2){
        if ($password1 != $password2){
            array_push($this->errorArr, Constants::$passwordsDoNotMatch);
            return;
        }
        if(strlen($password1) < 5 || strlen($password1) > 255){
            array_push($this->errorArr, Constants::$passwordLength);
        }
    }

    public function getUserInfo($username){
        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:username;");
        $query->bindValue(":username", $username);
        $query->execute();
        return $query->fetch();
    }
}
