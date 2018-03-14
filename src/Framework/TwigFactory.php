<?php

namespace Framework;

use Framework\Twig\Functions\Url;
use Symfony\Component\Routing\Generator\UrlGenerator;

class TwigFactory
{
    private $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string[] $templatesPaths
     * @param string $cachePath
     * @param bool $debug
     * @return \Twig_Environment
     * @throws \ReflectionException
     */
    public function createTwig(array $templatesPaths, string $cachePath = null, bool $debug = true): \Twig_Environment
    {
        //$vendorDirectory = realpath(APP_ROOT.'/vendor');
        $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
        $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());

        $templatesPaths = array_unique(
            array_merge(
                $templatesPaths,
                [$vendorTwigBridgeDirectory.'/Resources/views/Form']
            )
        );

        $twig = new \Twig_Environment(
            new \Twig_Loader_Filesystem($templatesPaths),
            ['cache' => $debug ? false : $cachePath]
        );

        $twig->addFunction(new Url($this->urlGenerator));

        return $twig;
    }
}
