<?php

if (!function_exists('read_csv')) {
    function read_csv($path , $model)
    {
        $collection = [];
        $index  = 0;
        $keys = [];

        // Open the file for reading
        if (($handle = fopen($path, 'r')) !== FALSE) {
            // Loop through each line in the file
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if($index == 0){
                    $index++;
                    $keys = array_map(fn($value) => str_replace(' ', '_', strtolower($value)) , $data);
                    continue;
                }

                // Print out each line of the CSV file
                $collection[] = (new $model())->fill(array_combine($keys,$data));

            }
            // Close the file
            fclose($handle);
        } else {
            echo "Error opening the file.";
        }
        return $collection;
    }
}

if(!function_exists('get_attr')){
    function get_attr($arr , $key , $default = null){
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}
