<?php

/**
 * Remove Menu for User 'demo' and 'asisten'
 */

function remove_menus()
{
    global $menu;
    global $current_user;
    get_currentuserinfo();

    if($current_user->user_login == 'demo' || $current_user->user_login == 'asisten')
    {
        $restricted = array(__('Posts'),
                            __('Media'),
                            __('Links'),
                            __('Pages'),
                            __('Comments'),
                            __('Appearance'),
                            __('Plugins'),
                            __('Users'),
                            __('Tools'),
                            __('New'),
                            __('Settings')
        );
        end ($menu);
        while (prev($menu)){
            $value = explode(' ',$menu[key($menu)][0]);
            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
        }// end while

    }// end if
}
add_action('admin_menu', 'remove_menus');

function wp_dental_admin_head_stuff() {

  
    global $menu;
    global $current_user;
    get_currentuserinfo();

    if($current_user->user_login == 'demo' || $current_user->user_login == 'asisten')
    { ?>

	<style>
    p#wp-version-message, li.post-count, li.comment-count, #wpadminbar li#wp-admin-bar-comments, #wpadminbar li#wp-admin-bar-new-post, #wpadminbar li#wp-admin-bar-new-media { display: none;}
	</style>


  <?php 
  }// end if
}

add_action('admin_head', 'wp_dental_admin_head_stuff');
add_action('wp_head', 'wp_dental_admin_head_stuff');

/**
 * Hide Comment status (uncheck on screen option)  for WP Dental
 */
function wpdental_hidden_meta_boxes( $hidden ) {
    $hidden[] = 'commentstatusdiv';
    return $hidden;
}

add_filter( 'hidden_meta_boxes', 'wpdental_hidden_meta_boxes' );

/**
 * Exclude List Comments for WP Dental
 */
 function exclude_comment_list($clauses) {
	global $post_type, $wpdb;
        if (is_admin() || !('wpdental' == $post_type)) {  
		//global $wpdb;

		if ( ! $clauses['join'] ) {
			$clauses['join'] = '';
		}

		if ( ! strstr( $clauses['join'], "JOIN $wpdb->posts" ) ) {
			$clauses['join'] .= " LEFT JOIN $wpdb->posts ON comment_post_ID = $wpdb->posts.ID ";
		}

		if ( $clauses['where'] ) {
			$clauses['where'] .= ' AND ';
		}

		$clauses['where'] .= " $wpdb->posts.post_type <> 'wpdental' ";

	}
		return $clauses;


};

add_filter('comments_clauses', 'exclude_comment_list');


/**
 * Add the title to our admin area, for editing, etc
 */
function pmg_comment_tut_add_meta_box()
{
    add_meta_box( 'pmg-comment-title', __( 'Diagnosa dan Tarif' ), 'pmg_comment_tut_meta_box_cb', 'comment', 'normal', 'high' );
}

function pmg_comment_tut_meta_box_cb( $comment )
{
    $diagnosa = get_comment_meta( $comment->comment_ID, '_diagnosa', true );
    $terapi = get_comment_meta( $comment->comment_ID, '_terapi', true );
    wp_nonce_field( 'pmg_comment_update', 'pmg_comment_update', false );
    ?>

<table class="form-table editcomment">
<tbody>
<tr>
	<td class="diagnosa">Diagnosa :</td>
	<td><input type="text" name="_diagnosa" value="<?php echo esc_attr( $diagnosa ); ?>" class="widefat" /></td>
</tr>
<tr>
	<td class="tarif">Terapi :</td>
	<td><input type="text" name="_terapi" value="<?php echo esc_attr( $terapi ); ?>" class="widefat" /></td>
</tr>
</tbody>
</table>

    <?php
}

add_action( 'add_meta_boxes_comment', 'pmg_comment_tut_add_meta_box' );

/**
 * Save our comment (from the admin area)
 */
