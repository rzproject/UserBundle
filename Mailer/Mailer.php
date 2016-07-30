<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use FOS\UserBundle\Mailer\TwigSwiftMailer as FOSTwigSwiftMailer;

class Mailer extends FOSTwigSwiftMailer
{
    protected $requestStack;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, RequestStack $requestStack, array $parameters)
    {
        parent::__construct($mailer, $router, $twig, $parameters);
        $this->requestStack = $requestStack;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('sonata_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $email = key($this->parameters['from_email']['confirmation']);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
            'ttl'             => $this->parameters['ttl'],
            'email'           => $email,
            'administrator'   => $this->parameters['from_email']['confirmation'][$email],
            'request'         => $this->requestStack->getCurrentRequest()
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('sonata_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);

        $email = key($this->parameters['from_email']['resetting']);
        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
            'ttl'             => $this->parameters['ttl'],
            'email'           => $email,
            'administrator'   => $this->parameters['from_email']['resetting'][$email],
            'request' => $this->requestStack->getCurrentRequest()
        );
        $this->sendMessage($template, $context, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
