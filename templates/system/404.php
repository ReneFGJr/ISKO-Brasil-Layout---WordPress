<?php
/**
 * Not found template.
 *
 * @package ISKO
 */
get_header();
?>
<main id="conteudo" class="container error-page">
	<p class="eyebrow">404</p>
	<h1><?php esc_html_e( 'Página não encontrada', 'isko' ); ?></h1>
	<p><?php esc_html_e( 'O endereço pode ter mudado ou a página não está disponível.', 'isko' ); ?></p>
	<a class="button-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Voltar ao início', 'isko' ); ?></a>
</main>
<?php get_footer(); ?>
