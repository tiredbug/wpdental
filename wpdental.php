<?php
/* 
 * Plugin Name: WP Dental
 * Plugin URI: http://drg.id/
 * Description: A WordPres Dental Records Plugin!
 * Version: 0.1
 * Author: drg. F. Basoro
 * Author URI: http://drg.id/
 * License: MIT 
 */

// Flush rewrite rules on activation
function my_rewrite_flush() {

    register_wpdental();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );

include_once('functions.php');

// Create wpdental post type

function register_wpdental() {
    register_post_type( 'wpdental',
        array(
		'labels' => array(
                	'name' => __( 'Rekam Medik' ),
                	'singular_name' => __( 'Pasien' ),
                	'menu_name' => __( 'Rekam Medik' ),
                	'all_items' => __( 'Data Pasien' ),
               		'add_new' => __( 'Pasien Baru' ),
         		'add_new_item' => __( 'Pasien Baru' ),
                	'edit_item' => __( 'Ubah Detail' ),
                	'new_item' => __( 'Pasien Baru' ),
                	'view_item' => __( 'Lihat Pasien' ),
                	'search_items' => __( 'Cari Pasien' ),
                	'not_found' => __( 'Pasien Tidak Ditemukan' ),
                	'not_found_in_trash' => __( 'Catatan Pasien Tidak Ada Di Kotak Sampah' ),
                	'parent_item_colon' => __( 'Catatan Induk' ),
		  ),
	  	'public' => true,
         	'rewrite' => false,
         	'has_archive' => false,
         	'supports' => array('title', 'editor', 'comments', 'thumbnail'),
         	'menu_icon' => 'dashicons-portfolio'
        )
    );
}

add_action( 'init', 'register_wpdental' );

function wpdental_add_meta_box($post_type){
    $post_types = array("wpdental");
    if(in_array($post_type, $post_types)){
        add_meta_box("wpdental", "Data Pasien", "wpdental_meta_box_content", "wpdental", "advanced", "core");
        add_meta_box("general", "Keadaan Umum", "general_meta_box_content", "wpdental", "advanced", "core");
        add_meta_box("odontogram", "Odontogram", "odontogram_meta_box_content", "wpdental", "advanced", "core");
    }
}

add_action('add_meta_boxes', 'wpdental_add_meta_box');

