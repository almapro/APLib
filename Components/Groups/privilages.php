<?php
  namespace APLib\Groups;

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
      if(!static::enabled($priv)) return false;
      $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM groups_privs WHERE name = ? AND priv_name = ?");
      $stmt->bind_param('ss', $group, $priv);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($count);
      $stmt->fetch();
      return ($count > 0);
    }

    /**
     * Check if a group has a special privilage
     *
     * @param   string  $group  group to check a privilage for
     *
     * @return  bool
     */
    public static function special($group)
    {
      $priv = '%_ALL_%';
      $stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM groups_privs WHERE name = ? AND priv_name LIKE ?");
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
      $stmt  = \APLib\DB::prepare("INSERT INTO groups_privs(name, priv_name) SELECT * FROM (SELECT ? AS name, ? AS priv_name) AS tmp WHERE NOT EXISTS (SELECT * FROM groups_privs WHERE name = ?) LIMIT 1");
      $stmt->bind_param('sss', $group, $priv, $group);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Delete a privilage from a specific group
     *
     * @param   string  $group  group to delete a privilage from
     * @param   string  $priv   privilage to delete
     *
     * @return  bool
     */
    public static function delete($group, $priv)
    {
        $stmt  = \APLib\DB::prepare("DELETE FROM groups_privs WHERE name = ?, priv_name = ?");
        $stmt->bind_param('ss', $group, $priv);
        $stmt->execute();
        return ($stmt->affected_rows > 0);
    }

    public static function given($group)
    {
        $privs = array();
        $stmt  = \APLib\DB::prepare("SELECT priv_name,description FROM privs WHERE priv_name IN (SELECT priv_name FROM groups_privs WHERE name = ?)");
        $stmt->bind_param('s', $group);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($priv, $desc);
        while($stmt->fetch()) array_push($privs, array($priv => $desc));
        return $privs;
    }

    public static function unused()
    {
        $privs = array();
        $stmt  = \APLib\DB::prepare("SELECT priv_name,description FROM privs WHERE priv_name NOT IN (SELECT priv_name FROM groups_privs)");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($priv, $desc);
        while($stmt->fetch()) array_push($privs, array($priv => $desc));
        return $privs;
    }

    public static function table()
    {
        \APLib\DB::query(
          "CREATE TABLE IF NOT EXISTS groups_privs(
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(60) NOT NULL,
            priv_name VARCHAR(60) NOT NULL,
            INDEX (name),
            INDEX (priv_name),
            PRIMARY KEY (id, name),
            CONSTRAINT FK_gp_gn FOREIGN KEY (name) REFERENCES privilages_groups(priv_name) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT FK_gp_pn FOREIGN KEY (priv_name) REFERENCES privilages(name) ON UPDATE CASCADE ON DELETE RESTRICT
          ) ENGINE=INNODB"
        );
    }
  }

?>
