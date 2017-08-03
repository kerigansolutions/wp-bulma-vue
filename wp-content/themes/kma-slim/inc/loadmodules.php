<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */

include('modules/social/sociallinks.php');
include('modules/testimonials/testimonials.php');
include('modules/layouts/Layouts.php');
include('modules/slider/Slider.php');

$socialLinks = new SocialSettingsPage();
if(is_admin()) {
	$socialLinks->createPage();
}

$testimonials = new Testimonials();
$testimonials->createPostType();
$testimonials->createAdminColumns();
$testimonials->createShortcode();

$layouts = new Layouts();
$layouts->createPostType();
$layouts->createDefaultFormats();
$layouts->createLayout('two-column','two column page layout','twocol');

$slider = new Slider();
$slider->createPostType();
$slider->createAdminColumns();
