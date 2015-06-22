<?php


namespace Rz\UserBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ProfileGenderGraphBlockService extends BaseBlockService
{
    protected $manager;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, ContainerInterface $container)
    {
        $this->container      = $container;
        parent::__construct($name, $templating);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'User Gender Demographics';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'User Gender Demographics',
            'template' => 'RzUserBundle:Block:block_profile_gender_graph.html.twig',
            'gender' => null,
            'genderTotal' => null,
            'mode'       => 'admin',
            'disabled' => false
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
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getBlock()->getSettings();

        return $this->renderResponse($blockContext->getTemplate(),
            array(
                'context'   => $blockContext,
                'block'     => $blockContext->getBlock(),
                'settings'  => $settings
            ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function load(BlockInterface $block)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $gender = $userManager->fetchGenderCount();
        $totalGender = $userManager->fetchGenderCountTotal();
        $block->setSetting('gender', $gender);
        $block->setSetting('genderTotal', $totalGender);
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array('/bundles/rzuser/css/block-flot.css');
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media)
    {
        return array('/bundles/rmzamorajquery/jquery-plugins/flot/jquery.flot.js',
                     '/bundles/rmzamorajquery/jquery-plugins/flot/jquery.flot.pie.min.js',
                     '/bundles/rmzamorajquery/jquery-plugins/flot/jquery.flot.resize.min.js',
                     '/bundles/rmzamorajquery/jquery-plugins/flot/jquery.flot.stack.js',
                    );
    }
}
