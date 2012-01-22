<?

defined('COT_CODE') or die('Wrong URL');

if (!$db_share) $db_share = $db_x.'share';

/**
 * Sets sharing permissions
 * 
 * @param string $realm Module or plugin code
 * @param string $key Unique item identifier (primary key)
 * @param int $user User ID or 0 for default setting
 * @param bool $read Read rights
 * @param bool $write Write rights
 * @param bool $admin Administrative rights
 * @param int $expires Expiration date (timestamp) or 0 to disable
 * @return bool
 */
function cot_share($realm, $key, $user, $read = false, $write = false, $admin = false, $expires = 0)
{
	global $db, $db_share, $usr;
	cot_block(cot_auth('plug', 'share', 'W'));
	
	$rights = '';
	if ($read) $rights .= 'R';
	if ($write) $rights .= 'W';
	if ($admin) $rights .= 'A';
	if ($user == 0) $expires = 0;
	
	if (cot_share_get($realm, $key, $user) === null)
	{
		$res = $db->insert($db_share, array(
			'realm' => $realm,
			'key' => $key,
			'owner' => $usr['id'],
			'user' => $user,
			'rights' => $rights,
			'expires' => $expires
		));
	}
	else
	{
		$res = $db->update($db_share, array(
			'rights' => $rights,
			'expires' => $expires
		), '
			realm = ? AND key = ? AND 
			owner = ? AND user = ?
		', array(
			$realm, $key, $usr['id'], $user
		));
	}
	return (bool)$res;
}

/**
 * Returns sharing permissions
 *
 * @param string $realm Module or plugin code
 * @param string $key Unique item identifier (primary key)
 * @param int $user User ID, 0 for default setting or null to use current user
 * @param string $mask Access mask
 * @param bool $assign Assign rights to $usr (auth_read, auth_write, isadmin)
 * @return mixed
 */
function cot_share_get($realm, $key, $user = null, $mask = 'RWA', $assign = true)
{
	global $db, $db_share, $sys, $usr;
	if (!$mask) return;
	if ($user === null) $user = $usr['id'];
	
	if ($user > 0)
	{
		$rights = $db->query("
			SELECT `rights`
			FROM $db_share 
			WHERE `realm` = ?
			AND `key` = ?
			AND `user` = ?
			AND (`expires` = 0 OR `expires` > {$sys['now']})
		", array($realm, $key, $user)
		)->fetchColumn();
	}
	
	if (!$rights)
	{
		$rights = $db->query("
			SELECT `rights`
			FROM $db_share
			WHERE `realm` = ?
			AND `key` = ?
			AND `user` = 0
		", array($realm, $key)
		)->fetchColumn();
	}
	if (!$rights) return;
	
	$list = array();
	foreach (str_split($mask) as $m)
	{
		$list[] = $res = (strpos($rights, $m) !== false);
		if ($assign)
		{
			($m == 'R') && $usr['auth_read'] = $res;
			($m == 'W') && $usr['auth_write'] = $res;
			($m == 'A') && $usr['isadmin'] = $res;
		}
	}
	return (count($list) == 1) ? $list[0] : $list;
}

?>