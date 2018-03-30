<?php

declare(strict_types=1);

namespace Tasker\Presentation\Controller\Admin;

use Framework\Application;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tasker\Domain\TaskNotFoundException;

class AdminController
{
    public static function indexAction(Request $request, Application $app)
    {
        if (!$app['user']->isGrant('ADMIN')) {
            return new RedirectResponse($app['url_generator']->generate('admin.login'));
        }

        return new RedirectResponse($app['url_generator']->generate('/'));
    }

    public static function loginAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder(FormType\FormType::class)
            ->setAction($app['url_generator']->generate('admin.login'))
            ->setMethod('POST')
            ->add('login', FormType\TextType::class, ['required' => true, 'attr' => ['placeholder' => 'admin']])
            ->add('password', FormType\PasswordType::class, ['required' => true, 'attr' => ['placeholder' => '123']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($app['auth']->tryLogin($data['login'], $data['password'])) {
                $app['session']->getFlashBag()->add('success', 'You logged as '.$data['login']);
                return new RedirectResponse($app['url_generator']->generate('/'));
            }

            $app['session']->getFlashBag()->add('warning', 'Authorization not completed');
            return new RedirectResponse($app['url_generator']->generate('admin.login'));
        }

        return $app['twig']->render('Admin/login.twig', [
            'form' => $form->createView(),
        ]);
    }

    public static function logoutAction(Request $request, Application $app)
    {
        $app['session']->remove('user');
        $app['session']->getFlashBag()->add('success', 'You logged out');
        return new RedirectResponse($app['url_generator']->generate('/'));
    }

    public static function editTaskAction(Request $request, Application $app)
    {
        if (!$app['user']->isGrant('ADMIN')) {
            return new RedirectResponse($app['url_generator']->generate('admin.login'));
        }

        $id = intval($request->get('id'));

        try {
            $task = $app['app.use_case.fetch_task']->run($id);
        } catch (TaskNotFoundException $e) {
            return new Response('Task not found', response::HTTP_NOT_FOUND);
        }

        $form = $app['form.factory']->createBuilder(FormType\FormType::class, [
            'text' => $task->getText(),
            'done' => $task->isDone(),
        ])
            ->add('text', FormType\TextareaType::class)
            ->add('done', FormType\CheckboxType::class, ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $task->setText($data['text']);
            $task->setDone($data['done']);

            $app['app.use_case.update_task']->run($task);

            $app['session']->getFlashBag()->add('success', 'Task '.$task->getId().' was successfully updated');

            return new RedirectResponse($app['url_generator']->generate('/'));
        }

        return $app['twig']->render('Admin/edit_task.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
}
