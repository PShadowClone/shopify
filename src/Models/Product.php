<?php

namespace Amr\Shopify\Models;


class Product extends BaseModel
{

    protected $fillable= [
        "Handle",
        "Title",
        "Body (HTML)",
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

    public function setOptions($options)
    {
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
    public function setVariants($variants){
        return  [
           [
               'sku' =>  get_attr($variants ,"variant_sku" ),
               'grams' =>  get_attr($variants ,"variant_grams" ),
               'inventory_quantity' =>  get_attr($variants ,"variant_inventory_qty" ),
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
    public function setImages($images){
        return [
                [
                    'src' => get_attr($images ,"image_src" ),
                    'position' => get_attr($images ,"image_position" ),
                    'alt' => get_attr($images ,"image_alt_text" )
                ]
        ];
    }

    public function setGoogle_shopping($googleShipping){
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



}