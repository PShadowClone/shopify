<?php

namespace Amr\Shopify\Models;


class Product extends BaseModel
{

    protected $fillable= [
        "Handle",
        "Title",
        "Body HTML",
        "Vendor",
        "Type",
        "Tags",
        "Published",
        "options",
        'variants',
        'images',
        'google_shopping',
        "Gift Card",
        "SEO Title",
        "SEO Description",
        "Cost per item"
    ];

    /**
     * set options values
     * @param $options
     * @return array
     */
    public function setOptions($options)
    {
        if(isset($options['options']))
            return $options['options'];
        $data = [];
        for ($index = 1 ; $index < 5; $index++){
            if(get_attr($options, "option{$index}_value") == '')
                continue;
            $data[] = [
                'name' => get_attr($options ,"option{$index}_name" ),
                'value' => get_attr($options, "option{$index}_value")
            ];
        }
        return $data;
    }

    public function setTitle($title){
        if(is_array($title))
            return $title['title'];
        $this->data['title'] .=  $this->data['title'].$title;
      return $this->data['title'];
    }

    /**
     * set variants values
     * @param $variants
     * @return array[]
     */
    public function setVariants($variants){
        if(isset($variants['variants']))
            return $variants['variants'];
        return  [
           [
               'sku' =>  get_attr($variants ,"variant_sku" ),
               'grams' =>  get_attr($variants ,"variant_grams" ),
               'inventory_quantity' =>  get_attr($variants ,"variant_inventory_qty" ),
               'inventory_management' => 'shopify', // to enable quantity tracking
               'inventory_policy' => get_attr($variants ,"variant_inventory_policy" ),
               'fulfillment_service' => get_attr($variants ,"variant_fulfillment_service" ),
               'price' => get_attr($variants ,"variant_price" ),
               'compare_at_price' => get_attr($variants ,"variant_compare_at_price" ),
               'requires_shipping' =>  get_attr($variants ,"variant_requires_shipping" ),
               'taxable' =>  get_attr($variants ,"variant_taxable" ),
               'barcode' =>  get_attr($variants ,"variant_barcode" ),
               'weight_unit' =>  get_attr($variants ,"variant_weight_unit" ),
               'tax_code' => get_attr($variants ,"variant_tax_code" ),
               'cost' => get_attr($variants ,"cost_per_item" ),
               'image' => [
                   'src' => get_attr($variants ,"variant_image" )
               ],
           ]
        ];
    }

    /**
     * set images values
     * @param $images
     * @return array[]
     */
    public function setImages($images){
        if(isset($images['images']))
            return $images['images'];
        return [
                [
                    'src' => get_attr($images ,"image_src" ),
                    'position' => get_attr($images ,"image_position" ),
                    'alt' => get_attr($images ,"image_alt_text" )
                ]
        ];
    }

    /**
     * set google shopping values
     * @param $googleShipping
     * @return array
     */
    public function setGoogle_shopping($googleShipping){
        if(isset($googleShipping['google_shopping']))
            return $googleShipping['google_shopping'];
        $keys  = [
            "google_shopping_/_google_product_category",
            "google_shopping_/_gender",
            "google_shopping_/_age_group",
            "google_shopping_/_mpn",
            "google_shopping_/_adwords_grouping",
            "google_shopping_/_adwords_labels",
            "google_shopping_/_condition",
            "google_shopping_/_custom_product",
            "google_shopping_/_custom_label_0",
            "google_shopping_/_custom_label_1",
            "google_shopping_/_custom_label_2",
            "google_shopping_/_custom_label_3",
            "google_shopping_/_custom_label_4",
        ];
        $data = [];
        foreach ($keys as $keyValue){
            $key = str_replace('google_shopping_/_' , '' , $keyValue);
            $data[$key] = get_attr($googleShipping ,$keyValue );
        }
       return $data;

    }

    /**
     * update all qty of items
     * @param $client
     * @param $locationId
     * @param $available
     * @return string|void
     */
    function updateInventoryLevel($client,$locationId, $available) {

        $endpoint = "inventory_levels/set.json";
        if (!$this->variants || sizeof($this->variants) ==0){
            return;
        }
       foreach ( $this->variants as $variant){
           if(!isset($variant['inventory_item_id'])){
               continue;
           }
           $inventory_item_id = $variant['inventory_item_id'];
           echo $inventory_item_id."<br/>";
           try{
               $response = $client->post($endpoint, [
                   'json' => [
                       'location_id' => $locationId,
                       'inventory_item_id' => $inventory_item_id,
                       'available' => $available
                   ]
               ]);
           }catch (\Exception $exception){

           }

       }
       return "Update qty with {$available}";
    }



}