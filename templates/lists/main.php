<?php
ecp_get_template( 'lists/listing-start.php', [ 'max_page' => $max_page, 'paged' => $paged ] );
foreach ( $events as $event ) {
	ecp_get_template( 'lists/listing-content.php', [ 'event' => $event ] );
}
ecp_get_template( 'lists/pagination.php', [ 'max_page' => $max_page, 'paged' => $paged ] );
ecp_get_template( 'lists/listing-end.php' );
?>