function wpdental_save($post_id){
    if(!isset($_POST["wpdental_nonce"]))
        return $post_id;

    $nonce = $_POST["wpdental_nonce"];

    if(!wp_verify_nonce($nonce, "wpdental"))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

        $fields = array(
	  		'name'		=> sanitize_text_field($_POST["wpdental_name"]),
		  	'sex'		=> sanitize_text_field($_POST["wpdental_sex"]),
		  	'birthdate'	=> sanitize_text_field($_POST["wpdental_birthdate"]),
		  	'address'	=> sanitize_text_field($_POST["wpdental_address"]),
		  	'phone'		=> sanitize_text_field($_POST["wpdental_phone"]),
		  	'job'		=> sanitize_text_field($_POST["wpdental_job"]),

		  	'keluhan_utama'		=> sanitize_text_field($_POST["wpdental_keluhan_utama"]),
		  	'ps_hipertensi'		=> sanitize_text_field($_POST["wpdental_ps_hipertensi"]),
		  	'ps_diabetes'		=> sanitize_text_field($_POST["wpdental_ps_diabetes"]),
		  	'ps_jantung'		=> sanitize_text_field($_POST["wpdental_ps_jantung"]),
		  	'ps_ginjal'		=> sanitize_text_field($_POST["wpdental_ps_ginjal"]),
		  	'ps_hepatitis'		=> sanitize_text_field($_POST["wpdental_ps_hepatitis"]),
		  	'ps_tbc'		=> sanitize_text_field($_POST["wpdental_ps_tbc"]),
		  	'ps_aids'		=> sanitize_text_field($_POST["wpdental_ps_aids"]),
		  	'ps_pms'		=> sanitize_text_field($_POST["wpdental_ps_pms"]),
		  	'ps_hamil'		=> sanitize_text_field($_POST["wpdental_ps_hamil"]),
		  	'ps_lain_lain'		=> sanitize_text_field($_POST["wpdental_ps_lain_lain"]),
		  	'alergi_obat'		=> sanitize_text_field($_POST["wpdental_alergi_obat"]),
		  	'tekanan_darah'		=> sanitize_text_field($_POST["wpdental_tekanan_darah"]),
		  	'denyut_nadi'		=> sanitize_text_field($_POST["wpdental_denyut_nadi"]),
		  	'riwayat_cabut_gigi'		=> sanitize_text_field($_POST["wpdental_riwayat_cabut_gigi"]),

		  	'gg11'		=> sanitize_text_field($_POST["wpdental_gg11"]),
		  	'gg12'		=> sanitize_text_field($_POST["wpdental_gg12"]),
		  	'gg13'		=> sanitize_text_field($_POST["wpdental_gg13"]),
		  	'gg14'		=> sanitize_text_field($_POST["wpdental_gg14"]),
		  	'gg15'		=> sanitize_text_field($_POST["wpdental_gg15"]),
		  	'gg16'		=> sanitize_text_field($_POST["wpdental_gg16"]),
		  	'gg17'		=> sanitize_text_field($_POST["wpdental_gg17"]),
		  	'gg18'		=> sanitize_text_field($_POST["wpdental_gg18"]),

		  	'gg21'		=> sanitize_text_field($_POST["wpdental_gg21"]),
		  	'gg22'		=> sanitize_text_field($_POST["wpdental_gg22"]),
		  	'gg23'		=> sanitize_text_field($_POST["wpdental_gg23"]),
		  	'gg24'		=> sanitize_text_field($_POST["wpdental_gg24"]),
		  	'gg25'		=> sanitize_text_field($_POST["wpdental_gg25"]),
		  	'gg26'		=> sanitize_text_field($_POST["wpdental_gg26"]),
		  	'gg27'		=> sanitize_text_field($_POST["wpdental_gg27"]),
		  	'gg28'		=> sanitize_text_field($_POST["wpdental_gg28"]),

		  	'gg31'		=> sanitize_text_field($_POST["wpdental_gg31"]),
		  	'gg32'		=> sanitize_text_field($_POST["wpdental_gg32"]),
		  	'gg33'		=> sanitize_text_field($_POST["wpdental_gg33"]),
		  	'gg34'		=> sanitize_text_field($_POST["wpdental_gg34"]),
		  	'gg35'		=> sanitize_text_field($_POST["wpdental_gg35"]),
		  	'gg36'		=> sanitize_text_field($_POST["wpdental_gg36"]),
		  	'gg37'		=> sanitize_text_field($_POST["wpdental_gg37"]),
		  	'gg38'		=> sanitize_text_field($_POST["wpdental_gg38"]),

		  	'gg41'		=> sanitize_text_field($_POST["wpdental_gg41"]),
		  	'gg42'		=> sanitize_text_field($_POST["wpdental_gg42"]),
		  	'gg43'		=> sanitize_text_field($_POST["wpdental_gg43"]),
		  	'gg44'		=> sanitize_text_field($_POST["wpdental_gg44"]),
		  	'gg45'		=> sanitize_text_field($_POST["wpdental_gg45"]),
		  	'gg46'		=> sanitize_text_field($_POST["wpdental_gg46"]),
		  	'gg47'		=> sanitize_text_field($_POST["wpdental_gg47"]),
		  	'gg48'		=> sanitize_text_field($_POST["wpdental_gg48"]),

		  	'gg51'		=> sanitize_text_field($_POST["wpdental_gg51"]),
		  	'gg52'		=> sanitize_text_field($_POST["wpdental_gg52"]),
		  	'gg53'		=> sanitize_text_field($_POST["wpdental_gg53"]),
		  	'gg54'		=> sanitize_text_field($_POST["wpdental_gg54"]),
		  	'gg55'		=> sanitize_text_field($_POST["wpdental_gg55"]),

		  	'gg61'		=> sanitize_text_field($_POST["wpdental_gg61"]),
		  	'gg62'		=> sanitize_text_field($_POST["wpdental_gg62"]),
		  	'gg63'		=> sanitize_text_field($_POST["wpdental_gg63"]),
		  	'gg64'		=> sanitize_text_field($_POST["wpdental_gg64"]),
		  	'gg65'		=> sanitize_text_field($_POST["wpdental_gg65"]),

		  	'gg71'		=> sanitize_text_field($_POST["wpdental_gg71"]),
		  	'gg72'		=> sanitize_text_field($_POST["wpdental_gg72"]),
		  	'gg73'		=> sanitize_text_field($_POST["wpdental_gg73"]),
		  	'gg74'		=> sanitize_text_field($_POST["wpdental_gg74"]),
		  	'gg75'		=> sanitize_text_field($_POST["wpdental_gg75"]),

		  	'gg81'		=> sanitize_text_field($_POST["wpdental_gg81"]),
		  	'gg82'		=> sanitize_text_field($_POST["wpdental_gg82"]),
		  	'gg83'		=> sanitize_text_field($_POST["wpdental_gg83"]),
		  	'gg84'		=> sanitize_text_field($_POST["wpdental_gg84"]),
		  	'gg85'		=> sanitize_text_field($_POST["wpdental_gg85"])

        );

        foreach($fields as $k=>$v){
	    update_post_meta($post_id, $k, $v);
  	}
}

