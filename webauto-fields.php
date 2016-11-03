<?php

/*
Plugin Name: Webauto Fields
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Vlastné polia k testom (technické údaje, ceny...)
Version: 1.0
Author: miso
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/


function enqueue_my_scripts()
{
    wp_enqueue_script('script1', plugin_dir_url(__FILE__) . 'js/script.js');
}

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";

    if(isset($_POST["meta-box-text1"]))
    {
        $meta_box_text_value = $_POST["meta-box-text1"];
    }
    update_post_meta($post_id, "meta-box-text1", $meta_box_text_value);



}

add_action("save_post", "save_custom_meta_box", 10, 3);



function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Technické údaje", "custom_meta_box_markup", "post", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");
add_action("add_meta_boxes", "enqueue_my_scripts");

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>

    <style>
        #webauto-fields div {
            padding: 5px 0;

        }

        #webauto-fields label, #webauto-fields input {
            height: 25px;
            vertical-align: middle;
        }
    </style>
    <div id="webauto-fields">
        <div id="TextBoxDiv1">
            <input type="text" value="Label #1">  :
            <input name="meta-box-text1" type="text"
                   value="<?php echo get_post_meta($object->ID, "meta-box-text1", true); ?>">


        </div>

    </div>
    <input type='button' value='Add Button' id='addButton'>
    <input type='button' value='Remove Button' id='removeButton'>



    <?php
}