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

class ProfileEditMenuBlockService extends BaseBreadcrumbMenuBlockService
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
        return 'Breadcrumb: Edit Profile';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMenu(BlockContextInterface $blockContext)
    {
        $menu = parent::getRootMenu($blockContext);
        $menu->addChild($this->translator->trans('breadcrumb.link_dashboard', array(), $this->translationDomain), array('route' => 'fos_user_profile_show'));
        $menu->addChild($this->translator->trans('breadcrumb.link_edit_profile', array(), $this->translationDomain), array('route' => 'fos_user_profile_edit'));
        return $menu;
    }
}
