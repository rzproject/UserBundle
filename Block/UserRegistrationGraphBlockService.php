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


class UserRegistrationGraphBlockService extends BaseBlockService
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
        return 'User Registration';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'User Registration',
            'template' => 'RzUserBundle:Block:block_profile_user_registration_graph.html.twig',
            'registrationCount' => null,
            'fetchCountRecord' => 5,
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
            ),
            'attr'=>array('class'=>'rz-immutable-container')
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
        $settings = $block->getSettings();
        $userRegistrations = $userManager->fetchRegistrationCount(isset($settings['fetchCountRecord']) ? $settings['fetchCountRecord'] : 5);
        $block->setSetting('userRegistrations', $userRegistrations);
    }
}
