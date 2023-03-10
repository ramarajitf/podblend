<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$important = ( isset( $defaults['important_style'] ) && ! empty( $defaults['important_style'] ) ) ? ' !important' : '';
?>
.frm_rootline_group{
	margin: 20px 0 30px<?php echo esc_html( $important ) ?>;
}
			
ul.frm_page_bar{
	list-style-type: none<?php echo esc_html( $important ) ?>;
	margin: 0 !important;
	padding: 0<?php echo esc_html( $important ) ?>;
	width: 100%<?php echo esc_html( $important ) ?>;
	float: left<?php echo esc_html( $important ) ?>;
	display: table<?php echo esc_html( $important ) ?>;
	display: -webkit-flex<?php echo esc_html( $important ) ?>;
	display: flex<?php echo esc_html( $important ) ?>;
	flex-wrap: wrap<?php echo esc_html( $important ) ?>;
	box-sizing: border-box<?php echo esc_html( $important ) ?>;
	-moz-box-sizing: border-box<?php echo esc_html( $important ) ?>;
	-webkit-box-sizing: border-box<?php echo esc_html( $important ) ?>;
}

ul.frm_page_bar li{
	display: inline-block;
	-ms-flex: 1;
	flex: 1;
}

ul.frm_page_bar.frm_rootline_hidden_steps {
	z-index: 1;
	display: block;
	width: auto;
	position: absolute;
	background: #fff;
	padding: 16px;
	right: 0;
	flex-direction: column;
	border-radius: 6px;
	min-width: 70%;
}

ul.frm_page_bar.frm_rootline_hidden_steps li{
	white-space: nowrap;
	text-align: start;
	z-index: 1;
	cursor: pointer;
}

.frm_forms .frm_page_bar input,
.frm_forms .frm_page_bar input:disabled{
	transition: background-color 0.1s ease;
	color: <?php echo esc_html( $defaults['progress_color'] ) ?>;
	color: var(--progress-color) <?php echo esc_html( $important ) ?>;
	background-color: <?php echo esc_html( $defaults['progress_bg_color'] ) ?>;
	background-color: var(--progress-bg-color) <?php echo esc_html( $important ) ?>;
	font-size: 18px;
	border-width: <?php echo esc_html( $defaults['progress_border_size'] ) ?>;
	border-width: var(--progress-border-size) <?php echo esc_html( $important ); ?>;
	border-style: solid;
	border-color: <?php echo esc_html( FrmStylesHelper::adjust_brightness( $defaults['progress_border_color'], -10 ) ) ?>;
	border-color: var(--progress-border-color-b) <?php echo esc_html( $important ); ?>;
}

.frm_forms .frm_page_bar input:focus{
	outline: none;
}

.frm_forms .frm_progress_line input.frm_page_back{
	background-color: <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
	background-color: var(--progress-active-bg-color) <?php echo esc_html( $important ); ?>;
}

.frm_forms .frm_page_bar .frm_current_page input[type="button"]{
	background-color: <?php echo esc_html( $defaults['progress_bg_color'] . $important ) ?>;
	border-color: <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
}
	
.frm_rootline_single{
	text-align: center;
	margin: 0<?php echo esc_html( $important ) ?>;
	padding: 0<?php echo esc_html( $important ) ?>;
}

.frm_current_page .frm_rootline_title{
	color: <?php echo esc_html( $defaults['progress_active_bg_color'] ) ?>;
	color: var(--progress-active-bg-color) <?php echo esc_html( $important ); ?>;
}

.frm_rootline_title,
.frm_pages_complete,
.frm_percent_complete {
	font-size:14px<?php echo esc_html( $important ) ?>;
	padding:4px<?php echo esc_html( $important ) ?>;
	color: <?php echo esc_html( $defaults['description_color'] ); ?>;
	color: var(--description-color) <?php echo esc_html( $important ); ?>;
}

.frm_pages_complete {
	float: right;
	margin-right:13px;
}

.frm_percent_complete {
	float: left;
	margin-left:13px;
}

.frm_forms .frm_progress_line input,
.frm_forms .frm_progress_line input:disabled {
	width: 100%;
	border: none;
	border-top: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
	border-bottom: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
	box-shadow: inset 0 2px 10px -10px rgba(41, 58, 82, 0.31);
	margin: 5px 0<?php echo esc_html( $important ) ?>;
	padding: 6px 0<?php echo esc_html( $important ); ?>;
	border-radius:0;
	font-size:0<?php echo esc_html( $important ); ?>;
	line-height:15px<?php echo esc_html( $important ); ?>;
}

.frm_forms .frm_progress_line.frm_show_lines input {
	border-left: 1px solid <?php echo esc_html( $defaults['progress_color'] . $important ) ?>;
	border-right: 1px solid <?php echo esc_html( $defaults['progress_color'] . $important ) ?>;
}

.frm_progress_line .frm_rootline_single {
	display: flex<?php echo esc_html( $important ); ?>;
	flex-direction: column<?php echo esc_html( $important ); ?>;
	justify-content: flex-end<?php echo esc_html( $important ); ?>;
	margin: 0<?php echo esc_html( $important ); ?>;
}

