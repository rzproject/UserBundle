<?php

namespace Rz\UserBundle\Block;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAuthenticationLogsStatsBlockService extends BaseBlockService
{
    protected $manager;
    protected $pool;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, ManagerInterface $manager, Pool $pool)
    {
        parent::__construct($name, $templating);
        $this->manager = $manager;
        $this->pool    = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'User Authentication Logs Stats';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'block_user_authentication_logs_stats_title',
            'template' => 'RzUserBundle:Block:block_profile_user_authentication_logs_stats.html.twig',
            'currentLogs' => null,
            'mode'       => 'admin',
            'disabled' => false,
            'icon'     => 'fa-line-chart',
            'text'     => 'Statistics',
            'color'    => 'bg-aqua',
            'code'     => 'sonata.user.admin.user',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('title', 'text', array('required' => false)),
                array('mode', 'choice', array(
                    'choices' => array(
                        'public' => 'public',
                        'admin'  => 'admin'
                    )
                )),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getBlock()->getSettings();
        $admin = $this->pool->getAdminByAdminCode($blockContext->getSetting('code'));

        return $this->renderResponse($blockContext->getTemplate(),
            array(
                'context'   => $blockContext,
                'block'     => $blockContext->getBlock(),
                'admin'     => $admin,
                'settings'  => $settings
            ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $currentLoggedUser = $this->manager->fetchCurrentLoggedUser('login');

        $block->setSetting('currentLoggedUser', $currentLoggedUser);
    }
}
