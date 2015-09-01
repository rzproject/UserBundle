<?php

namespace Rz\UserBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSampleUserLogsCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('rz:user:create-sample-user-logs');
        $this->addOption('no-confirmation', null, InputOption::VALUE_OPTIONAL, 'Ask confirmation before generating sample users', false);
        $this->setDescription('Create sample user logs');

        $this->setHelp(<<<EOT
The <info>rz:user:create-sample-user-logs</info> command create sample user logs.

EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        $dialog = $this->getHelperSet()->get('dialog');

            if ($input->getOption('no-confirmation') || $dialog->askConfirmation($output, 'Confirm user logs creation ?', false)) {
                $output->writeln(array(
                    '',
                    '<info>Starting user log creation!</info>',
                    ''));

                $users = $this->getUserManager()->findAll();

                if(count($users) > 0 ) {
                    foreach ($users as $user) {
                        //login
                        $max= rand(5,20);
                        $logsManager = $this->getUserAuthemticationLogsManager();
                        foreach (range(1, $max) as $num) {
                            $logDateStr= null;
                            $logoutDateStr= null;
                            $logDate = $this->getFaker()->dateTimeBetween($startDate = '-1 months', $endDate = 'now');
                            $logDateStr = $logDate->format("Y-m-d H:i:s");
                            $minutesToAdd = rand(3,45);
                            $dv = new \DateInterval('PT'.$minutesToAdd.'M');
                            $logs = $logsManager->create();
                            $logs->setLogDate($logDate);
                            $logs->setUser($user);
                            $logs->setType('login');
                            $logsManager->save($logs);
                            $logs = null;
                            usleep(2000);
                            $logs = $logsManager->create();
                            $logDate->add($dv);
                            $logoutDateStr = $logDate->format("Y-m-d H:i:s");
                            $logs->setLogDate($logDate);
                            $logs->setUser($user);
                            $logs->setType('logout');
                            $logsManager->save($logs);
                            usleep(2000);
                            $output->writeln(<<<INFO

Creating user with the following information :
  <info>user</info> : {$user->getUsername()}
  <info>login</info> : { $logDateStr }
  <info>logout</info> : { $logoutDateStr }
INFO
                            );

                            $logs = null;
                            $logDate = null;
                            $logoutDate = null;
                        }
                    }
                } else {
                    $output->writeln('<error>No User found !</error>');
                }

            } else {
                $output->writeln('<error>User creation cancelled !</error>');
            }

    }

    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        return $this->getContainer()->get('faker.generator');
    }

    public function getUserAuthemticationLogsManager() {
        return $this->getContainer()->get('rz.user.manager.user_authentication_logs');
    }
}
