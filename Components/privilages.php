<?php
    namespace APLib;

    /**
     * Privilages - A class to manage privilages
     */
    class Privilages
    {

        /*******/
        public static function add($name, $desc, $for)
        {
            $stmt = \APLib\DB::prepare("INSERT INTO privilages(name, description, fortype) SELECT * FROM (SELECT ? AS name, ? AS description, ? AS fortype) AS tmp WHERE NOT EXISTS (SELECT * FROM privilages WHERE name = ?) LIMIT 1");
            $stmt->bind_param('ssss', $name, $desc, $for, $name);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        /*******/
        public static function delete($name)
        {
            $stmt = \APLib\DB::prepare("DELETE FROM privilages WHERE name = ?");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            return ($stmt->affected_rows > 0);
        }

        /**
         * Check if a specific privilage is enabled
         *
         * @param   string  $priv  privilage to check
         *
         * @return  bool
         */
        public static function enabled($name)
        {
          $stmt = \APLib\DB::prepare("SELECT COUNT(*) AS c FROM privilages WHERE enabled = 1 AND name = ?");
          $stmt->bind_param('s', $name);
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
        public static function disable($name)
        {
          $stmt = \APLib\DB::prepare("UPDATE privilages SET enabled = 0 WHERE name = ?");
          $stmt->bind_param('s', $name);
          $stmt->execute();
          return ($stmt->affected_rows > 0);
        }

        /**
         * Enable a specific privilage
         *
         * @param   string  $priv  privilage to enable
         *
         * @return  bool
         */
        public static function enable($name)
        {
          $stmt = \APLib\DB::prepare("UPDATE privilages SET enabled = 1 WHERE name = ?");
          $stmt->bind_param('s', $name);
          $stmt->execute();
          return ($stmt->affected_rows > 0);
        }

        public static function all()
        {
            $privilages = array();
            $stmt  = \APLib\DB::prepare("SELECT name,description FROM privilages");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($name, $desc);
            while($stmt->fetch()) array_push($privilages, array($name => $desc));
            return $privilages;
        }

        public static function groups()
        {
            $privilages = array();
            $stmt  = \APLib\DB::prepare("SELECT name,description FROM privilages WHERE fortype = 'group'");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($name, $desc);
            while($stmt->fetch()) array_push($privilages, array($name => $desc));
            return $privilages;
        }

        public static function tokens()
        {
            $privilages = array();
            $stmt       = \APLib\DB::prepare("SELECT name,description FROM privilages WHERE fortype = 'token'");
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($name, $desc);
            while($stmt->fetch()) array_push($privilages, array($name => $desc));
            return $privilages;
        }

        public static function table()
        {
            \APLib\DB::query(
              "CREATE TABLE IF NOT EXISTS privilages(
                id INT NOT NULL AUTO_INCREMENT,
                enabled BOOLEAN NOT NULL DEFAULT TRUE,
                name VARCHAR(60) NOT NULL,
                description VARCHAR(60) NOT NULL,
                fortype ENUM('group', 'token') NOT NULL DEFAULT 'token',
                INDEX (name),
                INDEX (description),
                INDEX (fortype),
                PRIMARY KEY (id, name)
              ) ENGINE=INNODB"
            );
        }
    }

?>
