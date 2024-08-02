<?php

namespace Amr\Shopify\Models;

abstract class BaseModel
{
    /**
     * object's attributes' names
     * @var array
     */
    protected $fillable = [];
    /**
     * core data of object attributes
     * @var array
     */
    protected $data = [];

    /**
     * fill object with the given attributes and values
     * @note: Clean parameter means that the filled data array will be sensitized lately
     * @param $attribute
     * @param $clean
     * @return $this
     */
    public function fill($attribute, $clean = false)
    {
        foreach ($this->fillable as $index => $value) {
            // adjust the attribute name
            $key = str_replace(' ', '_', strtolower($value));
            //set attribute value
            $this->{$key} = $attribute;
        }
        if ($clean)
            $this->cleanObject($this->data);
        return $this;
    }

    /**
     * push the given product to the shopify store
     * @param $client
     * @return void
     */
    public function save($client)
    {
        try {
            // Send POST request to create the product
            $response = $client->post('/admin/api/2023-04/products.json', [
                'json' => [
                    'product' => $this->data
                ],
            ]);

            // Get the response body
            $responseBody = json_decode($response->getBody(), true);

            // Print the response
            echo "{$this->title} created successfully:<br/>";
        } catch (\Exception $exception) {
            var_dump($exception);
            die();
        }
    }

    /**
     * php magic method that holds the set attributes operation
     * @param $name
     * @param $attributes
     * @return void
     */
    public function __set($name, $attributes)
    {
        // set method for each attribute
        $setMethod = "set" . ucfirst($name);
        // if the method is existed, it means that you want to override
        // the normal insertion of attribute
        if (method_exists($this, $setMethod)) {
            $this->data[$name] = $this->$setMethod($attributes);
        } else {
            $this->data[$name] = $attributes[$name];
        }
    }

    /**
     * read attribute direct from object
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return get_attr($this->data, $name);
    }

    /**
     * remove all unnecessary values
     * @param $data
     * @return void
     */
    public function cleanObject(&$data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->cleanObject($value);
            }
            if ($value == null || $value == ''|| $value == 'N/A') {
                unset($data[$key]);
            }
        }
    }

    /**
     * get object's data
     * @return array|mixed
     */
    public function getData(){
        return $this->data;
    }

    /**
     * customize the object printing operation
     * @return false|string
     */
    public function __toString(){
        return json_encode($this->data);
    }
}