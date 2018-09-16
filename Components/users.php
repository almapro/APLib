<?php
	namespace APLib;

	/**
	 * Users - A class to manage users'
	 */
	class Users
	{

		public static function all($offset = 0, $limit = 25, $owner = null)
		{
			$users = array();
			$query = "SELECT username FROM accounts LIMIT ?,?";
			if($owner != null)
			{
				$fromType  = static::account($owner)['type'];
				$fromLevel = \APLib\Users\Types::level($fromType);
				$toLevel   = \APLib\Users\Types::levels();
				$query     = "SELECT username FROM accounts WHERE (owner OR username) IN (";
				for($i=($fromLevel+1); $i < ($toLevel+2); $i++) $query .= "SELECT username FROM accounts WHERE (owner OR username) IN (";
				$query    .= "SELECT username FROM accounts WHERE username = ?)";
				for($i=$fromLevel; $i < ($toLevel+1); $i++) $query .= ")";
				$query    .= " LIMIT ?,?";
			}
			$stmt  = \APLib\DB::prepare($query);
			if($owner != null) $stmt->bind_param('sii', $owner, $offset, $limit);
			else $stmt->bind_param('ii', $offset, $limit);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($username);
			while($stmt->fetch()) array_push($users, $username);
			return $users;
		}

		public static function id($username)
		{
			$stmt = \APLib\DB::prepare("SELECT id FROM accounts WHERE username = ?");
			$stmm->bind_result('s', $username);
			$stmt->execute();
			$stmt->store_result();
			$stmm->bind_result($id);
			$stmt->fetch();
			return $id;
		}

		public static function total()
		{
			$stmt = \APLib\DB::prepare("SELECT COUNT(*) AS C FROM accounts");
			$stmt->execute();
			$stmt->store_result();
			$stmm->bind_result($c);
			$stmt->fetch();
			return $c;
		}

		public static function path($from, $to)
		{
			$fromType  = static::account($from)['type'];
			$toType    = static::account($to)['type'];
			$fromLevel = \APLib\Users\Types::level($fromType);
			$toLevel   = \APLib\Users\Types::level($toType);
			$query     = "SELECT l{$fromLevel}.username AS level{$fromLevel}";
	        for($i=($fromLevel+1); $i < ($toLevel+1); $i++) $query .= ", l{$i}.username AS level{$i}";
	        $query    .= " FROM accounts as l{$fromLevel} ";
	        for($i=($fromLevel+1); $i < ($toLevel+1); $i++)
	        {
	            $oldI   = $i-1;
	            $query .= "LEFT JOIN accounts AS l{$i} ON l{$i}.owner = l{$oldI}.username OR l{$i}.owner = l{$oldI}.owner ";
	        }
			$query    .= "WHERE l{$fromLevel}.owner = ? AND l{$toLevel}.username = ? LIMIT 1";
			$stmt      = \APLib\DB::prepare($query);
			$stmt->bind_param('ss', $from, $to);
			$stmt->execute();
			$stmt->store_result();
			$variables = array();
	        $data      = array();
	        $meta      = $stmt->result_metadata();
	        while($field = $meta->fetch_field()) $variables[] = &$data[$field->name];
			call_user_func_array(array($stmt, "bind_result"), $variables);
			$stmt->fetch();
			$final_path = array();
			foreach($variables as $user) if(!in_array($user, $final_path)) array_push($final_path, $user);
			return $final_path;
		}

		public static function check($username)
		{
			$stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM accounts WHERE username = ?");
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($c);
			$stmt->fetch();
			return ($c > 0);
		}

		public static function add($username, $type, $password, $sha256 = false)
	    {
			if(!$sha256) $password = hash('sha256', $password);
			$stmt = \APLib\DB::prepare("INSERT INTO accounts(username, password, account_type) SELECT * FROM (SELECT ? AS username, ? AS password, ? AS account_type) AS tmp WHERE NOT EXISTS (SELECT * FROM accounts WHERE username = ?) LIMIT 1");
			$stmt->bind_param('ssss', $username, $password, $type, $username);
			$stmt->execute();
			return ($stmt->affected_rows > 0);
		}

	    public static function update($username, $email, $firstname, $lastname)
	    {
			$stmt = \APLib\DB::prepare("UPDATE accounts SET email = ?, first_name = ?, last_name = ? WHERE username = ?");
			$stmt->bind_param('ssss', $email, $firstname, $lastname, $username);
			$stmt->execute();
			return ($stmt->affected_rows > 0);
		}

		public static function avatar($username, $avatar)
		{
			$stmt = \APLib\DB::prepare("UPDATE accounts SET avatar = ? WHERE username = ?");
			$stmt->bind_param('ss', $avatar, $username);
			$stmt->execute();
			return ($stmt->affected_rows > 0);
		}

	    public static function password($username, $password, $sha256 = false)
	    {
			if(!$sha256) $password = hash('sha256', $password);
			$stmt = \APLib\DB::prepare("UPDATE accounts SET password = ? WHERE username = ?");
			$stmt->bind_param('ss', $password, $username);
			$stmt->execute();
			return ($stmt->affected_rows > 0);
		}

		public static function delete($account)
	    {
	      $stmt = \APLib\DB::prepare("DELETE FROM accounts WHERE username = ?");
	      $stmt->bind_param("s", $account);
	      $stmt->execute();
	      return ($stmt->affected_rows > 0);
	    }

		public static function login($username, $password, $sha256 = false)
		{
			if(!$sha256) $password = hash('sha256', $password);
			$stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM accounts WHERE username = ? AND password = ?");
			$stmt->bind_param('ss', $username, $password);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($c);
			$stmt->fetch();
			return ($c > 0) ? \APLib\Users\Sessions::create($username) : false;
		}

		public static function logout()
		{
			if(!isset($_COOKIE[\APLib\Config::get("Cookie Name")])) return false;
			return \APLib\Users\Sessions::destroy($_COOKIE[\APLib\Config::get("Cookie Name")]);
		}

		public static function account($username)
		{
			$info = array();
			$stmt = \APLib\DB::prepare("SELECT enabled, account_type, email, avatar, first_name, last_name FROM accounts WHERE username = ?");
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($enabled, $account_type, $email, $avatar, $firstname, $lastname);
			if($stmt->fetch())
			{
				$info = array(
					'enabled'    => $enabled,
					'username'   => $username,
					'type'       => $account_type,
					'email'      => $email,
					'first name' => $firstname,
					'last name'  => $lastname,
					'avatar'     => $avatar,
					'group'      => \APLib\Groups\Users::get($username)
				);
			}
			return $info;
		}

		public static function table()
		{
			\APLib\Users\Types::table();
			$default_avatar = \APLib\Extras::NormalizePath(APLibHTML.'../imgs/avatars/default.png');
			\APLib\DB::query(
				"CREATE TABLE IF NOT EXISTS accounts(
					id INT NOT NULL AUTO_INCREMENT,
					enabled BOOLEAN NOT NULL DEFAULT TRUE,
					username VARCHAR(60) NOT NULL,
					password TEXT NOT NULL,
					account_type VARCHAR(60) NOT NULL,
					email VARCHAR(50) NOT NULL,
					first_name VARCHAR(25) NOT NULL,
					last_name VARCHAR(25) NOT NULL,
					avatar VARCHAR(255) NOT NULL DEFAULT '{$default_avatar}',
					INDEX (username),
					INDEX (email),
					INDEX full_name (first_name, last_name),
					PRIMARY KEY (id, username),
					CONSTRAINT FK_at_tn FOREIGN KEY (account_type) REFERENCES accounts_types(name) ON UPDATE CASCADE ON DELETE RESTRICT
				) ENGINE=INNODB"
			);
			\APLib\Users\Sessions::table();
		}
	}
?>
