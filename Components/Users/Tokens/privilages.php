<?php
  namespace APLib\Users\Tokens;

  /**
   * Privilages - A class to manage tokens' privilages
   */
  class Privilages
  {

    /**
     * Check a privilage for a specific token
     *
     * @param   string  $token  token to check a privilage for
     * @param   string  $priv   privilage to check
     *
     * @return  bool
     */
    public static function check($token, $priv)
    {
      if(!\APLib\Privilages::enabled($priv)) return false;
      $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM tokens_privs WHERE token = ? AND priv_name = ?");
      $stmt->bind_param('ss', $token, $priv);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($count);
      $stmt->fetch();
      return ($count > 0);
    }

    /**
     * Add a privilage to a specific token
     *
     * @param   string  $token  token to add a privilage for
     * @param   string  $priv   privilage to add
     *
     * @return  bool
     */
    public static function add($token, $priv)
    {
      if(static::check($token, $priv)) return false;
      $stmt  = \APLib\DB::prepare("INSERT INTO tokens_privs(token, priv_name) SELECT * FROM (SELECT ? AS token, ? AS priv_name) AS tmp WHERE NOT EXISTS (SELECT * FROM tokens_privs WHERE token = ?) LIMIT 1");
      $stmt->bind_param('sss', $token, $priv, $token);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Delete a privilage to a specific token
     *
     * @param   string  $token  token to delete a privilage from
     * @param   string  $priv   privilage to delete
     *
     * @return  bool
     */
    public static function delete($token, $priv)
    {
      if(!static::check($token, $priv)) return false;
      $stmt  = \APLib\DB::prepare("DELETE FROM tokens_privs WHERE token = ?, priv_name = ?");
      $stmt->bind_param('ss', $token, $priv);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    public static function given($token)
    {
        $privs = array();
        $stmt  = \APLib\DB::prepare("SELECT priv_name,description FROM privs WHERE priv_name IN (SELECT priv_name FROM tokens_privs WHERE token = ?)");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($priv, $desc);
        while($stmt->fetch()) array_push($privs, array($priv => $desc));
        return $privs;
    }

    public static function unused()
    {
        $privs = array();
        $stmt  = \APLib\DB::prepare("SELECT priv_name,description FROM privs WHERE priv_name NOT IN (SELECT priv_name FROM tokens_privs)");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($priv, $desc);
        while($stmt->fetch()) array_push($privs, array($priv => $desc));
        return $privs;
    }

    public static function table()
    {
        \APLib\DB::query(
            "CREATE TABLE IF NOT EXISTS tokens_privs(
              id INT NOT NULL AUTO_INCREMENT,
              token VARCHAR(60) NOT NULL,
              priv_name VARCHAR(60) NOT NULL,
              PRIMARY KEY (id),
              CONSTRAINT FK_tp_t FOREIGN KEY (token) REFERENCES tokens(id) ON UPDATE CASCADE ON DELETE CASCADE,
              CONSTRAINT FK_tp_pn FOREIGN KEY (priv_name) REFERENCES privs(priv_name) ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=INNODB"
        );
    }
  }

?>
