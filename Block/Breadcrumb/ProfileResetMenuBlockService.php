<?php

namespace Rz\UserBundle\Block\Breadcrumb;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\MenuBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Symfony\Component\HttpFoundation\RequestStack;

class ProfileResetMenuBlockService extends BaseBreadcrumbMenuBlockService
{
    protected $menuData;
    protected $translator;
    protected $translationDomain;
    protected $requestStack;

    /**
     * @param string $context
     * @param string $name
     * @param EngineInterface $templating
     * @param MenuProviderInterface $menuProvider
     * @param FactoryInterface $factory
     * @param TranslatorInterface $translator
     * @param string $translationDomain
     */
    public function __construct($context,
                                $name,
                                EngineInterface $templating,
                                MenuProviderInterface $menuProvider,
                                FactoryInterface $factory,
                                RequestStack $requestStack,
                                TranslatorInterface $translator,
                                $translationDomain = 'RzUserBundle')
    {
        parent::__construct($context, $name, $templating, $menuProvider, $factory);
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        $this->requestStack = $requestStack;
        $this->menuData = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Breadcrumb: Reset Password';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMenu(BlockContextInterface $blockContext)
    {

        $request = $this->requestStack->getCurrentRequest();
        $menu = parent::getRootMenu($blockContext);
        if($token = $request->get('token')) {
            $menu->addChild($this->translator->trans('breadcrumb.link_reset', array(), $this->translationDomain),
                            array('route' => 'fos_user_resetting_reset',
                                  'routeParameters' => array(
                                    'token' => $token)));
        }
        return $menu;
    }
}
