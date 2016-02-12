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

class RedisCache {
	/**
	 * Up and ready server connections.
	 *
	 * @var		boolean
	 */
	static protected $servers = [];

	/**
	 * Last exception error.
	 *
	 * @var		string
	 */
	static protected $lastError = '';

	/**
	 * Acquire a Redis connection.
	 *
	 * @access	protected
	 * @param	string	[Optiona] Server group key. 
	 * 					Example: 'cache' would look up $wgRedisServers['cached']
	 *					Default: Uses the first index of $wgRedisServers.
	 * @param	array	[Optional] Additional options, will merge and overwrite default options.
	 *					- connectTimeout : The timeout for new connections, in seconds.
	 *                      Optional, default is 1 second.
	 *					- persistent     : Set this to true to allow connections to persist across
	 *                      multiple web requests. False by default.
	 *					- password       : The authentication password, will be sent to Redis in clear text.
	 *                      Optional, if it is unspecified, no AUTH command will be sent.
	 *					- serializer     : Set to "php", "igbinary", or "none". Default is "php".
	 * @param	boolean	[Optional] Force a new connection, useful when forking processes.
	 * @return	mixed	Object RedisConnRef or false on failure.
	 */
	static public function getClient($group = null, $options = [], $newConnection = false) {
		global $wgRedisServers;

		if (!extension_loaded('redis')) {
			throw new MWException(__METHOD__." - The PHP Redis extension is not available.  Please enable it on the server to use RedisCache.");
		}

		if (empty($wgRedisServers) || !is_array($wgRedisServers)) {
			MWDebug::log(__METHOD__." - \$wgRedisServers must be configured for RedisCache to function.");
			return false;
		}

		if (empty($group)) {
			$group = 0;
			$server = current($wgRedisServers);
		} else {
			$server = $wgRedisServers[$group];
		}

		if ($newConnection === false && array_key_exists($group, self::$servers)) {
			return self::$servers[$group];
		}

		if (empty($server) || !is_array($server)) {
			throw new MWException(__METHOD__." - An invalid server group key was passed.");
		}

		$pool = \RedisConnectionPool::singleton(array_merge($server['options'], $options));
		$redis = $pool->getConnection($server['host'].":".$server['port']); //Concatenate these together for MediaWiki weirdness so it can split them later.

		if ($redis instanceOf RedisConnRef) {
			//Set up any extra options.  RedisConnectionPool does not handle the prefix automatically.
			if (!empty($server['options']['prefix'])) {
				$redis->setOption(Redis::OPT_PREFIX, $server['options']['prefix']);
			}
			try {
				$pong = $redis->ping();
				if ($pong === '+PONG') {
					self::$servers[$group] = $redis;
				} else {
					$redis = false;
				}
			} catch (RedisException $e) {
				//People using HAProxy will find it will lie about a Redis cluster being healthy when the master is down, but the slaves are up.  Doing a PING will cause an immediate disconnect.
				self::$lastError = $e->getMessage();
				$redis = false;
			}
		}

		return $redis;
	}

	/**
	 * Return the last exception error.
	 *
	 * @access	public
	 * @return	string
	 */
	static public function getLastError() {
		return self::$lastError;
	}
}
