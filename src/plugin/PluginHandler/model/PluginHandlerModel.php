<?php


namespace plugin\PluginHandler\model;


class PluginHandlerModel
{

    private $availablePlugins = array();

    public function __construct(){
        $this->conn = \Database::GetConnection();
    }

    public function InstalledPlugins(){
        $ret = array();


        $stmt = $this->conn->prepare("SELECT * FROM plugin");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $ret[] = $obj->name;
        }
        return $ret;
    }

    public function SetAvailablePlugins($plugins = array()){
        $this->availablePlugins = $plugins;
    }

    public function GetAvailablePlugins(){
        return $this->availablePlugins;
    }

    public function Install(){
        $this->conn->exec("
          CREATE TABLE IF NOT EXISTS `plugin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL
);

  ALTER TABLE `plugin`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `plugin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");
    }

    public function Uninstall(){
        $this->conn->exec("
          DROP TABLE IF EXISTS `plugin`
        ");
    }

    public function IsInstalled(){
        try {
            $result = $this->conn->query("SELECT 1 FROM plugin LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

    public function Save($data = array()){
        $this->conn->exec("DELETE FROM plugin");

        foreach($data as $key => $action){
            if($action == 'Install'){
                $stmt = $this->conn->prepare("INSERT INTO plugin (name) VALUES(?)");
                $stmt->execute(array($key));
            }
        }

    }
}