add_action( 'save_post', 'wpdental_save');

function wpdental_meta_box_content($post){
    wp_nonce_field("wpdental", "wpdental_nonce");

	$name = get_post_meta($post->ID, "name", true);
  	$sex = get_post_meta($post->ID, "sex", true);
  	$birthdate = get_post_meta($post->ID, "birthdate", true);
  	$address = get_post_meta($post->ID, "address", true);
  	$phone = get_post_meta($post->ID, "phone", true);
  	$job = get_post_meta($post->ID, "job", true);

  	?>
	  	<table class="form-table">
		  	<tr>
			  	<th><label for="wpdental_name"><?php _e("Name", "wpdental"); ?></label></th>
			  	<td><input type="text" name="wpdental_name" id="wpdental_name" value="<?php echo $name; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="wpdental_sex"><?php _e("Sex", "wpdental"); ?></label></th>
			  	<td><label for="jenis_kelamin_laki_laki">
        			  	<input type="radio" name="wpdental_sex" id="jenis_kelamin_laki_laki" value="Laki-Laki" <?php checked( $sex, 'Laki-Laki' ); ?>> Laki-Laki
    			  	</label>
    			  	<label for="jenis_kelamin_perempuan">
        			  	<input type="radio" name="wpdental_sex" id="jenis_kelamin_perempuan" value="Perempuan" <?php checked( $sex, 'Perempuan' ); ?>> Perempuan
    			  	</label></td>
		  	</tr>
		  	<tr>
			  	<th><label for="wpdental_birthdate"><?php _e("Birthdate", "wpdental"); ?></label></th>
			  	<td><input type="date" name="wpdental_birthdate" class="wpdental_birthdate" value="<?php echo $birthdate; ?>" class="regular-text"> (format: <i>yy-mm-dd</i>)</td>
		  	</tr>
		  	<tr>
			  	<th><label for="wpdental_address"><?php _e("Address", "wpdental"); ?></label></th>
			  	<td><textarea name="wpdental_address" id="wpdental_address" class="large-text"><?php echo $address; ?></textarea></td>
		  	</tr>
		  	<tr>
			  	<th><label for="wpdental_phone"><?php _e("Phone", "wpdental"); ?></label></th>
			  	<td><input type="text" name="wpdental_phone" id="wpdental_phone" value="<?php echo $phone; ?>" class="regular-text"></td>
		  	</tr>
		  	<tr>
			  	<th><label for="wpdental_job"><?php _e("Job", "wpdental"); ?></label></th>
			  	<td><input type="text" name="wpdental_job" id="wpdental_job" value="<?php echo $job; ?>" class="regular-text"></td>
		  	</tr>
	  	</table>
  	<?php
}

