<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;




class FakeApiController extends Controller {
    //

    // Auth::user()


    public function getPostTypes(Request $request) {
    


        $data = array(
            'success' => true,
            'data' => array(
                'post_types' => array('post','page','product')
            )
        );
        
        // respond with $data as json
        return response()->json($data);


    }


    // write a public function which respond with json data in the following format


    public function getPostsByType(Request $request) {


        $post_type = $request->input('post_type');

        $isTrue = $post_type == "post";

        if(!$isTrue) {
            $data = array(
                'success' => false,
                'data' => array(
                    'posts' => array()
                
                )
            );
    
    
            // respond with $data as json
            return response()->json($data);
        }


        $posts = array(

            array(
                'id' => 18,
                'title' => 'Lorem Ipsum {author_1} vs {author_4} dolor craft',
            ),

            array(
                'id' => 85,
                'title' => 'Lorem Ipsum EJ Ahmad vs KFC Pro dolor craft',
            ),

            array(
                'id' => 84,
                'title' => 'Test Template ID',
            ),

            array(
                'id' => 88,
                'title' => 'Lorem Ipsum {author_1} vs {author_4} dolor craft',
            ),

        );



        $data = array(

            'success' => true,
            "data" => array(
                "posts" => $posts,
            )
        
        );


        // respond with $data as json
        return response()->json($data);


    }



    public function getTemplateVarsById(Request $request) {

        $template_id = $request->input('post_id');

        $variables = array();

        if($template_id == "18") {

            $variables = [

                "{author_1}",
                "{author_4}",
                "{heading1_product}",
                "{product_1_title}",
                "{product_1}",
                "{rel_post_url}"
            
            ];
        
        }

        // dd($variables);

        return response()->json(array(

            'success' => true,
            'data' => array(
                'variables' => $variables
            )

        ));



    }



    public function validateAuthKey(Request $request) {


        $auth_key = $request->query('auth_key');

        if($auth_key == "6f25c51ea81e9372cdda2ee") {

            return response()->json(array(

                'success' => true,
                "data" => array(
                    'auth_key' => $auth_key,
                    'site_name' => 'Testing Wordpress website'
                )
            ));
            
        
        } 


        return response()->json(array(
            'success' => false,
            'data' => array(
                'error' => "Invalid auth key"
            )
        ));
    
    
    }



}