function pmg_comment_tut_edit_comment( $comment_id )
{
    if( ! isset( $_POST['pmg_comment_update'] ) || ! wp_verify_nonce( $_POST['pmg_comment_update'], 'pmg_comment_update' ) ) return;
    if( isset( $_POST['_diagnosa'] ) )
        update_comment_meta( $comment_id, '_diagnosa', esc_attr( $_POST['_diagnosa'] ) );
    if( isset( $_POST['_terapi'] ) )
        update_comment_meta( $comment_id, '_terapi', esc_attr( $_POST['_terapi'] ) );
}

add_action( 'edit_comment', 'pmg_comment_tut_edit_comment' );

/**
 * Save our title (from the front end)
 */
function pmg_comment_tut_insert_comment( $comment_id )
{
    if( isset( $_POST['_diagnosa'] ) )
        update_comment_meta( $comment_id, '_diagnosa', esc_attr( $_POST['_diagnosa'] ) );
    if( isset( $_POST['_terapi'] ) )
        update_comment_meta( $comment_id, '_terapi', esc_attr( $_POST['_terapi'] ) );
}

add_action( 'comment_post', 'pmg_comment_tut_insert_comment', 10, 1 );

function my_plugin_comment_template( $comment_template ) {
     global $post;
     if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
        return;
     }
     if($post->post_type == 'wpdental'){ // assuming there is a post type called business
        return dirname(__FILE__) . '/visite.php';
     }
}

add_filter( "comments_template", "my_plugin_comment_template" );

function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'wpdental') {
          $single_template = dirname( __FILE__ ) . '/view.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );

// Custom column Display
add_filter( 'manage_edit-wpdental_columns', 'edit_wpdental_columns' ) ;

function edit_wpdental_columns( $columns ) {
    $columns = array(
        'noreg' => __( 'No. Registrasi' ),
        'name' => __( 'Nama Pasien' ),
        'address' => __('Alamat' ),
        'phone' => __('Telepon')
    );
    return $columns;
}

// What to display in each list column
add_action( 'manage_wpdental_posts_custom_column', 'manage_wpdental_columns', 10, 2 );

function manage_wpdental_columns( $column, $post_id ) {

    // Get meta if exists
    $noreg = get_the_title();
    $name = get_post_meta( $post_id, 'name', true );
    $address = get_post_meta( $post_id, 'address', true );
    $phone = get_post_meta( $post_id, 'phone', true );

    switch( $column ) {

        case 'noreg' :
            if ( empty( $noreg ) ) {
                echo __( 'Unknown' );
            } else {
                echo '<a href="/wp-admin/post.php?post='.$post_id.'&action=edit">' . $noreg . '</a>';
            }
            break;
        case 'name' :
            if ( empty( $name ) ) {
                echo __( 'Unknown' );
            } else {
                echo '<a href="'; ?> <?php the_permalink(); ?> <?php echo '">' . $name . '</a>';
            }
            break;

        case 'address' :
            if ( empty( $address ) ) {
                echo __( 'Unknown' );
            } else {
                echo $address;
            }
            break;

        case 'phone' :
            if ( empty( $phone ) ) {
                echo __( 'Unknown' );
            } else {
                echo $phone;
            }
            break;
    }

}

// Which columns are filterable
add_filter( 'manage_edit-wpdental_sortable_columns', 'wpdental_sortable_columns' );

function wpdental_sortable_columns( $columns ) {
    $columns['noreg'] = 'noreg';
    $columns['name'] = 'name';
    $columns['address'] = 'address';
    $columns['phone'] = 'phone';
    return $columns;
}

function wpdental_default_title( $title ){
     $screen = get_current_screen();
     if  ( $screen->post_type == 'wpdental' ) {
          return 'Nomor Registrasi Pasien';
     }
} 
add_filter( 'enter_title_here', 'wpdental_default_title' );

function wpdental_move_metabox() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes['wpdental']['advanced']);

}
add_action('edit_form_after_title', 'wpdental_move_metabox');

