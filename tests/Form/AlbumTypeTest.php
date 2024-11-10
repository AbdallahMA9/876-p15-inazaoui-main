<?php

namespace App\Tests\Form;

use App\Entity\Album;
use App\Form\AlbumType;
use Symfony\Component\Form\Test\TypeTestCase;

class AlbumTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        // Données simulées pour remplir le formulaire
        $formData = [
            'name' => 'Album Test',
        ];

        // Création d'un objet Album vierge pour remplir avec les données
        $album = new Album();

        // Création du formulaire AlbumType
        $form = $this->factory->create(AlbumType::class, $album);

        // Soumission des données au formulaire
        $form->submit($formData);

        // Vérification que le formulaire a été validé correctement
        $this->assertTrue($form->isSynchronized());

        // Création d'un objet Album avec les données attendues
        $expected = new Album();
        $expected->setName('Album Test');

        // Vérification que les données du formulaire correspondent aux données attendues
        $this->assertEquals($expected, $album);

        // Vérification de la structure des champs du formulaire
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }


}
