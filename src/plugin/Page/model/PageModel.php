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
        return $stmt->fetchObject('\\plugin\\Page\\model\\Page');
        //return new Page($obj->name, $obj->slug, $obj->content, $obj->id, $obj->project);
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
        return $stmt->fetchObject('\\plugin\\Page\\model\\Page');
    }

    /**
     * @return Page[]
     */
    public function FetchAll(){
        $ret = array();


        $stmt = $this->conn->prepare("SELECT * FROM page");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, '\\plugin\\Page\\model\\Page');
        /*while($obj = $stmt->fetchObject()){
            $ret[] = new Page($obj->name, $obj->slug, $obj->content, $obj->id, $obj->project);
        }
        return $ret;*/
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

        $stmt = $this->conn->prepare("INSERT INTO page (name, slug, project, content, template, module) VALUES(?,?,?,?,?,?)");
        $stmt->execute(array($page->GetName(), $page->GenerateSlug(), $page->GetProject(), $page->GetContent(), $page->GetTemplate(), $page->GetModule()));
        return $this->conn->lastInsertId();
    }

    private function Update(Page $page){
        $stmt = $this->conn->prepare("UPDATE page SET name=?, slug=?, project=?, content=?, template=?, module=? WHERE id = ?");
        $stmt->execute(array($page->GetName(), $page->GenerateSlug(), $page->GetProject(), $page->GetContent(), $page->GetTemplate(), $page->GetModule(), $page->GetID()));
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
  `project` INT COLLATE utf8_swedish_ci NULL DEFAULT NULL,
  `template` varchar(100) COLLATE utf8_swedish_ci NULL DEFAULT 'full-width',
  `module` varchar(100) COLLATE utf8_swedish_ci NULL DEFAULT ''

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

INSERT INTO `page` (`name`, `slug`, `content`, `template`, `module`) VALUES
  ('Hello World', '', '<h1>Hello World();</h1>', 'full-width', '');

  ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
        ");
    }
}