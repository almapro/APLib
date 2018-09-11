<?php
  namespace APLib\Users;

  /**
   * Sessions - A class to manage sessions
   */
  class Sessions
  {

    /**
     * @var  string  $id  current session's id
     */
    private static $id  =  '';

    /**
     * @var  string  $username  current username
     */
    private static $username  =  '';

    /**
     * @var  string  $account  current account
     */
    private static $account  =  '';

    /**
     * Return current session's id
     *
     * @return  string
     */
    public static function id() { return static::$id; }

    /**
     * Return current username
     *
     * @return  string
     */
    public static function username() { return static::$username; }

    /**
     * Return current account's data
     *
     * @return  array
     */
    public static function account() { return static::$account; }

    /**
     * Check if the client is logged in
     *
     * @return  bool
     */
    public static function logged()
    {
        if(!isset($_COOKIE[\APLib\Config::get('Cookie Name')])) return false;
        $stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c,username FROM accounts WHERE username IN (SELECT username FROM sessions WHERE id = ?) AND enabled = 1");
        $stmt->bind_param('s', $_COOKIE[\APLib\Config::get('Cookie Name')]);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count, $user);
        $stmt->fetch();
        static::$username = $user;
        static::$account  = \APRadius\Users::account($user);
        static::$id       = $_COOKIE[\APLib\Config::get('Cookie Name')];
        if($count == 1) return true;
        static::destroy(static::$id);
        setcookie(\APLib\Config::get('Cookie Name'), '', -1, '/');
        return false;
    }

    /**
     * Create a session
     *
     * @param   string  $account   an account to create a session for
     *
     * @return  bool
     */
    public static function create($account)
    {
      $cookie = \APLib\Extras::RandomString();
      $device = \APLib\Security::identify();
      $now    = date("Y-m-d H:i:s");
      $stmt   = \APLib\DB::prepare("INSERT INTO sessions VALUES(?,?,?,?,?,?,?,?)");
      $stmt->bind_param('ssssssss', $cookie, $account, $device['os'], $device['agent'], $now, $now, $device['ip'], $device['ip']);
      $stmt->execute();
      if($stmt->affected_rows > 0)
      {
        setcookie(\APLib\Config::get('Cookie Name'), $cookie, time() + \APLib\Config::get('Cookie Timeout'), '/');
        return true;
      }
      return false;
    }

    /**
     * Destroy a session
     *
     * @param   string  $session   session to destroy
     *
     * @return  bool
     */
    public static function destroy($session)
    {
      $stmt = \APLib\DB::prepare("DELETE FROM sessions WHERE id = ?");
      $stmt->bind_param('s', $session);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Run Sessions
     *
     * @return  void
     */
    public static function run()
    {
      if(static::logged() && !\APLib\Request\HTTP::post())
      {
        $device = \APLib\Security::identify();
        $now    = date("Y-m-d H:i:s");
        $stmt   = \APLib\DB::prepare("UPDATE sessions SET os = ?, agent = ?, last_login = ?, last_ip = ? WHERE id = ?");
        $stmt->bind_param('sssss', $device['os'], $device['agent'], $now, $device['ip'], $_COOKIE[\APLib\Config::get('Cookie Name')]);
        $stmt->execute();
      }
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
            CONSTRAINT FK_username FOREIGN KEY (username) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE CASCADE
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
            CONSTRAINT FK_sesssion_id FOREIGN KEY (id) REFERENCES sessions(id) ON UPDATE CASCADE ON DELETE CASCADE
          ) ENGINE=INNODB"
        );
    }
  }

?>
