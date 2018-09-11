<?php
  namespace APLib;

  /**
   * Groups - A class to manage groups
   */
  class Groups
  {

    /**
     * Get all groups
     *
     * @return  array
     */
    public static function all($offset = 0 , $limit = 25)
    {
        $groups = array();
        $stmt   = \APLib\DB::prepare("SELECT name,description,fortype FROM privilages_groups LIMIT ?,?");
        $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name, $desc, $for);
        while($stmt->fetch())
        {
            $mmbrs = \APLib\DB::prepare("SELECT COUNT(*) as c FROM users_group WHERE name = ?");
            $mmbrs->bind_param('s', $name);
            $mmbrs->execute();
            $mmbrs->store_result();
            $mmbrs->bind_result($c);
            $mmbrs->fetch();
            $groups[$name] = array('description' => $desc, 'for' => $for, 'special' => \APLib\Groups\Privilages::special($name), 'members' => $c);
        }
        return $groups;
    }

    public static function members($name, $offset = 0, $limit = 25)
    {
        $members = array();
        $smt     = \APLib\DB::prepare("SELECT username FROM users_group WHERE name = ? LIMIT ?,?");
        $smt->bind_param('sii', $name, $offset, $limit);
        $smt->execute();
        $smt->store_result();
        $smt->bind_result($username);
        while($smt->fetch())
        {
            $info = \APLib\Users::account($username);
            $members[$username] = array('type' => $info['type'], 'name' => $info['full name'], 'enabled' => $info['enabled']);
        }
        return $members;
    }

    /**
     * Get all groups for a specific type
     *
     * @return  array
     */
    public static function fortype($type, $offset = 0, $limit = 25)
    {
        $groups = array();
        $stmt   = \APLib\DB::prepare("SELECT name,description FROM privilages_groups WHERE fortype = ? LIMIT ?,?");
        $stmt->bind_param('sii', $type, $offset, $limit);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name, $desc);
        while($stmt->fetch()) array_push($groups, array('name' => $name, 'description' => $desc));
        return $groups;
    }

    /**
     * Get a user's group
     *
     * @return  array
     */
    public static function joined($member)
    {
        $group = \APLib\Groups\Users::get($member);
        $stmt  = \APLib\DB::prepare("SELECT name,description,fortype FROM privilages_groups WHERE name = ?");
        $stmt->bind_param('s', $group);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name, $desc, $for);
        $stmt->fetch();
        return array('description' => $desc, 'for' => $for, 'special' => \APLib\Groups\Privilages::special($name));
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
        $stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM privilages_groups WHERE name = ?");
        $stmt->bind_param('s', $group);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        return ($count > 0);
    }

    /**
     * Check if a group with these privilages exist
     *
     * @param   array  $privs  list of privilages to check
     *
     * @return  bool
     */
    public static function equal($privs)
    {
        $groups = array();
        $stmt   = \APLib\DB::prepare("SELECT name FROM privilages_groups");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($group);
        while($stmt->fetch()) if(\APLib\Groups\Privilages::given($group) == $privs)  array_push($groups, $group);
        return (sizeof($groups) > 0);
    }

    /**
     * Check if a group is mean for a specific type of accounts
     *
     * @param   string  $group  group to check
     * @param   string  $for    type to check
     *
     * @return  bool
     */
    public static function for($group, $for)
    {
        $stmt  = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM privilages_groups WHERE name = ? AND fortype = ?");
        $stmt->bind_param('ss', $group, $for);
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
    public static function add($name, $desc, $for)
    {
        $owner = (\APLib\Users\Sessions::logged()) ? \APLib\Users\Sessions::username() : '';
        $stmt  = \APLib\DB::prepare("INSERT INTO privilages_groups(name, description, fortype) SELECT * FROM (SELECT ? AS name, ? AS description, ? AS fortype) AS tmp WHERE NOT EXISTS (SELECT * FROM privilages_groups WHERE name = ?) LIMIT 1");
        $stmt->bind_param('ssss', $name, $desc, $for, $name);
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
        $stmt  = \APLib\DB::prepare("UPDATE privilages_groups SET name = ?, description = ? WHERE name = ?");
        $stmt->bind_param('sss', $name, $desc, $group);
        $stmt->execute();
        return ($stmt->affected_rows > 0);
    }

    /**
     * Delete a group
     *
     * @param   string  $group  group to delete
     *
     * @return  bool
     */
    public static function delete($group)
    {
        $stmt  = \APLib\DB::prepare("DELETE FROM privilages_groups WHERE name = ?");
        $stmt->bind_param('s', $group);
        $stmt->execute();
        return ($stmt->affected_rows > 0);
    }

    public static function table()
    {
        \APLib\Privilages::table();
        \APLib\DB::query(
          "CREATE TABLE IF NOT EXISTS privilages_groups(
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(60) NOT NULL,
            priv_name VARCHAR(60) NOT NULL,
            INDEX (name),
            PRIMARY KEY (id, name),
            CONSTRAINT FK_pg_pn FOREIGN KEY (priv_name) REFERENCES privilages(name) ON UPDATE CASCADE ON DELETE RESTRICT
          ) ENGINE=INNODB"
        );
        \APLib\Groups\Privilages::table();
        \APLib\Groups\Users::table();
    }
  }

?>
