<?php
namespace CmsIr\Product\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class Product extends Model
{
    protected $id;
    protected $name;
    protected $slug;
    protected $date;
    protected $url;
    protected $main_photo;
    protected $realization_id;
    protected $category_id;
    protected $description;
    protected $realization;
    protected $category;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : Inflector::slugify($this->name);
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->main_photo = (!empty($data['main_photo'])) ? $data['main_photo'] : null;
        $this->realization_id = (!empty($data['realization_id'])) ? $data['realization_id'] : null;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $realization
     */
    public function setRealization($realization)
    {
        $this->realization = $realization;
    }

    /**
     * @return mixed
     */
    public function getRealization()
    {
        return $this->realization;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $realization_id
     */
    public function setRealizationId($realization_id)
    {
        $this->realization_id = $realization_id;
    }

    /**
     * @return mixed
     */
    public function getRealizationId()
    {
        return $this->realization_id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $main_photo
     */
    public function setMainPhoto($main_photo)
    {
        $this->main_photo = $main_photo;
    }

    /**
     * @return mixed
     */
    public function getMainPhoto()
    {
        return $this->main_photo;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }


}