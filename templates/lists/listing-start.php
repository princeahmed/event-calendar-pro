<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div id="primary" class="col-md-8 content-area b-r-1">
	<main id="main" class="site-main" role="main">
		<?php ecp_get_template( 'lists/lists-filter.php' ); ?>
		<?php ecp_get_template( 'lists/pagination.php', [ 'max_page' => $max_page, 'paged' => $paged ] ); ?>
