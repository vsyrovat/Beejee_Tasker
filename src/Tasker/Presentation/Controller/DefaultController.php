<?php

declare(strict_types=1);

namespace Tasker\Presentation\Controller;

use Framework\Application;
use Framework\Pagination\Pager;
use Framework\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    public static function indexAction(Request $request, Application $app)
    {
        $sort = $request->get('sort');

        $pager = new Pager($request, 'page', APP_PAGE_SIZE);

        $tasks = $app['app.use_case.fetch_tasks']->run($sort, $pager->getLimit(), $pager->getOffset());

        $totalTasks = $app['app.use_case.count_tasks']->run();

        $paginator = new Paginator($pager, count($tasks), $totalTasks);

        return $app['twig']->render('Default/index.twig', [
            'tasks' => $tasks,
            'paginator' => $paginator,
            'sort' => $sort,
        ]);
    }
}
