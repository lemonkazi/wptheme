<?php
/**
 * The main template file.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$translate['search-placeholder'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-placeholder','Enter your search') : __('Enter your search','betheme');
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" class="field" name="s" id="s" placeholder="<?php echo $translate['search-placeholder']; ?>" />
	<input type="submit" class="submit" value="Search" />
</form>