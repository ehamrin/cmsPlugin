<?php


namespace plugin\Authentication\model;


class UserModel
{
    private $conn;

    public function __construct(){
        $this->conn = \Database::GetConnection();
    }

    /**
     * @param string $username
     * @return User
     * @throws \Exception
     */
    public function FindByUsername($username){
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = ? LIMIT 1");
        $stmt->execute(array($username));

        if(!$stmt->rowCount()){
            throw new \Exception("User not found");
        }
        $obj = $stmt->fetchObject();
        return new User($obj->username, $obj->password);
    }

    /**
     * @param int $id
     * @return User
     * @throws \Exception
     */
    public function FindByID($id){
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE id = ? LIMIT 1");
        $stmt->execute(array($id));

        if(!$stmt->rowCount()){
            throw new \Exception("User not found");
        }
        $obj = $stmt->fetchObject();
        return new User($obj->username, $obj->password, $obj->id);
    }

    /**
     * @return User[]
     */
    public function FetchAll(){
        $ret = array();

        $stmt = $this->conn->prepare("SELECT * FROM user");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $ret[] = new User($obj->username, $obj->password, $obj->id);
        }
        return $ret;
    }

    public function Save(User $user){
        if($user->GetID() > 0){
            return $this->Update($user);
        }
        return $this->Create($user);
    }

    private function Create(User $user){
        $stmt = $this->conn->prepare("INSERT INTO user (username, password) VALUES(?,?)");
        $stmt->execute(array($user->GetUsername(), $user->GetPassword()));
        return $this->conn->lastInsertId();
    }

    private function Update(User $user){
        $stmt = $this->conn->prepare("UPDATE user SET username=?, password=? WHERE id = ?");
        $stmt->execute(array($user->GetUsername(), $user->GetPassword(), $user->GetID()));
        return $user->GetID();
    }

    public function Delete($id){
        if($id > 1){

            $stmt = $this->conn->prepare("DELETE FROM user WHERE id = ?");
            $stmt->execute(array($id));
        }
    }


    /**
     * @param User $credential
     * @return bool
     */
    public function UserExists(User $credential){
        foreach($this->FetchAll() as $user){
            /* @var $user User */
            if($credential->GetUsername() == $user->GetUsername() && $credential->GetID() != $user->GetID()){
                return true;
            }
        }
        return false;
    }

    public function Login(LoginCredential $credential){
        foreach($this->FetchAll() as $user){
            /* @var $user User */
            if($credential->GetUsername() == $user->GetUsername() && password_verify($credential->GetPassword(), $user->GetPassword())){
                $_SESSION["admin"] = $user;
            }
        }
        return $this->IsLoggedIn();
    }


    /**
     * @return bool
     */
    public function IsLoggedIn(){
        return isset($_SESSION["admin"]);
    }

    public function Logout(){
        unset($_SESSION["admin"]);
    }

    /**
     * @return User
     */
    public static function GetLoggedInUser() : User
    {
        return $_SESSION['admin'];
    }

    public function Install(){
        $this->conn->exec('
          CREATE TABLE IF NOT EXISTS `user` (
              `id` int(11) NOT NULL,
              `username` varchar(100) NOT NULL,
              `password` varchar(100) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

          CREATE TABLE IF NOT EXISTS `user_permission` (
              `user` int(11) NOT NULL,
              `permission` varchar(100) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

          ALTER TABLE `user`
          ADD PRIMARY KEY (`id`);

          ALTER TABLE `user`
           MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ');
    }

    public function Uninstall(){
        $this->conn->exec('
          DROP TABLE IF EXISTS `user`
        ');
    }

    public function IsInstalled(){
        try {
            $result = $this->conn->query("SELECT 1 FROM user LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }
}