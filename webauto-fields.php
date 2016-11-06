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

function deleteBox($id) {
    global $post_id;
    delete_post_meta($post_id, $id);
    $textbox=str_replace('label','textbox',$id);
    delete_post_meta($post_id, $textbox);
}

function enqueue_my_scripts() {

    wp_enqueue_script('webauto-fields', plugin_dir_url(__FILE__) . 'js/script.js');
    wp_enqueue_style('webauto-fields', plugin_dir_url(__FILE__) . 'style.css');
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
    <div id="webauto-fields">
    <?php

    if (count($labels))
    foreach ($labels as $label => $value) {

        $textbox=str_replace('label','textbox',$label);
        preg_match('/label([\d]+)/',$label,$in);
        $in=$in[1];
        ?>

        <div id="TextBoxDiv<?=$in?>">
            <input type="text" name="<?=$label?>" value="<?php echo get_post_meta($object->ID, $label, true); ?>">  :
            <input type="text" name="<?=$textbox?>" value="<?php echo get_post_meta($object->ID, $textbox, true); ?>">
            <button role="presentation" type="button" class="removeButton" id="<?=$in?>">x</button>
        </div>

        <?php

    }
    ?>
        <div id="deleted" style="display: none;"></div>
    </div>
    <input type='button' value='+' id='addButton'>




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


    foreach ($_POST as $post => $value) {

        if($value=="DeleteThisCustomMeta") deleteBox($post);
        else update_post_meta($post_id, $post, $value);
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








