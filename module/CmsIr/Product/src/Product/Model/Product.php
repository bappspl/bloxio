<?php
namespace CmsIr\Product\Model;

use CmsIr\System\Model\Model;

class Product extends Model
{
    protected $id;
    protected $name;
    protected $slug;
    protected $date;
    protected $url;
    protected $main_photo;
    protected $client_id;
    protected $category_id;
    protected $description;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->date = (!empty($data['date'])) ? $data['date'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
        $this->main_photo = (!empty($data['main_photo'])) ? $data['main_photo'] : null;
        $this->client_id = (!empty($data['client_id'])) ? $data['client_id'] : null;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
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
     * @param mixed $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
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