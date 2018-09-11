<?php
  namespace APLib\Groups;

  /**
   * Users - A class to manage users' groups
   */
  class Users
  {

    /**
     * Set group's name for a specific user
     *
     * @param   string  $user   user to set group for
     * @param   string  $group  group's name
     *
     * @return  bool
     */
    public static function set($user, $group)
    {
        $query = "UPDATE users_group SET group_name = ? WHERE username = ?";
        if(static::get($user) == "")
          $query = "INSERT INTO users_group(group_name, username) VALUES(?, ?)";
        $stmt  = \APLib\DB::prepare($query);
        $stmt->bind_param('ss', $group, $user);
        $stmt->execute();
        return ($stmt->affected_rows > 0);
    }

    /**
     * Get group's name for a specific user
     *
     * @param   string  $user   user to get group's name for
     *
     * @return  string
     */
    public static function get($user)
    {
        $stmt  = \APLib\DB::prepare("SELECT group_name FROM users_group WHERE username = ?");
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($group);
        $stmt->fetch();
        return $group;
    }

    public static function table()
    {
        \APLib\DB::query(
          "CREATE TABLE IF NOT EXISTS users_group(
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(60) NOT NULL,
            group_name VARCHAR(60) NOT NULL,
            INDEX (username),
            INDEX (group_name),
            PRIMARY KEY (id, username),
            CONSTRAINT FK_ug_un FOREIGN KEY (username) REFERENCES accounts(username) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT FK_ug_gn FOREIGN KEY (group_name) REFERENCES privilages_groups(name) ON UPDATE CASCADE ON DELETE RESTRICT
          ) ENGINE=INNODB"
        );
    }
  }

?>
