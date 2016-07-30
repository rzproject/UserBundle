<?php


namespace Rz\UserBundle\Block;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileGenderBlockService extends BaseBlockService
{
    protected $manager;
    protected $translator;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param ContainerInterface $container
     */
    public function __construct($name, EngineInterface $templating, UserManagerInterface $manager, $translator)
    {
        $this->manager      = $manager;
        $this->translator   = $translator;
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
            'title'    => 'block_title_profile_gender',
            'template' => 'RzUserBundle:Block:block_profile_gender.html.twig',
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
        $gender = $this->manager->fetchGenderCount();
        $genderChart = [];
        $genderList = [];
        foreach ($gender as $data) {
            switch ($data['gender']) {
                case 'u':
                    $genderList[] = ['gender'=>$this->translator->trans('gender_unknown', [], 'SonataUserBundle'), 'total'=>$data['total']];
                    $genderChart[$this->translator->trans('gender_unknown', [], 'SonataUserBundle')] = array($data['total']);
                    break;
                case 'm':
                    $genderList[] = ['gender'=>$this->translator->trans('gender_male', [], 'SonataUserBundle'), 'total'=>$data['total']];
                    $genderChart[$this->translator->trans('gender_male', [], 'SonataUserBundle')] = array($data['total']);
                    break;
                case 'f':
                    $genderList[] = ['gender'=>$this->translator->trans('gender_female', [], 'SonataUserBundle'), 'total'=>$data['total']];
                    $genderChart[$this->translator->trans('gender_female', [], 'SonataUserBundle')] = array($data['total']);
                    break;
            }
        }

        $totalGender = $this->manager->fetchGenderCountTotal();
        $block->setSetting('genderList', $genderList);
        $block->setSetting('genderChart', json_encode($genderChart));
        $block->setSetting('genderTotal', $totalGender);
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
