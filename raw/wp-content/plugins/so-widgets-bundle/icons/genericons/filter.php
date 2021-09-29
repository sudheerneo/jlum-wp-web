<?php

function siteorigin_widgets_icons_genericons_filter( $icons ){
	return $icons + array(
		"standard" => '&#xf100;',
		"aside" => '&#xf101;',
		"image" => '&#xf102;',
		"gallery" => '&#xf103;',
		"video" => '&#xf104;',
		"status" => '&#xf105;',
		"quote" => '&#xf106;',
		"link" => '&#xf107;',
		"chat" => '&#xf108;',
		"audio" => '&#xf109;',
		"github" => '&#xf200;',
		"dribbble" => '&#xf201;',
		"twitter" => '&#xf202;',
		"facebook" => '&#xf203;',
		"facebook-alt" => '&#xf204;',
		"wordpress" => '&#xf205;',
		"googleplus" => '&#xf206;',
		"linkedin" => '&#xf207;',
		"linkedin-alt" => '&#xf208;',
		"pinterest" => '&#xf209;',
		"pinterest-alt" => '&#xf210;',
		"flickr" => '&#xf211;',
		"vimeo" => '&#xf212;',
		"youtube" => '&#xf213;',
		"tumblr" => '&#xf214;',
		"instagram" => '&#xf215;',
		"codepen" => '&#xf216;',
		"polldaddy" => '&#xf217;',
		"googleplus-alt" => '&#xf218;',
		"path" => '&#xf219;',
		"skype" => '&#xf220;',
		"digg" => '&#xf221;',
		"reddit" => '&#xf222;',
		"stumbleupon" => '&#xf223;',
		"pocket" => '&#xf224;',
		"dropbox" => '&#xf225;',
		"comment" => '&#xf300;',
		"category" => '&#xf301;',
		"tag" => '&#xf302;',
		"time" => '&#xf303;',
		"user" => '&#xf304;',
		"day" => '&#xf305;',
		"week" => '&#xf306;',
		"month" => '&#xf307;',
		"pinned" => '&#xf308;',
		"search" => '&#xf400;',
		"unzoom" => '&#xf401;',
		"zoom" => '&#xf402;',
		"show" => '&#xf403;',
		"hide" => '&#xf404;',
		"close" => '&#xf405;',
		"close-alt" => '&#xf406;',
		"trash" => '&#xf407;',
		"star" => '&#xf408;',
		"home" => '&#xf409;',
		"mail" => '&#xf410;',
		"edit" => '&#xf411;',
		"reply" => '&#xf412;',
		"feed" => '&#xf413;',
		"warning" => '&#xf414;',
		"share" => '&#xf415;',
		"attachment" => '&#xf416;',
		"location" => '&#xf417;',
		"checkmark" => '&#xf418;',
		"menu" => '&#xf419;',
		"refresh" => '&#xf420;',
		"minimize" => '&#xf421;',
		"maximize" => '&#xf422;',
		"404" => '&#xf423;',
		"spam" => '&#xf424;',
		"summary" => '&#xf425;',
		"cloud" => '&#xf426;',
		"key" => '&#xf427;',
		"dot" => '&#xf428;',
		"next" => '&#xf429;',
		"previous" => '&#xf430;',
		"expand" => '&#xf431;',
		"collapse" => '&#xf432;',
		"dropdown" => '&#xf433;',
		"dropdown-left" => '&#xf434;',
		"top" => '&#xf435;',
		"draggable" => '&#xf436;',
		"phone" => '&#xf437;',
		"send-to-phone" => '&#xf438;',
		"plugin" => '&#xf439;',
		"cloud-download" => '&#xf440;',
		"cloud-upload" => '&#xf441;',
		"external" => '&#xf442;',
		"document" => '&#xf443;',
		"book" => '&#xf444;',
		"cog" => '&#xf445;',
		"unapprove" => '&#xf446;',
		"cart" => '&#xf447;',
		"pause" => '&#xf448;',
		"stop" => '&#xf449;',
		"skip-back" => '&#xf450;',
		"skip-ahead" => '&#xf451;',
		"play" => '&#xf452;',
		"tablet" => '&#xf453;',
		"send-to-tablet" => '&#xf454;',
		"info" => '&#xf455;',
		"notice" => '&#xf456;',
		"help" => '&#xf457;',
		"fastforward" => '&#xf458;',
		"rewind" => '&#xf459;',
		"portfolio" => '&#xf460;',
		"heart" => '&#xf461;',
		"code" => '&#xf462;',
		"subscribe" => '&#xf463;',
		"unsubscribe" => '&#xf464;',
		"subscribed" => '&#xf465;',
		"reply-alt" => '&#xf466;',
		"reply-single" => '&#xf467;',
		"flag" => '&#xf468;',
		"print" => '&#xf469;',
		"lock" => '&#xf470;',
		"bold" => '&#xf471;',
		"italic" => '&#xf472;',
		"picture" => '&#xf473;',
		"fullscreen" => '&#xf474;',
		"uparrow" => '&#xf500;',
		"rightarrow" => '&#xf501;',
		"downarrow" => '&#xf502;',
		"leftarrow" => '&#xf503;',
	);
}
add_filter('siteorigin_widgets_icons_genericons', 'siteorigin_widgets_icons_genericons_filter');