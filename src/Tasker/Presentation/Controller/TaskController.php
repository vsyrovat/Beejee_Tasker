<?php

declare(strict_types=1);

namespace Tasker\Presentation\Controller;

use Framework\Application;
use Framework\Image\SimpleImage;
use Framework\PHP\UploadMaxDetector;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\Response;
use Tasker\Domain\ImageProcessException;

class TaskController
{
    private static function createForm(Application $app)
    {
        return $app['form.factory']->createBuilder(FormType\FormType::class)
            ->setAction($app['url_generator']->generate('task.add'))
            ->setMethod('POST')
            ->add('userName', FormType\TextType::class, ['required' => true])
            ->add('email', FormType\EmailType::class, ['required' => true])
            ->add('text', FormType\TextareaType::class, ['required' => true])
            ->add('image', FormType\FileType::class, ['required' => false, 'attr' => ['accept' => "image/*" ]])
            ->getForm();
    }

    public static function addAction(Request $request, Application $app)
    {
        $form = static::createForm($app);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $image = $data['image'];

            if ($image) {
                try {
                    if (!$image instanceof UploadedFile) {
                        throw new ImageProcessException('Upload file problem');
                    }
                    if (!$image->isValid()) {
                        throw new ImageProcessException(
                            "Unknown image type or exceed upload file size limit (".
                            UploadMaxDetector::bytesFormat($app['upload_max_size']).')'
                        );
                    }
                } catch (ImageProcessException $e) {
                    return new JsonResponse([
                        'success' => false,
                        'errmessage' => $e->getMessage(),
                    ]);
                }
            }
            $app['app.use_case.create_task']->run($data['userName'], $data['email'], $data['text'], $data['image']);

            return new JsonResponse([
                'success' => true,
            ]);
        }

        return $app['twig']->render('Task/add.twig', [
            'form' => $form->createView(),
            'upload_max_filesize' => UploadMaxDetector::bytesFormat($app['upload_max_size']),
        ]);
    }

    public static function previewAction(Request $request, Application $app)
    {
        $form = static::createForm($app);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $image = $data['image'];
            $dataUriImage = null;

            if ($image) {
                try {
                    if (!$image instanceof UploadedFile) {
                        throw new ImageProcessException('Upload file problem');
                    }
                    if (!$image->isValid()) {
                        throw new ImageProcessException(
                            "Unknown image type or exceed upload file size limit (".
                            UploadMaxDetector::bytesFormat($app['upload_max_size']).')'
                        );
                    }
                    try {
                        $i = new SimpleImage($image);
                        $i->bestFit(320, 240);
                        $dataUriImage = $i->toDataUri();
                    } catch (\Exception $e) {
                        throw new ImageProcessException($e->getMessage(), $e->getCode());
                    }

                } catch (ImageProcessException $e) {
                    return new JsonResponse([
                        'success' => false,
                        'errmessage' => $e->getMessage(),
                    ]);
                }
            }

            return new JsonResponse([
                'success' => true,
                'result' => $app['twig']->render('Task/preview.twig', [
                    'formData' => $data,
                    'dataUriImage' => $dataUriImage,
                ]),
            ]);
        }

        return new Response('This url should not be called directly', Response::HTTP_BAD_REQUEST);
    }
}
