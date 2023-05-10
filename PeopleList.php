<?php

require_once(__DIR__ . '/People.php');

if (!class_exists('People')) {
    echo "Error! Class does not exist";
    return;
}

class PeopleList
{
    private array $arrayPeople = [];

    public function __construct($params = array())
    {
        $mydb = new mysqli('server', 'username', 'password', 'db_name');
        $query = "SELECT * FROM " . People::TABLE_NAME . " WHERE 1=1";
        if (isset($params['firstname'])) {
            $query .= " AND firstname LIKE '%{$params['firstname']}%'";
        }

        if (isset($params['lastname'])) {
            $query .= " AND lastname LIKE '%{$params['lastname']}%'";
        }

        if (isset($params['birthday'])) {
            $birthday = $mydb->real_escape_string($params['birthday']);
            $query .= " AND birthday='{$birthday}'";
        }

        if (isset($params['gender'])) {
            $gender = $params['gender'] == 1 ? 'Man' : 'Woman';
            $query .= " AND gender='{$gender}'";
        }

        if (isset($params['birthplace'])) {
            $birtplace = $mydb->real_escape_string($params['birthplace']);
            $query .= " AND birthplace='{$birtplace}'";
        }

        $result = $mydb->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->arrayPeople[] = $row['id'];
        }
    }

    public function getPeople()
    {
        $people = [];
        foreach ($this->arrayPeople as $id) {
            $people[] = new People($id);
        }
        return $people;
    }

    public function deletePeople()
    {
        $mydb = new mysqli('server', 'username', 'password', 'db_name');
        $people = $this->getPeople();
        $query = "DELETE * FROM " . People::TABLE_NAME . " WHERE id IN (" . implode(',', $this->arrayPeople) . ")";
        $mydb->query($query);
    }
}


