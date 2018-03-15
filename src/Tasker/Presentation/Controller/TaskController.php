<?php

declare(strict_types=1);

namespace Tasker\Presentation\Controller;

use Framework\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class TaskController
{
    public static function addAction(Request $request, Application $app)
    {
        $form = $app->getFormFactory()->createBuilder(FormType\FormType::class)
            ->add('userName', FormType\TextType::class, ['required' => true])
            ->add('email', FormType\EmailType::class, ['required' => true])
            ->add('text', FormType\TextareaType::class, ['required' => true])
            ->add('image', FormType\FileType::class, ['required' => false, 'attr' => ['accept' => "image/*" ]])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $app['app.use_case.create_task']->run($data['userName'], $data['email'], $data['text'], $data['image']);

            return $app->redirect($app->getUrlGenerator()->generate('task.list'));
        }

        return $app->render('Task/add.twig', [
            'form' => $form->createView(),
        ]);
    }
}
