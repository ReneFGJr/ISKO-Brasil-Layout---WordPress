<?php
/**
 * The footer for the ISKO theme.
 *
 * @package ISKO
 */
?>
<footer class="site-footer">
	<div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
		<a class="footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		<p class="mb-0"><?php
			printf(
				/* translators: %s: current year. */
				esc_html__( '© %s ISKO. Todos os direitos reservados.', 'isko' ),
				esc_html( wp_date( 'Y' ) )
			);
		?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
