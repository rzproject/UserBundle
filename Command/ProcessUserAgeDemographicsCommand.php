<?php

namespace Rz\UserBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;

class ProcessUserAgeDemographicsCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('rz:user:process-user-age-demographics')
            ->setDefinition(
                new InputDefinition(array(
                    new InputArgument('context', InputArgument::REQUIRED),
                ))
            );
        $this->addOption('no-confirmation', null, InputOption::VALUE_OPTIONAL, 'Ask confirmation before processing age demographics', false);
        $this->setDescription('Process user age demographics');

        $this->setHelp(<<<EOT
The <info>rz:user:process-user-age-demographics</info> command to process user age demographics.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        $context = $input->getArgument('context');

        if ($input->getOption('no-confirmation') || $dialog->askConfirmation($output, 'Confirm user age demographics processing?', false)) {
            $output->writeln(array(
                    '',
                    '<info>Start processing user age demographics!</info>',
                    ''));
            $users = $this->getUserManager()->findAll();

            if (count($users) > 0) {
                foreach ($users as $user) {
                    if ($user->getDateOfBirth()) {
                        $age = $this->getAge($user->getDateOfBirth());
                        if ($ageBracket = $this->getUserHelper()->getAgeBracket($age, $context)) {
                            if (!$ageDemographics = $this->getUserAgeDemographicsManager()->findOneBy(array('user'=>$user))) {
                                $this->create($user, $ageBracket);
                            } else {
                                $this->update($ageDemographics, $ageBracket);
                            }
                        }
                    }
                }
            } else {
                $output->writeln('<error>No User found!</error>');
            }
        } else {
            $output->writeln('<error>Age demographics processing cancelled !</error>');
        }
    }

    protected function create($user, $collection)
    {
        $ageDemographics = $this->getUserAgeDemographicsManager()->create();
        $ageDemographics->setUser($user);
        $ageDemographics->setCollection($collection);
        $this->getUserAgeDemographicsManager()->save($ageDemographics);
    }

    protected function update($ageDemographics, $collection)
    {
        $ageDemographics->setCollection($collection);
        $this->getUserAgeDemographicsManager()->save($ageDemographics);
    }

    protected function getAge($bDate)
    {
        return date_diff($bDate, date_create('today'))->y;
    }

    public function getUserHelper()
    {
        return $this->getContainer()->get('rz.user.user_helper');
    }

    public function getUserAgeDemographicsManager()
    {
        return $this->getContainer()->get('rz.user.manager.user_age_demographics');
    }
}
