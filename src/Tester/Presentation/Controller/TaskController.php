<?php

declare(strict_types=1);

namespace Tester\Presentation\Controller;

use Framework\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class TaskController
{
    public static function addAction(Request $request, Application $app)
    {
        $form = $app->getFormFactory()->createBuilder(FormType\FormType::class)
            ->add('userName', FormType\TextType::class)
            ->add('email', FormType\EmailType::class)
            ->add('text', FormType\TextareaType::class)
            ->add('image', FormType\FileType::class)
            ->getForm();

        $form->handleRequest($request);

        return $app->render('Task/add.twig', [
            'form' => $form->createView(),
        ]);
    }
}
