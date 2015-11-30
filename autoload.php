<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

do_action('nnr_dis_con_before_autoload_v2');

require_once('base.php');
require_once('controllers/settings.php');
require_once('controllers/display.php');

do_action('nnr_dis_con_after_autoload_v2');