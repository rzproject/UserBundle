<?php

namespace Rz\UserBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Model\ManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileAgeDemogrphicsBlockService extends BaseBlockService
{
    protected $context;
    protected $manager;
    protected $collectionManager;
    protected $translator;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, ManagerInterface $manager, ManagerInterface $collectionManager, $translator, $context)
    {
        parent::__construct($name, $templating);
        $this->collectionManager = $collectionManager;
        $this->translator        = $translator;
        $this->manager           = $manager;
        $this->context           = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'User Age Demographics';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'block_title_user_age_demographics',
            'template' => 'RzUserBundle:Block:block_profile_age_demographics.html.twig',
            'ageBracket' => null,
            'ageBracketTotal' => null,
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
        $ageBrackets = $this->collectionManager->findBy(array('context'=>$this->context, 'enabled'=>true));
        $ageBracket = $this->manager->fetchAgeBracketCount();
        $dataAgeBracketList = [];
        $dataAgeBracketChart  = [];
        $dataAgeBracketBar  = [];

        //PieChart Data & List Data
        foreach ($ageBrackets as $idx=>$bracket) {
            $dataAgeBracketChart[$bracket->getName()] = array(0);
            $dataAgeBracketList[$idx] = ['age'=>$bracket->getName(), 'total'=>0];
            foreach ($ageBracket as $age) {
                if ($bracket->getName() == $age['name']) {
                    $dataAgeBracketChart[$bracket->getName()] = array($age['ageBracketCount']);
                    $dataAgeBracketList[$idx] = ['age'=>$bracket->getName(), 'total'=>$age['ageBracketCount']];
                }
            }
        }

        $ageByGender = $this->manager->fetchAgeBracketCountByGender();
        //LineGraph Data
        $ageX = [];
        $ageU = [];
        $ageF = [];
        $ageM = [];
        foreach ($ageBrackets as $idx=>$bracket) {
            $ageX[$idx] = $bracket->getName();
            $ageU[$idx] = "0";
            $ageM[$idx] = "0";
            $ageF[$idx] = "0";
            foreach ($ageByGender as $age) {
                if ($bracket->getName() == $age['name']) {
                    switch ($age['gender']) {
                        case 'u':
                            $ageU[$idx] = $age['ageBracketCount'];
                            break;
                        case 'm':
                            $ageM[$idx] = $age['ageBracketCount'];
                            break;
                        case 'f':
                            $ageF[$idx] = $age['ageBracketCount'];
                            break;
                    }
                }
            }
        }
        $dataAgeBracketBar['x'] = $ageX;
        $dataAgeBracketBar['dataU'] = $ageU;
        $dataAgeBracketBar['dataM'] = $ageM;
        $dataAgeBracketBar['dataF'] = $ageF;

        $ageBracketTotal = $this->manager->fetchAgeBracketCountTotal();

        $block->setSetting('ageBracketList', $dataAgeBracketList);
        $block->setSetting('ageBracketBar', json_encode($dataAgeBracketBar));
        $block->setSetting('ageBracketChart', json_encode($dataAgeBracketChart));
        $block->setSetting('ageBracketTotal', $ageBracketTotal);
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