function general_meta_box_content($post){
    wp_nonce_field("general", "wpdental_nonce");

 	// If meta exists, get it
	$keluhan_utama = get_post_meta($post->ID, "keluhan_utama", true);	
	$ps_hipertensi = get_post_meta($post->ID, "ps_hipertensi", true);
	$ps_diabetes = get_post_meta($post->ID, "ps_diabetes", true);
	$ps_jantung = get_post_meta($post->ID, "ps_jantung", true);
	$ps_ginjal = get_post_meta($post->ID, "ps_ginjal", true);
	$ps_hepatitis = get_post_meta($post->ID, "ps_hepatitis", true);
	$ps_tbc = get_post_meta($post->ID, "ps_tbc", true);
	$ps_aids = get_post_meta($post->ID, "ps_aids", true);
	$ps_pms = get_post_meta($post->ID, "ps_pms", true);
	$ps_hamil = get_post_meta($post->ID, "ps_hamil", true);
	$ps_lain_lain = get_post_meta($post->ID, "ps_lain_lain", true);
	$alergi_obat = get_post_meta($post->ID, "alergi_obat", true);
	$tekanan_darah = get_post_meta($post->ID, "tekanan_darah", true);
	$denyut_nadi = get_post_meta($post->ID, "denyut_nadi", true);
	$riwayat_cabut_gigi = get_post_meta($post->ID, "riwayat_cabut_gigi", true);


?>

<table class="form-table">
<tr>
<th scope="row"><label for="keluhan_utama">Keluhan Utama</label></th>
<td><textarea rows="5" name="wpdental_keluhan_utama" id="keluhan_utama" class="large-text code" /><?php echo $keluhan_utama; ?></textarea></td>
</tr>
<tr>
<th scope="row"><label for="penyakit_sistemik">Penyakit Sistemik</label></th>
<td><label for="hipertensi">
        <input type="checkbox" name="wpdental_ps_hipertensi" id="ps_hipertensi" value="Hipertensi" <?php checked( $ps_hipertensi, 'Hipertensi' ); ?> />Hipertensi
    </label><br />
    <label for="diabetes">
        <input type="checkbox" name="wpdental_ps_diabetes" id="ps_diabetes" value="Diabetes" <?php checked( $ps_diabetes, 'Diabetes' ); ?> />Diabetes
    </label><br />
    <label for="jantung">
        <input type="checkbox" name="wpdental_ps_jantung" id="ps_jantung" value="Jantung" <?php checked( $ps_jantung, 'Jantung' ); ?> />Jantung
    </label><br />
    <label for="ginjal">
        <input type="checkbox" name="wpdental_ps_ginjal" id="ps_ginjal" value="Ginjal" <?php checked( $ps_ginjal, 'Ginjal' ); ?> />Ginjal
    </label><br />
    <label for="hepatitis">
        <input type="checkbox" name="wpdental_ps_hepatitis" id="ps_hepatitis" value="Hepatitis" <?php checked( $ps_hepatitis, 'Hepatitis' ); ?> />Hepatitis
    </label><br />
    <label for="tbc">
        <input type="checkbox" name="wpdental_ps_tbc" id="ps_tbc" value="TBC" <?php checked( $ps_tbc, 'TBC' ); ?> />TBC
    </label><br />
    <label for="aids">
        <input type="checkbox" name="wpdental_ps_aids" id="ps_aids" value="AIDS" <?php checked( $ps_aids, 'AIDS' ); ?> />AIDS
    </label><br />
    <label for="pms">
        <input type="checkbox" name="wpdental_ps_pms" id="ps_pms" value="PMS" <?php checked( $ps_pms, 'PMS' ); ?> />PMS
    </label><br />
    <label for="hamil">
        <input type="checkbox" name="wpdental_ps_hamil" id="ps_hamil" value="Hamil" <?php checked( $ps_hamil, 'Hamil' ); ?> />Hamil
    </label><br />
    <label for="lain-lain">
        <input type="checkbox" name="wpdental_ps_lain_lain" id="ps_lain_lain" value="Lain-Lain" <?php checked( $ps_lain_lain, 'Lain-Lain' ); ?> />Lain-Lain
    </label></td>
</tr>
<tr>
<th scope="row"><label for="alergi_obat">Alergi Obat</label></th>
<td><input type="text" name="wpdental_alergi_obat" id="wpdental_alergi_obat" value="<?php echo $alergi_obat; ?>" class="regular-text" /></td>
</tr>
<tr>
<th scope="row"><label for="tekanan_darah">Tekanan Darah</label></th>
<td><input type="text" name="wpdental_tekanan_darah" id="wpdental_tekanan_darah" value="<?php echo $tekanan_darah; ?>" class="regular-text"> mmHG</td>
</tr>
<tr>
<th scope="row"><label for="denyut_nadi">Denyut Nadi</label></th>
<td><input type="text"name="wpdental_denyut_nadi" id="wpdental_denyut_nadi" value="<?php echo $denyut_nadi; ?>" class="small-text" /> kali per menit</td>
</tr>
<tr>
<th scope="row"><label for="riwayat_cabut_gigi">Riwayat Cabut Gigi</label></th>
<td><label for="riwayat_cabut_gigi_pernah">
        <input type="radio" name="wpdental_riwayat_cabut_gigi" id="riwayat_cabut_gigi_pernah" value="Pernah" <?php checked( $riwayat_cabut_gigi, 'Pernah' ); ?>>
        Pernah
    </label>
    <label for="riwayat_cabut_gigi_tidak_pernah">
        <input type="radio" name="wpdental_riwayat_cabut_gigi" id="riwayat_cabut_gigi_tidak_pernah" value="Tidak Pernah" <?php checked( $riwayat_cabut_gigi, 'Tidak Pernah' ); ?>>
        Tidak Pernah
    </label></td>
</tr>
</table>

<?php

}

