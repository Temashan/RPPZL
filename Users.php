<?php

class Users
{
    private static $server="localhost";
    private static $login="root";
    private static $password="";
    private static $dbName="labsdb";

    static function openConnection()
    {
        return new mysqli(Users::$server,Users::$login,Users::$password,Users::$dbName);
    }

    static function closeConnection($connection)
    {
        $connection->close();
    }

    static function getList()
    {
        $connection=Users::openConnection();
        $sql="SELECT * FROM Users";
        $response=$connection->query($sql);
        $result=array();
        foreach ($response as $row)
        {
            $temp=array(
              "id" => $row['id'],
              "firstname" => $row['firstname'],
              "lastname" => $row['lastname'],
              "email" => $row['email'],
              "phone" => $row['phone']
            );
            $result[] = $temp;
        }
        Users::closeConnection($connection);
        return $result;
    }

    static function add($firstname,$lastname,$email,$phone)
    {
        $connection=Users::openConnection();
        $sql="INSERT INTO Users(firstname,lastname,email,phone) VALUES (\"$firstname\",\"$lastname\",\"$email\",\"$phone\")";
        $connection->query($sql);
        $rows=Users::getList();
        $id=$rows[count($rows)-1]["id"];
        Users::closeConnection($connection);
        return $id;
    }

    static function delete($id)
    {
        $connection=Users::openConnection();
        $sql="DELETE FROM Users WHERE id=$id";
        $connection->query($sql);
        Users::closeConnection($connection);
    }

    static function update($id,$property,$value)
    {
        if ($property!="id")
        {
            $connection=Users::openConnection();
            $sql="UPDATE Users SET $property=\"$value\" WHERE id=$id";
            $connection->query($sql);
            Users::closeConnection($connection);
        }
    }
}
