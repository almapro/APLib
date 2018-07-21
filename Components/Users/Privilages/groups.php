<?php
  namespace APLib\Users\Privilages;

  /**
   * Groups - A class to manage groups
   */
  class Groups
  {

    /**
     * Forbidden groups are default ones. SHOULD NOT BE EDITED UNLESS RENAMED
     */
    private static $forbidden = array('Admins', 'Supervisors', 'Users');

    /**
     * Get all groups
     *
     * @return  array
     */
    public static function all()
    {
      $name = ''; $desc = '';
      $groups = array();
      $stmt   = \APLib\DB::prepare("SELECT group_name,description FROM groups_privs");
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($name, $desc);
      while($stmt->fetch())
      {
        $username = '';
        $members  = array();
        $mmbrs = \APLib\DB::prepare("SELECT username FROM users_group WHERE group_name = ?");
        $mmbrs->bind_param('s', $name);
        $mmbrs->execute();
        $mmbrs->store_result();
        $mmbrs->bind_result($username);
        while($mmbrs->fetch())
        {
          $info = \APLib\Users::account($username);
          $members[$username] = array('type' => $info['type'], 'name' => $info['full name']);
        }
        $groups[$name] = array('description' => $desc, 'members' => $members);
      }
      return $groups;
    }

    /**
     * Check if a group exists
     *
     * @param   string  $group  group to check
     *
     * @return  bool
     */
    public static function check($group)
    {
      $count = 0;
      $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM groups_privs WHERE group_name = ?");
      $stmt->bind_param('s', $group);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($count);
      $stmt->fetch();
      return ($count > 0);
    }

    /**
     * Add a group
     *
     * @param   string  $name  group's name
     * @param   string  $desc  group's description
     *
     * @return  bool
     */
    public static function add($name, $desc)
    {
      if(static::check($name)) return false;
      if(in_array($group, static::$forbidden)) return false;
      $stmt  = \APLib\DB::prepare("INSERT INTO groups_privs(group_name, description) VALUES(?, ?)");
      $stmt->bind_param('ss', $name, $desc);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Edit a group's name & description
     *
     * @param   string  $group  group to edit
     * @param   string  $name   new name
     * @param   string  $desc   new description
     *
     * @return  bool
     */
    public static function edit($group, $name, $desc)
    {
      if(!static::check($group)) return false;
      if(in_array($group, static::$forbidden)) return false;
      $stmt  = \APLib\DB::prepare("UPDATE groups_privs SET group_name = ?, description = ? WHERE group_name = ?");
      $stmt->bind_param('sss', $name, $desc, $group);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

    /**
     * Remove a group
     *
     * @param   string  $group  group to remove
     *
     * @return  bool
     */
    public static function remove($group)
    {
      if(!static::check($group)) return false;
      if(in_array($group, static::$forbidden)) return false;
      $stmt  = \APLib\DB::prepare("DELETE FROM groups_privs WHERE group_name = ?");
      $stmt->bind_param('s', $group);
      $stmt->execute();
      return ($stmt->affected_rows > 0);
    }

    public static function table()
    {
      \APLib\DB::query(
        "CREATE TABLE IF NOT EXISTS groups_privs(
          id INT NOT NULL AUTO_INCREMENT,
          group_name VARCHAR(60) NOT NULL,
          description VARCHAR(60) NOT NULL,
          INDEX (group_name),
          INDEX (description),
          PRIMARY KEY (id, group_name)
        ) ENGINE=INNODB"
      );
      $groups = array(
        'Admins'      => 'Admins',
        'Supervisors' => 'Supervisors',
        'Users'       => 'Users'
      );
      foreach($groups as $name => $desc)
      {
        static::add($name, $desc);
      }
    }
  }

?>
