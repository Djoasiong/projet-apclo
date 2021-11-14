<?php

namespace App\Model;

class CatManager extends AbstractManager
{
    public const TABLE = 'cat';

    public function selectOneById(int $id)
    {
        $statement = $this->pdo->prepare("SELECT cat.name, image, birth_date,
        TIMESTAMPDIFF(YEAR, birth_date, NOW()) as age,
        digital_chip, description, adoption_date, gender.name gender, furr.length,
        color.name color, breed.name breed
        FROM " . self::TABLE .
        "   LEFT JOIN gender ON gender.id = cat.gender_id 
            LEFT JOIN furr ON furr.id = cat.furr_id
            LEFT JOIN breed ON breed.id = cat.breed_id 
            LEFT JOIN color ON color.id = cat.color_id 
        WHERE cat.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function toAdopt()
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE adoption_date IS NULL ORDER BY id DESC LIMIT 3";

        return $this->pdo->query($query)->fetchAll();
    }

    public function selectAllCats(): array
    {
        $query = "SELECT cat.name as name, image, birth_date, gender.name as gender, cat.id as id FROM " .
        self::TABLE . " JOIN gender ON gender.id = cat.gender_id";

        return $this->pdo->query($query)->fetchAll();
    }

    public function latestAdopted()
    {
        $query = "SELECT * FROM " . self::TABLE .
            " WHERE adoption_date IS NOT NULL 
            ORDER BY adoption_date DESC LIMIT 3";

        return $this->pdo->query($query)->fetchAll();
    }

    public function update(array $cat): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET `name` = :name, `birth_date` = :birth_date,`adoption_date` = :adoption_date,
        `description` = :description, `gender_id` = :gender_id, `color_id` = :color_id,
        `furr_id` = :furr_id, `breed_id` = :breed_id, `image` =:image WHERE id=:id");

        $statement->bindValue('id', $cat['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $cat['name'], \PDO::PARAM_STR);
        $statement->bindValue('birth_date', $cat['birth_date'], \PDO::PARAM_STR);
        $statement->bindValue('adoption_date', $cat['adoption_date']);
        $statement->bindValue('description', $cat['description'], \PDO::PARAM_STR);
        $statement->bindValue('gender_id', $cat['gender_id'], \PDO::PARAM_INT);
        $statement->bindValue('color_id', $cat['color_id'], \PDO::PARAM_INT);
        $statement->bindValue('furr_id', $cat['furr_id'], \PDO::PARAM_INT);
        $statement->bindValue('breed_id', $cat['breed_id'], \PDO::PARAM_INT);
        $statement->bindValue('image', $cat['image'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
