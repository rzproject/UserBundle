<?php

namespace Rz\UserBundle\Block\Breadcrumb;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\MenuBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sonata\SeoBundle\Block\Breadcrumb\BaseBreadcrumbMenuBlockService;
use Symfony\Component\Translation\TranslatorInterface;

class RegisterMenuBlockService extends BaseBreadcrumbMenuBlockService
{
    protected $menuData;
    protected $translator;
    protected $translationDomain;

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
                                TranslatorInterface $translator,
                                $translationDomain = 'RzUserBundle')
    {
        parent::__construct($context, $name, $templating, $menuProvider, $factory);
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        $this->menuData = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Breadcrumb: Register';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = parent::getRootMenu($blockContext);
        $menu->addChild($this->translator->trans('breadcrumb.link_register', array(), $this->translationDomain), array('route' => 'fos_user_registration_register'));
        return $menu;
    }
}
