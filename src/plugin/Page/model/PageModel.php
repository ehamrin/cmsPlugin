<?php


namespace plugin\Page\model;


class PageModel
{
    public function __construct(){
        $this->conn = \Database::GetConnection();
    }


    /**
     * @param $id
     * @return Page
     * @throws \Exception
     */
    public function FindByURL($id){
        $stmt = $this->conn->prepare("SELECT * FROM page WHERE slug = ? LIMIT 1");
        $stmt->execute(array($id));

        if(!$stmt->rowCount()){
            throw new \Exception("Page not found");
        }
        $obj = $stmt->fetchObject();
        return new Page($obj->name, $obj->slug, $obj->content, $obj->id, $obj->project);
    }

    /**
     * @param $id
     * @return Page
     * @throws \Exception
     */
    public function FindByID($id){

        $stmt = $this->conn->prepare("SELECT * FROM page WHERE id = ? LIMIT 1");
        $stmt->execute(array($id));

        if(!$stmt->rowCount()){
            throw new \Exception("Page not found");
        }
        $obj = $stmt->fetchObject();
        return new Page($obj->name, $obj->slug, $obj->content, $obj->id, $obj->project);
    }

    /**
     * @return Page[]
     */
    public function FetchAll(){
        $ret = array();


        $stmt = $this->conn->prepare("SELECT * FROM page");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $ret[] = new Page($obj->name, $obj->slug, $obj->content, $obj->id, $obj->project);
        }
        return $ret;
    }

    public function FetchAllSlugs(){
        $ret = array();

        foreach($this->FetchAll() as $page){
            $ret[] = $page->GetSlug();
        }
        return $ret;
    }

    public function Save(Page $page){
        if($page->GetID() > 0){
            return $this->Update($page);
        }
        return $this->Create($page);
    }

    private function Create(Page $page){

        $stmt = $this->conn->prepare("INSERT INTO page (name, slug, project, content) VALUES(?,?,?,?)");
        $stmt->execute(array($page->GetName(), $page->GenerateSlug(), $page->GetProject(), $page->GetContent()));
        return $this->conn->lastInsertId();
    }

    private function Update(Page $page){
        $stmt = $this->conn->prepare("UPDATE page SET name=?, slug=?, project=?, content=? WHERE id = ?");
        $stmt->execute(array($page->GetName(), $page->GenerateSlug(), $page->GetProject(), $page->GetContent(), $page->GetID()));
        return $page->GetID();
    }

    public function Delete($id){
        if($id > 1){
            $stmt = $this->conn->prepare("DELETE FROM page WHERE id = ?");
            $stmt->execute(array($id));
        }

    }

    public function Install(){
        $this->conn->exec("
          CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `content` text COLLATE utf8_swedish_ci NOT NULL,
  `project` INT COLLATE utf8_swedish_ci NULL DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

INSERT INTO `page` (`name`, `slug`, `content`) VALUES
  ('Hello World', '', '<h1>Hello World();</h1>');

  ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");
    }

    public function Uninstall(){
        $this->conn->exec("
          DROP TABLE IF EXISTS `page`
        ");
    }

    public function IsInstalled(){
        try {
            $result = $this->conn->query("SELECT 1 FROM `page` LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }
}