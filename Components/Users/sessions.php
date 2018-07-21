<?php
namespace APLib\Users;

/**
 * Sessions - A class to manage sessions
 */
class Sessions
{
	private static $account = null;

	public static function create($username)
	{
		$cookie = \APLib\Extras::RandomString();
  	$device = \APLib\Security::identify();
  	$now    = date("Y-m-d H:i:s");
  	$stmt   = \APLib\DB::prepare("INSERT INTO sessions VALUES(?,?,?,?,?,?,?,?)");
		if($stmt == null) return false;
		$stmt->bind_param('ssssssss', $cookie, $username, $device['os'], $device['agent'], $now, $now, $device['ip'], $device['ip']);
		$stmt->execute();
		if($stmt->affected_rows > 0)
		{
			setcookie(\APLib\Config::get('Cookie Name'), $cookie, time() + \APLib\Config::get('Cookie Timeout'), '/');
			return true;
		}
		return false;
	}

	public static function remove($id)
	{
		$stmt = \APLib\DB::prepare("DELETE FROM sessions WHERE id = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		return ($stmt->affected_rows > 0);
	}

	public static function loggedIn()
	{
		if(!isset($_COOKIE[\APLib\Config::get("Cookie Name")])) return false;
		if(static::$account !== null) return true;
		$stmt = \APLib\DB::prepare("SELECT COUNT(*) AS C,username FROM sessions WHERE id = ?");
		$stmt->bind_param('s', $_COOKIE[\APLib\Config::get("Cookie Name")]);
		$stmt->execute();
		$c = 0; $username = null;
		$stmt->store_result();
		$stmt->bind_result($c, $username);
		$stmt->fetch();
		static::$account = \APLib\Users::account($username);
		return ($c > 0);
	}

	public static function account()
	{
		return static::$account;
	}

	public static function table()
	{
		\APLib\DB::query(
			"CREATE TABLE IF NOT EXISTS sessions(
				id VARCHAR(150) NOT NULL,
				username VARCHAR(60) NOT NULL,
				os VARCHAR(25) NOT NULL,
				agent TEXT NOT NULL,
				first_login DATETIME NOT NULL,
				last_login DATETIME NOT NULL,
				first_ip TEXT NOT NULL,
				last_ip TEXT NOT NULL,
				INDEX (os),
				INDEX (first_login),
				INDEX (last_login),
				PRIMARY KEY (id),
				CONSTRAINT FK_sessions_username FOREIGN KEY (username) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE CASCADE
			) ENGINE=INNODB"
		);
		\APLib\DB::query(
			"CREATE TABLE IF NOT EXISTS sessions_settings(
				id VARCHAR(150) NOT NULL,
				dark_mode BOOLEAN NOT NULL DEFAULT FALSE,
				lockscreen BOOLEAN NOT NULL DEFAULT FALSE,
				lockscreen_timeout INT NOT NULL DEFAULT 30,
				theme INT NOT NULL DEFAULT 1,
				PRIMARY KEY (id),
				CONSTRAINT FK_sessions_settings_id FOREIGN KEY (id) REFERENCES sessions(id) ON UPDATE CASCADE ON DELETE CASCADE
			) ENGINE=INNODB"
		);
	}
}
?>
