<?php

class db {
    private $db;

    function __construct() {
        $database_name = "database.db";
        $this->db = new SQLite3($database_name);
        $query = "CREATE TABLE IF NOT EXISTS tiles (id INTEGER PRIMARY KEY AUTOINCREMENT, tile_name VARCHAR(50) NOT NULL, tile_url VARCHAR(50) NOT NULL, tile_image VARCHAR(50), tile_order INTEGER NOT NULL)";
        $this->db->exec($query);
        $query = "CREATE TABLE IF NOT EXISTS settings(id INTEGER PRIMARY KEY AUTOINCREMENT, setting VAR_CHAR(50) NOT NULL, `value` INTEGER NOT NULL)";
        $this->db->exec($query);
        $this->initSettings();
    }

    function initSettings() {
        $settings = array(
            "launchMethod" => 0
        );
        $rows = $this->db->query("SELECT COUNT(*) as count FROM settings");
        $row = $rows->fetchArray();
        $numRows = $row['count'];
        if ($numRows == 0) {
            foreach ($settings as $key => $value) {
                $query = $this->db->prepare("INSERT INTO settings (setting, `value`) values (:setting, :value)");
                $query->bindValue(":setting", $key);
                $query->bindValue(":value", $value);
                $query->execute();
            }
        }
    }

    function getTile($id): array {
        $query = $this->db->prepare("SELECT * FROM tiles WHERE id=:id");
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        return $query->execute()->fetchArray(SQLITE3_ASSOC);
    }

    function addNewTile($tile_name, $tile_url, $tile_image) {
        $query = "SELECT MAX(tile_order) FROM tiles";
        $count = $this->db->querySingle($query);
        $count++;
        $query = $this->db->prepare("INSERT INTO tiles (tile_name, tile_url, tile_image, tile_order) VALUES (:tile_name, :tile_url, :tile_image, :tile_order)");
        $query->bindValue(':tile_name', $tile_name, SQLITE3_TEXT);
        $query->bindValue(':tile_url', $tile_url, SQLITE3_TEXT);
        $query->bindValue(':tile_image', $tile_image, SQLITE3_TEXT);
        $query->bindValue(':tile_order', $count, SQLITE3_INTEGER);
        $query->execute();
    }

    function updateTile($id, $tile_name, $tile_url, $tile_image, $tile_order) {
        $query = $this->db->prepare("UPDATE tiles SET tile_name = :tile_name, tile_url = :tile_url, tile_image = :tile_image, tile_order = :tile_order WHERE id=:id");
        $query->bindValue(':tile_name', $tile_name, SQLITE3_TEXT);
        $query->bindValue(':tile_url', $tile_url, SQLITE3_TEXT);
        $query->bindValue(':tile_image', $tile_image, SQLITE3_TEXT);
        $query->bindValue(':tile_order', $tile_order, SQLITE3_INTEGER);
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        return $query->execute();
    }

    function getTiles(): array {
        $query = "SELECT * FROM tiles ORDER BY tile_order";
        $results = $this->db->query($query);
        $data = array();
        while ($res = $results->fetchArray(SQLITE3_ASSOC)) {
            array_push($data, $res);
        }

        return $data;
    }

    function deleteTile($id): SQLite3Result {
        $query = $this->db->prepare("DELETE FROM tiles WHERE id=:id");
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        return $query->execute();
    }

    function updateOrder($array) {
        for ($i = 0; $i < count($array); $i++) {
            $query = $this->db->prepare("UPDATE tiles SET tile_order=:tile_order WHERE id=:id");
            $query->bindValue(':tile_order', $i, SQLITE3_INTEGER);
            $query->bindValue(':id', $array[$i], SQLITE3_INTEGER);
            $query->execute();
        }
    }

    function getSettings(): array {
        $query = "SELECT * FROM settings";
        $results = $this->db->query($query);
        $data = array();
        while ($res = $results->fetchArray(SQLITE3_ASSOC)) {
            $data[$res["setting"]] = $res["value"];
        }
        return $data;
    }

    function updateSettings($settings) {
        foreach ($settings as $setting => $value) {
            $query = $this->db->prepare("UPDATE settings SET value=:value WHERE setting=:setting");
            $query->bindValue(':value', $value, SQLITE3_INTEGER);
            $query->bindValue(':setting', $setting, SQLITE3_TEXT);
            $query->execute();
        }
    }
}
