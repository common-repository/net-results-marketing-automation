<?php
/*
Plugin Name: Net-Results Marketing Automation
Plugin URI: https://wordpress.org/plugins/net-results-marketing-automation/
Description: Leverage Progressive Profiling in your web forms, instantly begin lead scoring every visitor to your WordPress site, automate marketing list management, lead nurturing and drip email campaigns. Includes a custom Widget for embedding Net-Results Forms and the automatic setup of Net-Results implementation code (this enables all features of the Net-Results Marketing Automation Platform on your WordPress site or blog).
Version: 2.5
Author: <a href="http://www.net-results.com/" target="_blank">Net-Results Marketing Automation</a>
*/

if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

function activate_netresults()
{
	add_option('nr_ps_id');
	add_option('nr_access_token');
	add_option('nr_client_id');
	add_option('nr_client_secret');
}

function deactive_netresults()
{
	delete_option('nr_ps_id');
	delete_option('nr_access_token');
	delete_option('nr_client_id');
	delete_option('nr_client_secret');
}

function admin_init_netresults()
{
	register_setting('netresults', 'nr_ps_id');
	register_setting('netresults', 'nr_access_token');
	register_setting('netresults', 'nr_client_id');
	register_setting('netresults', 'nr_client_secret');
}

function malog()
{
	syslog(LOG_DEBUG, uniqid() . ' wordpress ' . implode(" ", func_get_args()));
}

function admin_menu_netresults()
{
	add_options_page('Net-Results Marketing Automation', 'Net-Results', 'manage_options', 'netresults', 'options_page_netresults');
}

function options_page_netresults()
{
	include(WP_PLUGIN_DIR . '/net-results-marketing-automation/options.php');
}

function init_nr_tracking()
{
	$ps_id = get_option('nr_ps_id');
	if (!empty($ps_id) && is_numeric($ps_id) && $ps_id > 0) {
		echo <<<__nr_tracking_code__
<script id="__maSrc" type="text/javascript" data-pid="$ps_id">
	(function(c,a,p,s) {
		p=c.createElement(a);
		p.type='text/java'+a;
		p.src='https://sc.cdnma.com/apps/$ps_id/capture.js';
		s=c.getElementsByTagName(a)[0];s.parentNode.insertBefore(p,s);
	}(document,'script'));
</script>
__nr_tracking_code__;
	} else {
		malog('no ps_id specified. cannot continue.');
	}
}

function api_call($controller, $call_params)
{
	$pre_params = array(
		'id' => uniqid(),
		'jsonrpc' => '2.0'
	);
	$params = array_merge($pre_params, $call_params);
	$nr_client_id = get_option('nr_client_id');
	$nr_client_secret = get_option('nr_client_secret');
	$nr_access_token = get_option('nr_access_token');
	$url = 'https://apps.net-results.com/api/v2/rpc/server.php?Controller=' . $controller . '&access_token='. $nr_access_token . '&client_id=' . $nr_client_id . '&client_secret=' . $nr_client_secret;
	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		$strResponse = curl_exec($ch);
		$arrResponse = json_decode($strResponse, true);
		if ($arrResponse) {
			return ($arrResponse);
		} else {
			return ($strResponse);
		}
	} else {
		malog('curl extension is not installed or enabled. cannot continue.');
	}
}

function api_get_forms()
{
	$params = array(
		'method' => 'getMultiple',
		'params' => array(
			'offset' => 0,
			'limit' => 50,
			'order_by' => 'form_name',
			'order_dir' => 'ASC'
		)
	);
	$response = api_call('Form', $params);
	return $response;
}

register_activation_hook(__FILE__, 'activate_netresults');
register_deactivation_hook(__FILE__, 'deactive_netresults');

if (is_admin()) {
	add_action('admin_init', 'admin_init_netresults');
	add_action('admin_menu', 'admin_menu_netresults');
	add_action('load-authorization.php', 'send_authorization_code');

}

if (!is_admin()) {
	add_action('wp_footer', 'init_nr_tracking');
}

// *** BEGIN Net-Results Form Widget ***
class netresults_form_widget extends WP_Widget
{

	function __construct()
	{
		parent::__construct(
			'netresults_form_widget', // Base ID of the widget
			__('Net-Results Form', 'netresults_form_widget_domain'), // Widget name as it will appear in the UI
			array('description' => __('Insert any Net-Results Form', 'netresults_form_widget_domain'),) // Widget description
		);
	}

	// Widget Front-End
	public function widget($args, $instance)
	{
		$title = apply_filters('widget_title', $instance['title']);
		$form_id = $instance['form_id'];

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if (!empty($form_id)) {
			echo <<<__embed_form__
<div id="MAform-$form_id" class="MAform">
    <script type="text/javascript" src="https://forms.net-results.io/form-render.js?fid=$form_id"></script>
</div>
__embed_form__;
		}
		echo $args['after_widget'];
	}

	// Widget Backend
	public function form($instance)
	{
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		} else {
			$title = __('New title', 'netresults_form_widget_domain');
		}

		//get list of NR Forms
		$form_options = '<option value=""> -- Please Select --</option>';
		$form_list = api_get_forms();

		//build list of <options> for the <select> drop down of available NR forms
		if (is_array($form_list) && array_key_exists('result', $form_list)) {
			foreach ($form_list['result'] as $arr_result) {
				if (is_array($arr_result)) {
					foreach ($arr_result as $arr_form) {
						if ($arr_form['form_id'] == $instance['form_id']) {
							$selected = ' selected';
						} else {
							$selected = '';
						}
						$form_options .= <<<__options__
						<option value="{$arr_form['form_id']}"$selected>{$arr_form['form_name']}</option>\n
__options__;
					}
				}
			}
		} else {
			$form_options = '<option value=""> -- No Forms found. Did you enter your username and password? --</option>';
		}

		// Widget admin form
		?>
		<p>
			<label
				for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Add a Title for Your Form (optional):'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				   name="<?php echo $this->get_field_name('title'); ?>" type="text"
				   value="<?php echo esc_attr($title); ?>"/>
			<br/><br/>

			<label for="<?php echo $this->get_field_id('form_id'); ?>"><?php _e('Choose a Form to Embed:'); ?></label>
			<select id="<?php echo $this->get_field_id('form_id'); ?>"
					name="<?php echo $this->get_field_name('form_id'); ?>" class="widefat" style="width:100%;">
				<?php echo $form_options; ?>
			</select>
		</p>
	<?php
	}

	// Updating widget replacing old instances with new
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['form_id'] = (!empty($new_instance['form_id'])) ? strip_tags($new_instance['form_id']) : '';
		return $instance;
	}
} // Class netresults_form_widget ends here

// Register and load the widget
function load_widget()
{
	register_widget('netresults_form_widget');
}

add_action('widgets_init', 'load_widget');
// *** END Net-Results Form Widget ***
?>
