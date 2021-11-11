<?php

namespace App\Controller;

use App\Model\CatManager;
use App\Model\AdminBreedManager;
use App\Model\AdminColorManager;
use App\Model\AdminFurrManager;
use App\Model\AdminGenderManager;

class AdminCatController extends AbstractController
{
    public function edit(int $id): string
    {
        $error = $cat = [];
        $catManager = new catManager();
        $cat = $catManager->selectOneById($id);

        /*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);
            header('Location: /items/show?id=' . $id);
        }*/
        $adminBreedManager = new AdminBreedManager();
        $breeds = $adminBreedManager->selectAll();

        $adminColorManager = new AdminColorManager();
        $colors = $adminColorManager->selectAll();

        $adminFurrManager = new AdminFurrManager();
        $furrs = $adminFurrManager->selectAll();

        $adminGenderManager = new AdminGenderManager();
        $genders = $adminGenderManager->selectAll();


        return $this->twig->render('Admin/Cat/edit.html.twig', [
            'error' => $error, 'cat' => $cat,
            'breeds' => $breeds, 'colors' => $colors, 'furrs' => $furrs, 'genders' => $genders,
        ]);
    }
}
