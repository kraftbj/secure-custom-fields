<?php
/**
 * The empty list state for an ACF field group
 *
 * @package ACF
 */

?><script>document.body.classList.add('acf-no-field-groups');</script>
<div class="acf-no-field-groups-wrapper">
	<div class="acf-no-field-groups-inner">
		<img src="<?php echo esc_url( acf_get_url( 'assets/images/empty-group.svg' ) ); ?>" />
		<h2><?php esc_html_e( 'Add Your First Field Group', 'acf' ); ?></h2>
		<p>
		<?php
		echo acf_esc_html( __( 'SCF uses field groups to group custom fields together, and then attach those fields to edit screens.', 'acf' ) );
		?>
		</p>
		<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=acf-field-group' ) ); ?>" class="acf-btn"><i class="acf-icon acf-icon-plus"></i> <?php esc_html_e( 'Add Field Group', 'acf' ); ?></a>
	</div>
</div>