function wpdental_scripts() {
    // Enqueue Datepicker + jQuery UI CSS
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', true);
    wp_enqueue_style( 'wpdental-css', plugins_url() . '/wpdental/assets/wpdental.css' );
    // Load Custom Js Effects
    wp_enqueue_script( 'jquery-color-picker-js', plugins_url() . '/wpdental/assets/jquery.colorPicker.js' );
    wp_enqueue_script( 'wpdental-js', plugins_url() . '/wpdental/assets/wpdental.js' );
}
add_action( 'wp_enqueue_scripts', 'wpdental_scripts' );
add_action( 'admin_init', 'wpdental_scripts' );

function wpdental_move_publish_metabox(){
    remove_meta_box( 'submitdiv', 'wpdental', 'side' ); 
    add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', 'wpdental', 'side', 'low');
} 

add_action('do_meta_boxes', 'wpdental_move_publish_metabox');

function wpdental_change_publish_button( $translation, $text ) {

    if ( 'wpdental' == get_post_type())
      if ( $text == 'Publish' )
      return 'Input Data';

   return $translation;
}

add_filter( 'gettext', 'wpdental_change_publish_button', 10, 2 );

function wpdental_hide_minor_publishing() {
    $screen = get_current_screen();

    if( in_array( $screen->id, array( 'wpdental' ) ) ) {
        echo '<style>#minor-publishing { display: none; }</style>';
    }
}

add_action( 'admin_head', 'wpdental_hide_minor_publishing' );

function wpdental_general_setting_nama()
{
    register_setting('general', 'wpdental_setting_nama', 'esc_attr');
    add_settings_field('wpdental_setting_nama', '<label for="wpdental_setting_nama">'.__('Nama Praktek' , 'wpdental_setting_nama' ).'</label>' , 'wpdental_general_setting_nama_html', 'general');
}
 
function wpdental_general_setting_nama_html()
{
    $wpdental_nama = get_option( 'wpdental_setting_nama', '' );
    echo '<input type="text" id="wpdental_setting_nama" name="wpdental_setting_nama" value="' . $wpdental_nama . '" class="regular-text" />';
}

add_filter('admin_init', 'wpdental_general_setting_nama');

function wpdental_general_setting_alamat()
{
    register_setting('general', 'wpdental_setting_alamat', 'esc_attr');
    add_settings_field('wpdental_setting_alamat', '<label for="wpdental_setting_alamat">'.__('Alamat Praktek' , 'wpdental_setting_alamat' ).'</label>' , 'wpdental_general_setting_alamat_html', 'general');
}
 
function wpdental_general_setting_alamat_html()
{
    $wpdental_alamat = get_option( 'wpdental_setting_alamat', '' );
    echo '<input type="text" id="wpdental_setting_alamat" name="wpdental_setting_alamat" value="' . $wpdental_alamat . '" class="widefat" />';
}

add_filter('admin_init', 'wpdental_general_setting_alamat');

function custom_glance_items( $items = array() ) {
    $post_types = array( 'wpdental' );
    foreach( $post_types as $type ) {
        if( ! post_type_exists( $type ) ) continue;
        $num_posts = wp_count_posts( $type );
        if( $num_posts ) {
            $published = intval( $num_posts->publish );
            $post_type = get_post_type_object( $type );
            $text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'wpdental' );
            $text = sprintf( $text, number_format_i18n( $published ) );
            if ( current_user_can( $post_type->cap->edit_posts ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
                echo '<li class="page-count ' . $post_type->name . '-count">' . $output . '</li>';
            } else {
            $output = '<span>' . $text . '</span>';
                echo '<li class="page-count ' . $post_type->name . '-count">' . $output . '</li>';
            }
        }
    }
    return $items;
}

add_filter( 'dashboard_glance_items', 'custom_glance_items', 10, 1 );

?>
