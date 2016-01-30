<?php


namespace app\Logger\model;


class LoggerModel
{
    public function __construct(){
        $this->conn = \Database::GetConnection();
    }

    public function logException(\Exception $e){

        $stmt = $this->conn->prepare("INSERT INTO exception_logger (date, type, exception, session, ip) VALUES(?,?,?,?,?)");
        $stmt->execute(array(date('Y-m-d H:i:s'), get_class($e), $e, session_id(), $_SERVER['REMOTE_ADDR']));

    }

    public function logVisitor($username){


        $stmt = $this->conn->prepare("INSERT INTO visitor_logger (date, user, info, session) VALUES(?,?,?,?)");
        $stmt->execute(array(date('Y-m-d H:i:s'), $username, serialize($_SERVER), session_id()));

    }

    public function fetchAllError(){
        $ret = array();


        $stmt = $this->conn->prepare("SELECT * FROM exception_logger  ORDER BY date DESC LIMIT 100");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $ret[] = $obj;
        }
        return $ret;
    }

    public function fetchAllVisitor(){
        $ret = array();


        $stmt = $this->conn->prepare("SELECT * FROM visitor_logger ORDER BY date DESC  LIMIT 100");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $obj->info = unserialize($obj->info);
            $ret[] = $obj;
        }
        return $ret;
    }

    public function fetchVisitorSummary(){
        $ret = array();

        $stmt = $this->conn->prepare("
            SELECT
            vl.date,
            vl.info,
            vl.session,
            (SELECT count(*)
              FROM visitor_logger
              WHERE session = vl.session
            ) as count,
            (SELECT user
              FROM visitor_logger
              WHERE session = vl.session
              ORDER BY date DESC
              LIMIT 1
            ) as user
            FROM visitor_logger as vl GROUP BY session ORDER BY date DESC
");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $obj->visited = array();
            $obj->info = unserialize($obj->info);
            $visited = $this->conn->prepare("SELECT * FROM visitor_logger WHERE session = ?  ORDER BY date DESC ");
            $visited->execute(array($obj->session));
            while($visit = $visited->fetchObject()){
                $visit->info = unserialize($visit->info);
                $obj->visited[] = $visit;
            }

            $ret[] = $obj;
        }
        return $ret;
    }

    public function Install(){
        $this->conn->exec("
          CREATE TABLE IF NOT EXISTS `exception_logger` (
  `id` int(11) NOT NULL,
  `date` DATETIME COLLATE utf8_swedish_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `exception` text COLLATE utf8_swedish_ci NOT NULL,
  `session` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `ip` varchar(100) COLLATE utf8_swedish_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

  ALTER TABLE `exception_logger`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `exception_logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  CREATE TABLE IF NOT EXISTS `visitor_logger` (
  `id` int(11) NOT NULL,
  `date` DATETIME COLLATE utf8_swedish_ci NOT NULL,
  `info` text COLLATE utf8_swedish_ci NOT NULL,
  `user` varchar(100) COLLATE utf8_swedish_ci NULL DEFAULT NULL,
  `session` varchar(100) COLLATE utf8_swedish_ci NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

  ALTER TABLE `visitor_logger`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `visitor_logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");

    }

    public function Uninstall(){
        $this->conn->exec("
          DROP TABLE IF EXISTS `exception_logger`;
          DROP TABLE IF EXISTS `visitor_logger`;
        ");
    }

    public function IsInstalled(){
        try {
            $result = $this->conn->query("SELECT 1 FROM `exception_logger` LIMIT 1");

            if($result !== FALSE){
                $result = $this->conn->query("SELECT 1 FROM `visitor_logger` LIMIT 1");
            }

        } catch (\Exception $e) {
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

}