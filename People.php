<?php

class People
{
    private $id,
    private $firstname,
    private $lastname,
    private $birthday,
    private $gender,
    private $birthplace;

    const TABLE_NAME = 'people';

    public function __construct($id, $firstname, $lastname, $birthday, $gender, $birthplace)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->birthplace = $birthplace;
        $this->save();
    }

     public function save()
     {
         $mydb = new mysqli('server', 'username', 'password', 'db_name');

         if (!isset($this->id)) {
             $query = "INSERT INTO " . self::TABLE_NAME . " (firstname, lastname, birthday, gender, birthplace) VALUES (?, ?, ?, ?, ?)";
             $prepst = $mydb->prepare($query);
             $prepst->bind_param('sssis', $this->firstname, $this->lastname, $this->birthday, $this->gender, $this->birthplace);
             $prepst->execute();
             $prepst->id = $mydb->insert_id;
         } else {
             $query = "UPDATE " . self::TABLE_NAME . " SET firstname=?, lastname=?, birthday=?, gender=?, birthplace=? WHERE id=?";
             $prepst = $mydb->prepare($query);
             $prepst->bind_param('id', $this->firstname, $this->lastname, $this->birthday, $this->gender, $this->birthplace, $this->id);
             $prepst->execute();
         }
     }

    public function delete()
    {
        $mydb = new mysqli('server', 'username', 'password', 'db_name');
        if (isset($this->id)) {
            $query = "DELETE FROM " . self::TABLE_NAME . " WHERE id = '$this->id'";
            $prepst = $mydb->prepare($query);
            $prepst->bind_param('i', $this->id);
            $prepst->execute();
            $prepst->id = null;
        }
    }

    public static function agePeople($birthday)
    {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md') < date('md', $birthday_timestamp)) {
            $age--;
        }
        return $age;
    }


    public static function genderConversation($gender)
    {
        return $gender === 0 ? 'Man' : 'Woman';
    }

 public function getUser($include_age = false, $include_gender = false)
 {
     $new_user = new stdClass();
     $new_user->id = $this->id;
     $new_user->firstname = $this->firstname;
     $new_user->lastname = $this->lastname;
     $new_user->birthday = $this->birthday;
     $new_user->gender = $this->gender;
     $new_user->birthplace = $this->birthplace;
     if ($include_age) {
         $new_user->age = self::agePeople($this->birthday);
     }
     if ($include_gender) {
         $new_user->gender = self::genderConversation($this->gender);
     }
     return $new_user;
 }
}
