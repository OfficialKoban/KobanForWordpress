<?php
class km_Main
{
	public function __construct(){
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('admin_init', array($this, 'register_settings'));
		add_filter("mce_external_plugins", array($this, "enqueue_plugin_scripts"));
		add_filter("mce_buttons", array($this, "register_buttons_editor"));
		add_filter("tiny_mce_before_init", array($this, "km_tinymce_settings"));
		add_action( 'wp_print_scripts', array($this, 'add_koban_tracking'));
		add_action( 'add_meta_boxes', array($this, 'meta_box_koban') );
		add_action( 'save_post',      array( $this, 'save_box_koban') );
	}
	
	public function add_admin_menu(){
		add_menu_page('Koban Marketing', 'Koban Marketing', 'manage_options', 'koban_marketing', array($this, 'menu_html'));
		add_management_page( 'Déclarer le site', "Déclarer à Koban Marketing", 'manage_options', 'km_declare', array($this, 'kbn_declare') );
	}
	function meta_box_koban()
	{
		$post_types = array( 'post', 'page' );
		add_meta_box( 'koban-meta-box-id', 'Publication Koban', array($this,'koban_box_callback'), $post_types, 'side', 'high' );
	}
	function koban_box_callback( $post )
	{
		// Add an nonce field so we can check for it later.
        wp_nonce_field( 'kbn_inner_custom_box', 'kbn_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_kbn_key', true );
 
        // Display the form, using the current value.
        ?>
        <!--<label for="kbn_publish">
            <?php _e( 'Publier sur Koban', 'publish' ); ?>
        </label>
        <input type="checkbox" id="kbn_publish" name="kbn_publish" <?php if( $value == "on" ) { ?>checked="checked"<?php } ?> />-->
        <?php
	}
	public function save_box_koban( $post_id )
	{
		if ( ! isset( $_POST['kbn_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['kbn_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'kbn_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $mydata = $_POST['kbn_publish'];
 
        // Update the meta field.
        update_post_meta( $post_id, '_kbn_key', $mydata );
	}
	public function kbn_declare(){
		// Déclaration du site à Koban
		$resp = wp_remote_post("https://app-koban.com/api/v1/ncSite/Post", array(
			"body" => array(
				"url" => site_url(),
				"feed" => site_url()
			)
		));
		if ( is_wp_error( $resp ) ) {
			$error_string = $resp->get_error_message();
		}
		echo site_url();
	}
	
	public function menu_html(){
		echo '<h1>Koban Marketing</h1>';
		echo "<p>Bienvenue dans le plugin Koban Marketing pour Wordpress. Avant tout, il convient de paramétrer votre clé API correspondant à votre compte Koban Marketing.<br/>Si vous ne disposez pas d'un compte Koban Marketing, il vous suffit d'en créer un en vous rendant sur <a href='http://www.koban.cloud' target='_blank'>http://www.koban.cloud</a></p>";
		?>
		<form method='post' action='options.php'>
			<?php settings_fields('kobanmkg_settings') ?>
			<div>
				<label style="width:25%;display:inline-block;text-align:right;padding-right:20px">Votre serveur Koban (URL complète)</label>
				<input style="width:40%" type='text' name='kobanmkg_server' value="<?php echo get_option('kobanmkg_server') ?>" />
			</div>
			<div>
				<label style="width:25%;display:inline-block;text-align:right;padding-right:20px">Votre clé API</label>
				<input style="width:40%" type='text' name='kobanmkg_apikey' value="<?php echo get_option('kobanmkg_apikey') ?>" />
			</div>
			<div>
				<label style="width:25%;display:inline-block;text-align:right;padding-right:20px">Votre clé Utilisateur</label>
				<input style="width:40%" type='text' name='kobanmkg_apiusr' value="<?php echo get_option('kobanmkg_apiusr') ?>" />
			</div>
			<div>
				<label style="width:25%;display:inline-block;text-align:right;padding-right:20px">Site en SSL</label>
				<select style="width:40%" name='kobanmkg_ssl'><option value="N" <?php if (get_option('kobanmkg_ssl') == 'N'){ echo 'selected';}?>>Non</option><option value="Y" <?php if (get_option('kobanmkg_ssl') == 'Y'){ echo 'selected';}?>>Oui</option></select>
			</div>
			<?php submit_button() ?>
		</form>
		<?php
	}
	
	public function register_settings()
	{
		register_setting('kobanmkg_settings', 'kobanmkg_server');
		register_setting('kobanmkg_settings', 'kobanmkg_apikey');
		register_setting('kobanmkg_settings', 'kobanmkg_apiusr');
		register_setting('kobanmkg_settings', 'kobanmkg_ssl');
	}
	
	public function add_koban_tracking(){
		$url = get_option('kobanmkg_server')."/libapi/kobantracker.js";
		if( get_option('kobanmkg_apikey') != null && get_option('kobanmkg_apikey') != "" ) {
			if (get_option('kobanmkg_ssl') == "Y"){
				$url = get_option('kobanmkg_server')."/libapi/kobantracker-s.js";
			}
        ?>
        <script type='text/javascript'>(function (i, s, o, g, r, a, m) {
    i['KobanObject'] = r; i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date(); a = s.createElement(o),
            m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
})
(window, document, 'script', '<?php echo $url ?>', 'kb');
kb('reg', '<?php echo get_option('kobanmkg_apikey') ?>');</script>
        <?php
		}
	}
	
	public function enqueue_plugin_scripts($plugin_array)
	{
		//enqueue TinyMCE plugin script with its ID.
		$plugin_array["koban_button_plugin"] =  plugin_dir_url(__FILE__) . "km-index.js";
		return $plugin_array;
	}
	
	public function km_tinymce_settings($settings)
	{
		$settings["km_key"] = get_option('kobanmkg_apikey');
		$settings["km_usr"] = get_option('kobanmkg_apiusr');
		$settings["km_ssl"] = get_option('kobanmkg_ssl');
		return $settings;
	}
	
	public function register_buttons_editor($buttons)
	{
		//register buttons with their id.
		array_push($buttons, "koban");
		return $buttons;
	}
}
?>