function odontogram_meta_box_content($post){
    wp_nonce_field("wpdental", "wpdental_nonce");

	  	$gg11 = get_post_meta($post->ID, "gg11", true);
	  	$gg12 = get_post_meta($post->ID, "gg12", true);
	  	$gg13 = get_post_meta($post->ID, "gg13", true);
	  	$gg14 = get_post_meta($post->ID, "gg14", true);
	  	$gg15 = get_post_meta($post->ID, "gg15", true);
	  	$gg16 = get_post_meta($post->ID, "gg16", true);
	  	$gg17 = get_post_meta($post->ID, "gg17", true);
	  	$gg18 = get_post_meta($post->ID, "gg18", true);

	  	$gg21 = get_post_meta($post->ID, "gg21", true);
	  	$gg22 = get_post_meta($post->ID, "gg22", true);
	  	$gg23 = get_post_meta($post->ID, "gg23", true);
	  	$gg24 = get_post_meta($post->ID, "gg24", true);
	  	$gg25 = get_post_meta($post->ID, "gg25", true);
	  	$gg26 = get_post_meta($post->ID, "gg26", true);
	  	$gg27 = get_post_meta($post->ID, "gg27", true);
	  	$gg28 = get_post_meta($post->ID, "gg28", true);

	  	$gg31 = get_post_meta($post->ID, "gg31", true);
	  	$gg32 = get_post_meta($post->ID, "gg32", true);
	  	$gg33 = get_post_meta($post->ID, "gg33", true);
	  	$gg34 = get_post_meta($post->ID, "gg34", true);
	  	$gg35 = get_post_meta($post->ID, "gg35", true);
	  	$gg36 = get_post_meta($post->ID, "gg36", true);
	  	$gg37 = get_post_meta($post->ID, "gg37", true);
	  	$gg38 = get_post_meta($post->ID, "gg38", true);

	  	$gg41 = get_post_meta($post->ID, "gg41", true);
	  	$gg42 = get_post_meta($post->ID, "gg42", true);
	  	$gg43 = get_post_meta($post->ID, "gg43", true);
	  	$gg44 = get_post_meta($post->ID, "gg44", true);
	  	$gg45 = get_post_meta($post->ID, "gg45", true);
	  	$gg46 = get_post_meta($post->ID, "gg46", true);
	  	$gg47 = get_post_meta($post->ID, "gg47", true);
	  	$gg48 = get_post_meta($post->ID, "gg48", true);

	  	$gg51 = get_post_meta($post->ID, "gg51", true);
	  	$gg52 = get_post_meta($post->ID, "gg52", true);
	  	$gg53 = get_post_meta($post->ID, "gg53", true);
	  	$gg54 = get_post_meta($post->ID, "gg54", true);
	  	$gg55 = get_post_meta($post->ID, "gg55", true);

	  	$gg61 = get_post_meta($post->ID, "gg61", true);
	  	$gg62 = get_post_meta($post->ID, "gg62", true);
	  	$gg63 = get_post_meta($post->ID, "gg63", true);
	  	$gg64 = get_post_meta($post->ID, "gg64", true);
	  	$gg65 = get_post_meta($post->ID, "gg65", true);

	  	$gg71 = get_post_meta($post->ID, "gg71", true);
	  	$gg72 = get_post_meta($post->ID, "gg72", true);
	  	$gg73 = get_post_meta($post->ID, "gg73", true);
	  	$gg74 = get_post_meta($post->ID, "gg74", true);
	  	$gg75 = get_post_meta($post->ID, "gg75", true);

	  	$gg81 = get_post_meta($post->ID, "gg81", true);
	  	$gg82 = get_post_meta($post->ID, "gg82", true);
	  	$gg83 = get_post_meta($post->ID, "gg83", true);
	  	$gg84 = get_post_meta($post->ID, "gg84", true);
	  	$gg85 = get_post_meta($post->ID, "gg85", true);

?>

	<div class="table-odontogram">
	<table style="margin: 0 auto; width: 450px; text-align: center;">
		 <tr>
		 <td>8</td><td>7</td><td>6</td><td>5</td><td>4</td><td>3</td><td>2</td><td>1</td><td> </td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td>
		</tr>
		<tr>
		<td><input type="text" name="wpdental_gg18" id="gg18" value="<?php echo $gg18; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg17" id="gg17" value="<?php echo $gg17; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg16" id="gg16" value="<?php echo $gg16; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg15" id="gg15" value="<?php echo $gg15; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg14" id="gg14" value="<?php echo $gg14; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg13" id="gg13" value="<?php echo $gg13; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg12" id="gg12" value="<?php echo $gg12; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg11" id="gg11" value="<?php echo $gg11; ?>" class="odont_input color"></td>
		<td> </td>
		<td><input type="text" name="wpdental_gg21" id="gg21" value="<?php echo $gg21; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg22" id="gg22" value="<?php echo $gg22; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg23" id="gg23" value="<?php echo $gg23; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg24" id="gg24" value="<?php echo $gg24; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg25" id="gg25" value="<?php echo $gg25; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg26" id="gg26" value="<?php echo $gg26; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg27" id="gg27" value="<?php echo $gg27; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg28" id="gg28" value="<?php echo $gg28; ?>" class="odont_input color"></td>
	  	</tr>
		<tr>
		<td> </td>
		<td> </td>
		<td> </td>
		<td><input type="text" name="wpdental_gg55" id="gg55" value="<?php echo $gg55; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg54" id="gg54" value="<?php echo $gg54; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg53" id="gg53" value="<?php echo $gg53; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg52" id="gg52" value="<?php echo $gg52; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg51" id="gg51" value="<?php echo $gg51; ?>" class="odont_input color"></td>
		<td> </td>
		<td><input type="text" name="wpdental_gg61" id="gg61" value="<?php echo $gg61; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg62" id="gg62" value="<?php echo $gg62; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg63" id="gg63" value="<?php echo $gg63; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg64" id="gg64" value="<?php echo $gg64; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg65" id="gg65" value="<?php echo $gg65; ?>" class="odont_input color"></td>
	  	<td> </td>
	  	<td> </td>
	  	<td> </td>

	  	</tr>
		<tr>
	  	<td> </td><td> </td><td> </td><td>V</td><td>IV</td><td>III</td><td>II</td><td>I</td><td> </td><td>I</td><td>II</td><td>III</td><td>IV</td><td>V</td><td> </td><td> </td><td> </td>
		</tr>
		<tr>
	  	<td> </td>
	  	<td> </td>
	  	<td> </td>
		<td><input type="text" name="wpdental_gg85" id="gg85" value="<?php echo $gg85; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg84" id="gg84" value="<?php echo $gg84; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg83" id="gg83" value="<?php echo $gg83; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg82" id="gg82" value="<?php echo $gg82; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg81" id="gg81" value="<?php echo $gg81; ?>" class="odont_input color"></td>
		<td> </td>
		<td><input type="text" name="wpdental_gg71" id="gg71" value="<?php echo $gg71; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg72" id="gg72" value="<?php echo $gg72; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg73" id="gg73" value="<?php echo $gg73; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg74" id="gg74" value="<?php echo $gg74; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg75" id="gg75" value="<?php echo $gg75; ?>" class="odont_input color"></td>
	  	<td> </td>
	  	<td> </td>
	  	<td> </td>
	  	</tr>
		<tr>
		<td><input type="text" name="wpdental_gg48" id="gg48" value="<?php echo $gg48; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg47" id="gg47" value="<?php echo $gg47; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg46" id="gg46" value="<?php echo $gg46; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg45" id="gg45" value="<?php echo $gg45; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg44" id="gg44" value="<?php echo $gg44; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg43" id="gg43" value="<?php echo $gg43; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg42" id="gg42" value="<?php echo $gg42; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg41" id="gg41" value="<?php echo $gg41; ?>" class="odont_input color"></td>
		<td> </td>
		<td><input type="text" name="wpdental_gg31" id="gg31" value="<?php echo $gg31; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg32" id="gg32" value="<?php echo $gg32; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg33" id="gg33" value="<?php echo $gg33; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg34" id="gg34" value="<?php echo $gg34; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg35" id="gg35" value="<?php echo $gg35; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg36" id="gg36" value="<?php echo $gg36; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg37" id="gg37" value="<?php echo $gg37; ?>" class="odont_input color"></td>
		<td><input type="text" name="wpdental_gg38" id="gg38" value="<?php echo $gg38; ?>" class="odont_input color"></td>
	  	</tr>
		<tr>
	  	<td>8</td><td>7</td><td>6</td><td>5</td><td>4</td><td>3</td><td>2</td><td>1</td><td> </td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td>
		</tr>
	</table>
	<br/><hr/><br/>
	
	<table style="margin: 0 auto; width: 450px;">
		<tr>
			<td style="height: 20px; width: 20px; background-color: #ffffff; border: 1px solid #000000; "></td>
			<td> = Normal</td>
			<td style="height: 20px; width: 20px; background-color: #FF0000; border: 1px solid #000000; "></td>
			<td> = Dicabut</td>
			<td style="height: 20px; width: 20px; background-color: #000000; border: 1px solid #000000; "></td>
			<td> = Hilang</td>
			<td style="height: 20px; width: 20px; background-color: #FFFF00; border: 1px solid #000000; "></td>
			<td> = Karies</td>
		</tr>
		<tr>
			<td style="height: 20px; width: 20px; background-color: #FF6600; border: 1px solid #000000; "></td>
			<td> = Sisa Akar</td>
			<td style="height: 20px; width: 20px; background-color: #0000FF; border: 1px solid #000000; "></td>
			<td> = Tumpatan</td>
			<td style="height: 20px; width: 20px; background-color: #FF00FF; border: 1px solid #000000; "></td>
			<td> = Gigi Tiruan</td>
			<td style="height: 20px; width: 20px; background-color: #339966; border: 1px solid #000000; "></td>
			<td> = Goyang</td>
		</tr>
	</table>

	</div>
<?php

}

?>
