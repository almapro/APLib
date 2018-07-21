<?php
  namespace APLib\Users;

  /**
   * Privilages - A class to manage privilages
   */
  class Privilages
  {

    /**
     * Check a privilage for a specific group
     *
     * @param   string  $group  group to check a privilage for
     * @param   string  $priv   privilage to check
     *
     * @return  bool
     */
    public static function check($group, $priv)
    {
      if(!static::enabled($priv) || !\APLib\Users\Privilages\Groups::check($group)) return false;
      $count = 0;
      $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM privs_given WHERE group_name = ? AND priv_name = ?");
      $stmt->bind_param('ss', $group, $priv);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($count);
      $stmt->fetch();
      return ($count > 0);
    }

    /**
     * Add a privilage to a specific group
     *
     * @param   string  $group  group to add a privilage for
     * @param   string  $priv   privilage to add
     *
     * @return  bool
     */
    public static function add($group, $priv)
    {
      if(static::check($group, $priv)) return false;
      $stmt  = \APLib\DB::prepare("INSERT INTO privs_given(group_name, priv_name) VALUES(?, ?)");
      $stmt->bind_param('ss', $group, $priv);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Remove a privilage to a specific group
     *
     * @param   string  $group  group to remove a privilage from
     * @param   string  $priv   privilage to remove
     *
     * @return  bool
     */
    public static function remove($group, $priv)
    {
      if(!static::check($group, $priv)) return false;
      $stmt  = \APLib\DB::prepare("DELETE FROM privs_given WHERE group_name = ?, priv_name = ?");
      $stmt->bind_param('ss', $group, $priv);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Check if a specific privilage is enabled
     *
     * @param   string  $priv  privilage to check
     *
     * @return  bool
     */
    public static function enabled($priv)
    {
      $count = 0;
      $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM privs WHERE enabled = 1 AND priv_name = ?");
      $stmt->bind_param('s', $priv);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($count);
      $stmt->fetch();
      return ($count > 0);
    }

    /**
     * Disable a specific privilage
     *
     * @param   string  $priv  privilage to disable
     *
     * @return  bool
     */
    public static function disable($priv)
    {
      if(!static::enabled($priv)) return false;
      $stmt  = \APLib\DB::prepare("UPDATE privs SET enabled = 0 WHERE priv_name = ?");
      $stmt->bind_param('s', $priv);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Enable a specific privilage
     *
     * @param   string  $priv  privilage to enable
     *
     * @return  bool
     */
    public static function enable($priv)
    {
      if(static::enabled($priv)) return false;
      $stmt  = \APLib\DB::prepare("UPDATE privs SET enabled = 1 WHERE priv_name = ?");
      $stmt->bind_param('s', $priv);
      $stmt->execute();
      $stmt->fetch();
      return ($stmt->affected_rows > 0);
    }

    public static function table()
    {
      \APLib\DB::query(
        "CREATE TABLE IF NOT EXISTS privs(
          id INT NOT NULL AUTO_INCREMENT,
          enabled BOOLEAN NOT NULL DEFAULT TRUE,
          priv_name VARCHAR(60) NOT NULL,
          description VARCHAR(60) NOT NULL,
          INDEX (priv_name),
          INDEX (description),
          PRIMARY KEY (id, priv_name)
        ) ENGINE=INNODB"
      );
      \APLib\DB::query(
        "CREATE TABLE IF NOT EXISTS privs_given(
          id INT NOT NULL AUTO_INCREMENT,
          group_name VARCHAR(60) NOT NULL,
          priv_name VARCHAR(60) NOT NULL,
          PRIMARY KEY (id),
          CONSTRAINT FK_pg_gn FOREIGN KEY (group_name) REFERENCES groups_privs(group_name) ON UPDATE CASCADE ON DELETE CASCADE,
          CONSTRAINT FK_pg_pn FOREIGN KEY (priv_name) REFERENCES privs(priv_name) ON UPDATE CASCADE ON DELETE CASCADE
        ) ENGINE=INNODB"
      );
    }
  }

?>
