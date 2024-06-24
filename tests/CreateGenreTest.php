<?php

namespace App\Tests\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Component\Form\Test\TypeTestCase;

class GenreTypeTest extends TypeTestCase
{
     public function testSubmitValidData()
     {
          $formData = [
               'name' => 'Genre Test'
          ];


          $genre = new Genre();

          $form = $this->factory->create(GenreType::class, $genre);
          $form->submit($formData);

          $this->assertTrue($form->isSynchronized());

          $this->assertEquals('Genre Test', $genre->getName());

          $view = $form->createView();
          $children = $view->children;

          foreach (array_keys($formData) as $key) {
               $this->assertArrayHasKey($key, $children);
          }
     }

}