.frm_forms .frm_progress_line li:first-of-type input {
	border-top-left-radius: 15px<?php echo esc_html( $important ) ?>;
	border-bottom-left-radius: 15px<?php echo esc_html( $important ) ?>;
	border-left: 1px solid <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line li:last-of-type input {
	border-top-right-radius: 15px<?php echo esc_html( $important ) ?>;
	border-bottom-right-radius: 15px<?php echo esc_html( $important ) ?>;
	border-right: 1px solid <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line li:last-of-type input.frm_page_skip {
	border-right: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line .frm_current_page input[type="button"] {
	border-left: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line.frm_show_lines .frm_current_page input[type="button"] {
	border-right: 1px solid <?php echo esc_html( $defaults['progress_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line input.frm_page_back {
	border-color: <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line.frm_show_lines input.frm_page_back{
	border-left-color: <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
	border-right-color: <?php echo esc_html( $defaults['progress_color'] . $important ) ?>;
}

/* Start RTL */
.frm_rtl.frm_forms .frm_progress_line li:first-of-type input {
	border-top-right-radius: 15px<?php echo esc_html( $important ) ?>;
	border-bottom-right-radius: 15px<?php echo esc_html( $important ) ?>;
	border-top-left-radius:0px<?php echo esc_html( $important ); ?>;
	border-bottom-left-radius:0px<?php echo esc_html( $important ); ?>;
	border-right: 1px solid <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
}

.frm_rtl.frm_forms .frm_progress_line li:last-of-type input	{
	border-top-left-radius: 15px<?php echo esc_html( $important ) ?>;
	border-bottom-left-radius: 15px<?php echo esc_html( $important ) ?>;
	border-top-right-radius:0px<?php echo esc_html( $important ); ?>;
	border-bottom-right-radius:0px<?php echo esc_html( $important ); ?>;
	border-left: 1px solid <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
}

.frm_rtl.frm_forms .frm_progress_line li:last-of-type input.frm_page_skip {
	border-left: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
	border-right:none;
}

.frm_rtl.frm_forms .frm_progress_line .frm_current_page input[type="button"] {
	border-right: 1px solid <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
	border-left:none;
}

.frm_rtl.frm_forms .frm_progress_line.frm_show_lines .frm_current_page input[type="button"] {
	border-left: 1px solid <?php echo esc_html( $defaults['progress_color'] . $important ) ?>;
	border-right:none;
}
/* End RTL */

.frm_rootline.frm_show_lines:before {
	border-top-width: <?php echo esc_html( $defaults['progress_border_size'] ) ?>;
	border-top-style: solid;
	border-top-color: <?php echo esc_html( $defaults['progress_border_color'] . $important ) ?>;
	content: "";
	margin: 0 auto;
	position: absolute;
	top: 20px;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	z-index: -1;
}

.frm_rootline_hidden_steps .frm_rootline_title:after {
	border-width: 0 0 0 2px;
	border-style: solid;
	border-color: #B9B9C3;
	content: "";
	z-index: -1;
	display: block;
	height: 15px;
	margin: 10px 0px -5px -20px;
}

.frm_rootline_single .frm_rootline_node {
	position: relative;
}

.frm_rootline.frm_show_lines{
	position: relative;
    z-index: 1;
}

.frm_rootline.frm_show_lines span{
	display:block;
}

.frm_forms .frm_rootline input {
	width: <?php echo esc_html( $defaults['progress_size'] . $important ) ?>;
	height: <?php echo esc_html( $defaults['progress_size'] . $important ) ?>;
	min-height:auto;
	border-radius: <?php echo esc_html( $defaults['progress_size'] . $important ) ?>;
	padding:0<?php echo esc_html( $important ) ?>;
}

.frm_forms .frm_rootline.frm_no_numbers input.frm_rootline_show_more_btn {
	color: var(--progress-color) !important;
}

.frm_forms .frm_rootline input:focus {
	border-color: <?php echo esc_html( $defaults['progress_active_bg_color'] ) ?>;
	border-color: var(--progress-active-bg-color) <?php echo esc_html( $important ); ?>;
}

.frm_forms .frm_rootline .frm_current_page input[type="button"] {
	border-color: <?php echo esc_html( FrmStylesHelper::adjust_brightness( $defaults['progress_active_bg_color'], -20 ) . $important ) ?>;
	background-color: <?php echo esc_html( $defaults['progress_active_bg_color'] . $important ) ?>;
	color: <?php echo esc_html( $defaults['progress_active_color'] . $important ) ?>;
}

.frm_forms .frm_progress_line input,
.frm_forms .frm_progress_line input:disabled,
.frm_forms .frm_progress_line .frm_current_page input[type="button"],
.frm_forms .frm_rootline.frm_no_numbers input,
.frm_forms .frm_rootline.frm_no_numbers .frm_current_page input[type="button"] {
	color: transparent !important;
}

@media only screen and (max-width: 700px) {
	.frm_progress span.frm_rootline_title,
	.frm_rootline.frm_rootline_10 span.frm_rootline_title,
	.frm_rootline.frm_rootline_9 span.frm_rootline_title,
	.frm_rootline.frm_rootline_8 span.frm_rootline_title,
	.frm_rootline.frm_rootline_7 span.frm_rootline_title,
	.frm_rootline.frm_rootline_6 span.frm_rootline_title,
	.frm_rootline.frm_rootline_5 span.frm_rootline_title{
		display:none;
	}

	.frm_rootline_hidden_steps .frm_rootline_single span.frm_rootline_title {
		position: relative;
		display: inline-block;
	}
}

@media only screen and (max-width: 500px) {
	.frm_rootline.frm_rootline_4 span.frm_rootline_title,
	.frm_rootline.frm_rootline_3 span.frm_rootline_title{
		display:none;
	}
}
