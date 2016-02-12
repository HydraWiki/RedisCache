<?php
/**
 * Curse Inc.
 * Redis Cache
 *
 * @author		Alexia E. Smith
 * @copyright	(c) 2015 Curse Inc.
 * @license		GPLv3
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
$wgAutoloadClasses['RedisCache'] = __DIR__.'/classes/RedisCache.php';

//In your LocalSettings.php file or other configuration files you should configure $wgRedisServers.
//This setting is configured in a similiar to $wgExternalServers where there is an array of server identifiers that contains a server configuration.
/*
$wgRedisServers = [
	'cache'		=> [
		'host'		=> '127.0.0.1',
		'port'		=> 6379, //Default Redis port is 6379.
		'options'	=> [
			'prefix'		=> '', //Prefix all keys with this string.  Note, a single ':' character will not be automatically added.
			'serializer'	=> 'none', //Which serializer to use.  See Redis::OPT_SERIALIZER option at: https://github.com/phpredis/phpredis
			'readTimeout'	=> 5 //This is the default recommended read timeout.
		]
	],
	'worker'		=> [
		'host'		=> 'worker_node.example.com',
		'port'		=> 9001,
		'options'	=> [
			'prefix'		=> 'MW:',
			'serializer'	=> 'none',
			'readTimeout'	=> -1 //Persistent worker processes may want -1 for an infinite timeout.
		]
	]
];
*/
