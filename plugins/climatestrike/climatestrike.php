<?php
/**
 * Plugin Name:     Climatestrike
 */
define("CLIMATESTRIKE_PLUGIN_URL", plugin_dir_url(__FILE__));
define("CLIMATESTRIKE_PLUGIN_DIR", plugin_dir_path(__FILE__));

require(CLIMATESTRIKE_PLUGIN_DIR . 'includes/blocks.php');
require(CLIMATESTRIKE_PLUGIN_DIR . 'includes/custom-post-types.php');
