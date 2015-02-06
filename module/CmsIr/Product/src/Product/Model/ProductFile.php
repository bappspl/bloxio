<?php
namespace CmsIr\Product\Model;

use CmsIr\System\Model\Model;

class ProductFile extends Model
{
    protected $id;
    protected $filename;
    protected $product_id;
    protected $size;
    protected $mime_type;

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->filename = (!empty($data['filename'])) ? $data['filename'] : null;
        $this->product_id = (!empty($data['product_id'])) ? $data['product_id'] : null;
        $this->size = (!empty($data['size'])) ? $data['size'] : null;
        $this->mime_type = (!empty($data['mime_type'])) ? $data['mime_type'] : null;
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
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $mime_type
     */
    public function setMimeType($mime_type)
    {
        $this->mime_type = $mime_type;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }
}