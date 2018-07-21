<?php
namespace APLib;

/**
 * Users - A class to manage users'
 */
class Users
{

	public static function init()
	{
		\APLib\DB::init(
			\APLib\Config::get('DB Host'),
			\APLib\Config::get('DB Name'),
			\APLib\Config::get('DB User'),
			\APLib\Config::get('DB Pass')
		);
	}

	public static function all()
	{
		$users = array();
		$stmt  = \APLib\DB::prepare("SELECT username FROM accounts");
		$stmt->execute();
		$username = '';
		$stmt->store_result();
		$stmt->bind_result($username);
		while($stmt->fetch())
		{
			array_push($users, $username);
		}
		return $users;
	}

	public static function check($account)
	{
		$stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM accounts WHERE username = ?");
		$stmt->bind_param('s', $account);
		$stmt->execute();
		$c= 0;
		$stmt->store_result();
		$stmt->bind_result($c);
		$stmt->fetch();
		return ($c > 0);
	}

	public static function add($username, $password, $type, $email = '')
    {
		$password = md5($password);
		$stmt = \APLib\DB::prepare("INSERT INTO accounts(username, first_name, password, account_type, email) VALUES(?, ?, ?, ?, ?)");
		if($stmt == null) return false;
		$stmt->bind_param("sssss", $username, $username, $password, $type, $email);
		$stmt->execute();
		if($stmt->affected_rows > 0) return true;
		return false;
	}

    public static function update($username, $type, $email, $firstname, $lastname, $avatar)
    {
		$stmt = \APLib\DB::prepare("UPDATE accounts SET account_type = ?, email = ? WHERE username = ?");
		if($stmt == null) return false;
		$stmt->bind_param("ss", $type, $email, $username);
		$stmt->execute();
		return ($stmt->affected_rows > 0);
	}

	public static function remove($account)
    {
      $stmt = \APLib\DB::prepare("DELETE FROM accounts WHERE username = ?");
      if($stmt == null) return false;
      $stmt->bind_param("s", $account);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

	public static function login($username, $password)
	{
		$password = md5($password);
		$stmt     = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM accounts WHERE username = ? AND password = ?");
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
		$c = 0;
		$stmt->store_result();
		$stmt->bind_result($c);
		$stmt->fetch();
		if($c > 0)
		{
			\APLib\Users\Sessions::create($username);
			return true;
		}
		return false;
	}

	public static function logout()
	{
		if(!isset($_COOKIE[\APLib\Config::get("Cookie Name")])) return false;
		return \APLib\Users\Sessions::remove($_COOKIE[\APLib\Config::get("Cookie Name")]);
	}

	public static function account($username)
	{
		if($username == null) return null;
		$stmt = \APLib\DB::prepare(
			"SELECT account_type, email, avatar, first_name, last_name FROM accounts WHERE username = ?"
		);
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$account_type = ''; $email = ''; $avatar = ''; $firstname = ''; $lastname = '';
		$stmt->store_result();
		$stmt->bind_result($account_type, $email, $avatar, $firstname, $lastname);
		if($stmt->fetch())
		{
			return array(
				'username'         =>  $username,
				'type'             =>  $account_type,
				'email'            =>  $email,
				'avatar'           =>  $avatar,
				'first name'       =>  $firstname,
				'last name'        =>  $lastname
			);
		}
		return null;
	}

	public static function table()
	{
		$default_avatar = \APLib\Extras::NormalizePath(APLibHTML.'../imgs/avatars/default.png');
		\APLib\DB::query(
			"CREATE TABLE IF NOT EXISTS accounts(
				id INT NOT NULL AUTO_INCREMENT,
				enabled BOOLEAN NOT NULL DEFAULT TRUE,
				username VARCHAR(60) NOT NULL,
				password TEXT NOT NULL,
				account_type TEXT NOT NULL,
				email VARCHAR(50) NOT NULL,
				first_name VARCHAR(25) NOT NULL,
				last_name VARCHAR(25) NOT NULL,
				avatar VARCHAR(255) NOT NULL DEFAULT '{$default_avatar}',
				INDEX (username),
				INDEX (account_type),
				INDEX (email),
				INDEX full_name (first_name, last_name),
				PRIMARY KEY (id, username)
			) ENGINE=INNODB"
		);
		\APLib\Users\Tokens::table();
		\APLib\Users\Sessions::table();
		\APLib\Users\Privilages::table();
		\APLib\Users\Privilages\Groups::table();
		\APLib\Users\Privilages\Users::table();
	}
}
?>
