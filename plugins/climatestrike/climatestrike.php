<?php
/**
 * Plugin Name:     Climatestrike
 */
define("CLIMATESTRIKE_PLUGIN_URL", plugin_dir_url(__FILE__));
define("CLIMATESTRIKE_PLUGIN_DIR", plugin_dir_path(__FILE__));

// Environment variables
require(CLIMATESTRIKE_PLUGIN_DIR . 'config.php');

// Includes
require(CLIMATESTRIKE_PLUGIN_DIR . 'includes/functions.php'); // Functions (for use in templating)
require(CLIMATESTRIKE_PLUGIN_DIR . 'includes/blocks.php'); // Blocks added by this plugin
require(CLIMATESTRIKE_PLUGIN_DIR . 'includes/ajax.php'); // AJAX Scripts
