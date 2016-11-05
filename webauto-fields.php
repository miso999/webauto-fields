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


function enqueue_my_scripts() {

    wp_enqueue_script('script1', plugin_dir_url(__FILE__) . 'js/script.js');
}

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Technické údaje", "custom_meta_box_markup", "post", "normal", "high", null);
}

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    $labels=array();
    $meta = get_post_meta(get_the_ID(), '', true);
    // Get all label in array $meta
    foreach ($meta as $key => $value) {
        if (strpos($key, 'label') === 0) {
            $labels[$key] = $value;
        }
    }

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
    <div id="out"></div>
    <div id="webauto-fields">
    <?php

    if (count($labels))
    foreach ($labels as $key => $value) {

       $index=array_search($key, array_keys($labels));
        $index+=1;
        ?>

        <div id="TextBoxDiv<?=$index?>">
            <input type="text" name="label<?=$index?>" value="<?php echo get_post_meta($object->ID, "label".$index, true); ?>">  :
            <input name="textbox<?=$index?>" type="text" value="<?php echo get_post_meta($object->ID, "textbox".$index, true); ?>">

        </div>

        <?php

    }
    ?>



    </div>
    <input type='button' value='+' id='addButton'>
    <input type='button' value='-' id='removeButton'>



<?php
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

//TODO
    foreach ($_POST as $post => $value) {

        update_post_meta($post_id, $post, $value);
    }

}

function remove_custom_field_meta_box()
{
    remove_meta_box("postcustom", "post", "normal");
}

add_action("do_meta_boxes", "remove_custom_field_meta_box");
add_action("add_meta_boxes", "enqueue_my_scripts");
add_action("add_meta_boxes", "add_custom_meta_box");
add_action("save_post", "save_custom_meta_box", 10, 3);








