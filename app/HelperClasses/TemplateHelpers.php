<?php 

namespace App\HelperClasses;


class TemplateHelpers {

    public function __construct() {


    
    }



    public static function getTemplateVarsArray() {
    
        $variables = [

                "{author_1}",
                "{author_4}",
                "{heading1_product}",
                "{product_1_title}",
                "{product_1}",
                "{rel_post_url}"
            
        ];


        return $variables;

    
    }


}


