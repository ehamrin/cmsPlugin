<?php
/**
 * Created by PhpStorm.
 * User: erikh_000
 * Date: 2016-01-18
 * Time: 17:59
 */

namespace plugin\Slider\model;

/**
 * @Table   ["slide"]
 */
class Slide extends \annotation\model\AnnotationModel
{

    /**
     * @Primary
     * @Column
     */
    private $id;

    /**
     * @Column
     * @Required    ["You must assign a name"]
     * @MaxLength   [50, "Name must be no longer than 50 characters"]
     */
    private $name;

    /**
     * @Column
     * @Required    ["You must assign a filename"]
     * @MaxLength   [100, "Filename must be no longer than 100 characters"]
     */
    private $filename;

    /**
     * @Column
     * @Default     ["CURRENT_TIMESTAMP"]
     * @Required    ["You must assign a datetime"]
     * @DbType      ["timestamp"]
     */
    private $created;

    /**
     * @Column
     * @Required    ["You must choose an alignment"]
     * @Default     ["center"]
     */
    private $alignment = "center";

    public function getName(){ return $this->name; }
    public function setName($name){ $this->name = $name; }
    public function getAlignment(){ return $this->alignment; }
    public function setAlignment($alignment){ $this->alignment = $alignment; }
    public function getFilename(){ return $this->filename; }
    public function getPublicFilename(){ return "/image/Slider/" . $this->filename; }
    public function getCreated(){ return $this->created; }
    public function getId(){ return $this->id; }

    public function uploadFile(){

        try {

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['slide']['error']) ||
                is_array($_FILES['slide']['error'])
            ) {
                throw new \RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['slide']['error'] value.
            switch ($_FILES['slide']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['slide']['size'] > 1000000) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES['slide']['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )) {
                throw new \RuntimeException('Invalid file format.');
            }

            // You should name it uniquely.
            // DO NOT USE $_FILES['slide']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            $filename = sha1_file($_FILES['slide']['tmp_name']) . '.' . $ext;

            $this->removeFile();

            if (!move_uploaded_file(
                $_FILES['slide']['tmp_name'],
                $this->getDirectory() . $filename
            )) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }

            chmod($this->getDirectory() . $filename, 775);

            $this->filename = $filename;
            return true;

        } catch (\RuntimeException $e) {
            $this->_modelErrors['filename'] = $e->getMessage();
            return false;
        }
    }

    public function removeFile(){
        if(is_file($this->getDirectory() . $this->filename)){
            unlink($this->getDirectory() . $this->filename);
        }
    }

    private function getDirectory(){
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "image" . DIRECTORY_SEPARATOR;
    }
}