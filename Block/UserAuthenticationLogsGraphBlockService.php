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


class UserAuthenticationLogsGraphBlockService extends BaseBlockService
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
        return 'User Authentication Logs';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'User Authentication Logs',
            'template' => 'RzUserBundle:Block:block_profile_user_authentication_logs_graph.html.twig',
            'logsPerDay' => null,
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
        $userAuthenticationLogsManager = $this->container->get('rz.user.manager.user_authentication_logs');
        $weekBefore = new \DateInterval('P6D');
        $beginDate = new \DateTime();
        $beginDate->sub($weekBefore);
        $endDate   = new \DateTime();
        $endDate = $endDate->modify( '+1 day' );
        $userLogsByDay = $userAuthenticationLogsManager->fetchUserLogsByDayCount($beginDate->format('Y-m-d'), 'login');

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($beginDate, $interval ,$endDate);
        $dateIndex = array();
        foreach($daterange as $date) {
            $dateIndex[] = $date->format("Y-m-d");
        }
        $userLogsWeek = array();

        $dateIndex =  array_reverse ($dateIndex );
        foreach($dateIndex as $date) {
            foreach($userLogsByDay as $key=>$log) {
                if($log['logDate'] === $date) {
                    $userLogsWeek[$key] = $log;
                }
            }
        }

        $block->setSetting('logsPerDay', $userLogsWeek);
    }
}
