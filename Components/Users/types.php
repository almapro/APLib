<?php
    namespace APLib\Users;

    /**
     * Types - A class to manage users' types
     */
    class Types
    {

        public static function all($offset = 0 , $limit = 25)
        {
            $types = array();
            $stmt  = \APLib\DB::prepare("SELECT name, description FROM accounts_types LIMIT ?,?");
            $stmt->bind_result('ii', $offset, $limit);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($name, $desc);
            while($stmt->fetch()) array_push($types, array('name' => $name, 'description' => $desc));
            return $types;
        }

        public static function id($type)
        {
            $stmt = \APLib\DB::prepare("SELECT id FROM accounts_types WHERE name = ?");
            $stmt->bind_result('s', $type);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            return $id;
        }

        public static function levels()
        {
            $stmt = \APLib\DB::prepare("SELECT COUNT(*) AS C FROM accounts_types");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($c);
            return $c;
        }

        public static function level($type)
        {
            $id   = static::id($type);
            $stmt = \APLib\DB::prepare("SELECT (@row_number:=@row_number + 1),name FROM accounts_types,(SELECT @row_number:=0) ORDER BY id LIMIT ?");
            $stmt->bind_result('i', $id);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($c, $name);
            while($stmt->fetch()) if($name == $type) return $c;
            return -1;
        }

        public static function add($name, $desc)
        {
            $stmt = \APLib\DB::prepare("INSERT INTO accounts_types(name, description) SELECT * FROM (SELECT ? AS name, ? AS description) AS tmp WHERE NOT EXISTS (SELECT * FROM accounts_types WHERE name = ?) LIMIT 1");
            $stmt->bind_param('sss', $name, $desc, $name);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function delete($name)
        {
            $stmt = \APLib\DB::prepare("DELETE FROM accounts_types WHERE name = ?");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function rename($oldname, $newname)
        {
            $stmt = \APLib\DB::prepare("UPDATE accounts_types SET name = ? WHERE name = ?");
            $stmt->bind_param('ss', $newname, $oldname);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function update($name, $desc)
        {
            $stmt = \APLib\DB::prepare("UPDATE accounts_types SET description = ? WHERE name = ?");
            $stmt->bind_param('ss', $desc, $name);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        public static function table()
        {
            \APLib\DB::query(
                "CREATE TABLE IF NOT EXISTS accounts_types(
                    id INT NOT NULL AUTO_INCREMENT,
                    name VARCHAR(60) NOT NULL,
                    description TEXT NOT NULL,
                    INDEX (name),
                    PRIMARY KEY(id, name)
                ) ENGINE=INNODB"
            );
        }
    }

?>
