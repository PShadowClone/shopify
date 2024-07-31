<?php

namespace Amr\Shopify\Models;

abstract class BaseModel
{
    protected $fillable = [];
    protected $data = [];

    public function fill($attribute)
    {
        foreach ($this->fillable as $index => $value) {
            $key = str_replace(' ', '_', strtolower($value));
            $setMethod = "set".ucfirst($key);
            if(method_exists($this,$setMethod) ){
                $this->data[$key] = $this->$setMethod($attribute);;
            }else{
                $this->data[$key] = $attribute[$key];
            }
        }
//        $this->setUpRelation();
        return $this;
    }

//    public abstract function setUpRelation();


    public function save($client)
    {
        try{
        // Send POST request to create the product
        $response = $client->post('/admin/api/2023-04/products.json', [
            'json' => [
                'product' => $this->data
            ] ,
        ]);

        // Get the response body
        $responseBody = json_decode($response->getBody(), true);

        // Print the response
        echo "{$this->title} created successfully:\n";
        }catch (\Exception $exception){
            var_dump($exception);
            die();
        }
    }
    public function __get($name)
    {
       return get_attr($this->data , $name);
    }
}