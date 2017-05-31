<?php
/*
	Plugin Name: Jem's CF7 Spam Stopper
	Plugin URI: 
	Description: Jem's Contact Form 7 Spam Stopper - based on the legendary "Jem's Mail Form" free PHP mail form
	Version: 0.1
	Author: Jem Turner
	Author URI: http://jemsmailform.com
	License: GPL
*/

function is_bot() {
	$bots = array( "008", "bot", "crawler", "spider", "Accoona-AI-Agent", "alexa", "Arachmo", "B-l-i-t-z-B-O-T", "boitho.com-dc", "Cerberian Drtrs", "Charlotte", "cosmos", "Covario IDS", "DataparkSearch", "FindLinks", "Holmes", "htdig", "ia_archiver", "ichiro", "inktomi", "igdeSpyder", "L.webis", "Larbin", "LinkWalker", "lwp-trivial", "mabontland", "Mnogosearch", "mogimogi", "Morning Paper", "MVAClient", "NetResearchServer", "NewsGator", "NG-Search", "NutchCVS", "Nymesis", "oegp", "Orbiter", "Peew", "Pompos", "PostPost", "PycURL", "Qseero", "Radian6", "SBIder", "ScoutJet", "Scrubby", "SearchSight", "semanticdiscovery", "ShopWiki", "silk", "Snappy", "Sqworm", "StackRambler", "Teoma", "TinEye", "truwoGPS", "updated", "Vagabondo", "Vortex", "voyager", "VYU2", "webcollage", "Websquash.com", "wf84", "WomlpeFactory", "yacy", "Yahoo! Slurp", "Yahoo! Slurp China", "YahooSeeker", "YahooSeeker-Testing", "YandexImages", "Yeti", "yoogliFetchAgent", "Zao", "ZyBorg", "froogle", "looksmart", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "Scooter", "appie", "WebBug", "Spade", "rabaz", "TechnoratiSnoop", "Indy", "Blaiz", "Java", "libwww-perl", "Python", "OutfoxBot", "User-Agent", "AlphaServer", "T8Abot", "Syntryx", "WinHttp", "WebBandit", "nicebot", "URL_Spider_SQL", "WebFindBot", "girafabot", "www.galaxy.com", "Googlebot", "Slurp", "FAST" );

	foreach ( $bots as $bot )
		if ( stripos( $_SERVER['HTTP_USER_AGENT'], $bot ) !== false)
			return true;

	if ( empty( $_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == " " )
		return true;
	
	return false;
}



function jems_cf7_spam_stopper( $spam ) {
	if ( $spam )
		return $spam;

	$max_points = 4;
	
	if ( is_bot() ) {
		$spam = true;
		return $spam;
	}
	
	
	$points = 0;
	
	$badwords = array( "adult", "beastial", "bestial", "blowjob", "clit", "cum", "cunilingus", "cunillingus", "cunnilingus", "cunt", "ejaculate", "fag", "felatio", "fellatio", "fuck", "fuk", "fuks", "gangbang", "gangbanged", "gangbangs", "hotsex", "hardcode", "jism", "jiz", "orgasim", "orgasims", "orgasm", "orgasms", "phonesex", "phuk", "phuq", "pussies", "pussy", "spunk", "xxx", "viagra", "phentermine", "tramadol", "adipex", "advai", "alprazolam", "ambien", "ambian", "amoxicillin", "antivert", "blackjack", "backgammon", "texas", "holdem", "poker", "carisoprodol", "ciara", "ciprofloxacin", "debt", "dating", "porn", "link=", "voyeur", "content-type", "bcc:", "cc:", "document.cookie", "onclick", "onload", "javascript" );

	foreach ( $badwords as $word )
		if (
			strpos( strtolower( $_POST['your-message'] ), $word) !== false || 
			strpos( strtolower( $_POST['your-name'] ), $word) !== false
		)
			$points += 2;
			
	if ( strpos( $_POST['your-message'], "http://" ) !== false || strpos( $_POST['your-message'], "www." ) !== false )
		$points += 2;
	if ( preg_match( "/(<.*>)/i", $_POST['your-message'] ) )
		$points += 2;
	if ( strlen( $_POST['your-name'] ) < 3 )
		$points += 1;
	if ( strlen( $_POST['your-message'] ) < 15 || strlen( $_POST['your-message'] ) > 1500 )
		$points += 2;
	if ( preg_match( "/[bcdfghjklmnpqrstvwxyz]{7,}/i", $_POST['your-message'] ) )
		$points += 1;
	
	if ( $points >= $max_points ) {
		$spam = true;
		return $spam;
	}
	
	
}
add_filter( 'wpcf7_spam', 'jems_cf7_spam_stopper' );