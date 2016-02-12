<?php
/**
 * Curse Inc.
 * Redis Cache
 *
 * @author		Alexia E. Smith
 * @copyright	(c) 2015 Curse Inc.
 * @license		All Rights Reserved
 * @package		RedisCache
 * @link		http://www.curse.com/
 *
**/

/******************************************/
/* Credits                                */
/******************************************/
$wgExtensionCredits['specialpage'][] = [
	'path'           => __FILE__,
	'name'           => 'Redis Cache',
	'author'         => ['Alexia E. Smtih'],
	'descriptionmsg' => 'rediscache_description',
	'version'        => '1.0'
];

/******************************************/
/* Language Strings, Page Aliases, Hooks  */
/******************************************/
$wgMessagesDirs['RedisCache'] = __DIR__.'/i18n';

//Classes
$wgAutoloadClasses['RedisCache']			= __DIR__.'/classes/RedisCache.php';
