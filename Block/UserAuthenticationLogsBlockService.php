<?php

namespace Rz\UserBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAuthenticationLogsBlockService extends BaseBlockService
{
    protected $manager;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, ManagerInterface $manager)
    {
        $this->manager      = $manager;
        parent::__construct($name, $templating);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Recent User Logs';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'      => 'block_title_user_authentication_logs',
            'template'   => 'RzUserBundle:Block:block_profile_user_authentication_logs.html.twig',
            'logsPerDay' => null,
            'mode'       => 'admin',
            'icon'       => 'fa-users',
            'disabled'   => false
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
        $weekBefore = new \DateInterval('P6D');
        $beginDate = new \DateTime();
        $beginDate->sub($weekBefore);
        $beginDate->setTime(0, 0);
        $endDate   = new \DateTime();
        $userLogsByDayGender = $this->manager->fetchUserLogsByDayCountGender($beginDate->format('Y-m-d'), 'login');
        $userLogsByDay = $this->manager->fetchUserLogsByDayCount($beginDate->format('Y-m-d'), 'login');

        $block->setSetting('startDate', $beginDate->format("Y-m-d"));
        $block->setSetting('endDate', $endDate->format("Y-m-d"));

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($beginDate, $interval, $endDate);
        $dateIndex = [];

        foreach ($daterange as $date) {
            $dateIndex[] = $date->format("Y-m-d");
        }

        $userLogsWeekx = [];
        $userLogsWeekDataU = [];
        $userLogsWeekDataM = [];
        $userLogsWeekDataF = [];

        $userLogsWeekDataTotal = [];

        $userLogsWeekx[] = 'x';
        $userLogsWeekData[] = 'data';
        foreach ($dateIndex as $index=>$date) {
            $idx= new \DateTime($date);
            $longIdx= new \DateTime($date);
            $userLogsWeekx[$index] = $idx->format("m-d");
            $userLogsWeekDataU[$index] = "0";
            $userLogsWeekDataM[$index] = "0";
            $userLogsWeekDataF[$index] = "0";
            $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['total'] = "0";
            $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['f'] = "0";
            $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['m'] = "0";
            $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['u'] = "0";

            foreach ($userLogsByDayGender as $key=>$log) {
                if ($log['logDate'] === $date) {
                    if ($log['gender'] === 'f') {
                        $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['f'] = $log['logDateCount'];
                        $userLogsWeekDataF[$index] = $log['logDateCount'];
                    } elseif ($log['gender'] === 'm') {
                        $userLogsWeekDataM[$index] = $log['logDateCount'];
                        $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['m'] = $log['logDateCount'];
                    } else {
                        $userLogsWeekDataU[$index] = $log['logDateCount'];
                        $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['u'] = $log['logDateCount'];
                    }
                }
            }

            foreach ($userLogsByDay as $key=>$log) {
                if ($log['logDate'] === $date) {
                    $userLogsWeekDataTotal[$longIdx->format("F d, Y")]['total'] = $log['logDateCount'];
                }
            }
        }

        $userLogsWeek['x'] = $userLogsWeekx;
        $userLogsWeek['dataU'] = $userLogsWeekDataU;
        $userLogsWeek['dataM'] = $userLogsWeekDataM;
        $userLogsWeek['dataF'] = $userLogsWeekDataF;

        $block->setSetting('logsPerDay', json_encode($userLogsWeek));
        $block->setSetting('logsPerDayTotal', $userLogsWeekDataTotal);
    }

    /**
     * {@inheritdoc}
     */
    public function getJavascripts($media)
    {
        return array('/assetic/admin_graph_js.js', '/assetic/admin_dashboard_block_js.js');
    }

    /**
     * {@inheritdoc}
     */
    public function getStylesheets($media)
    {
        return array('/assetic/admin_graph_css.css');
    }
}
