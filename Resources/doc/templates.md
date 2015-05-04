Templates
=========

* ``layout`` : RzUserBundle::layout.html.twig
* ``login`` : RzUserBundle:Security:login.html.twig
* ``admin_login`` : RzUserBundle:Admin:Security/login.html.twig
* ``resetting`` : RzUserBundle:Resetting:reset.html.twig
* ``resetting_content`` : RzUserBundle:Resetting:reset_content.html.twig
* ``resetting_request`` : RzUserBundle:Resetting:request.html.twig
* ``resetting_request_content`` : RzUserBundle:Resetting:request_content.html.twig
* ``resetting_password_already_requested`` : RzUserBundle:Resetting:passwordAlreadyRequested.html.twig
* ``resetting_check_email`` : RzUserBundle:Resetting:checkEmail.html.twig
* ``resetting_email`` : RzUserBundle:Resetting:email.html.twig
* ``profile`` : RzUserBundle:Profile:show.html.twig
* ``profile_action`` : RzUserBundle:Profile:action.html.twig
* ``profile_edit`` : RzUserBundle:Profile:edit_profile.html.twig
* ``profile_edit_authentication`` : RzUserBundle:Profile:edit_authentication.html.twig
* ``registration`` : RzUserBundle:Registration:register.html.twig
* ``registration_content`` : RzUserBundle:Registration:register_content.html.twig
* ``registration_check_email`` : RzUserBundle:Registration:checkEmail.html.twig
* ``registration_confirmed`` : RzUserBundle:Registration:confirmed.html.twig
* ``registration_email`` : RzUserBundle:Registration:email.html.twig
* ``change_password`` : RzUserBundle:ChangePassword:changePassword.html.twig
* ``change_password_content`` : RzUserBundle:ChangePassword:changePassword_content.html.twig


Configuring templates
---------------------

Like said before, the main goal of this template structure is to make it easy for you
to customize the ones you need. You can simply extend the ones you want in your own bundle,
and tell ``RzUserBundle`` to use your templates instead of the default ones. You can do so
in several ways.

You can specify your templates in the config.yml file, like so:

.. configuration-block::

    .. code-block:: yaml

        rz_user:
            templates:
                layout                                  : RzUserBundle::layout.html.twig
                login                                   : RzUserBundle:Security:login.html.twig
                admin_login                             : RzUserBundle:Admin:Security/login.html.twig
                resetting                               : RzUserBundle:Resetting:reset.html.twig
                resetting_content                       : RzUserBundle:Resetting:reset_content.html.twig
                resetting_request                       : RzUserBundle:Resetting:request.html.twig
                resetting_request_content               : RzUserBundle:Resetting:request_content.html.twig
                resetting_password_already_requested    : RzUserBundle:Resetting:passwordAlreadyRequested.html.twig
                resetting_check_email                   : RzUserBundle:Resetting:checkEmail.html.twig
                resetting_email                         : RzUserBundle:Resetting:email.html.twig
                profile                                 : RzUserBundle:Profile:show.html.twig
                profile_action                          : RzUserBundle:Profile:action.html.twig
                profile_edit                            : RzUserBundle:Profile:edit_profile.html.twig
                profile_edit_authentication             : RzUserBundle:Profile:edit_authentication.html.twig
                registration                            : RzUserBundle:Registration:register.html.twig
                registration_content                    : RzUserBundle:Registration:register_content.html.twig
                registration_check_email                : RzUserBundle:Registration:checkEmail.html.twig
                registration_confirmed                  : RzUserBundle:Registration:confirmed.html.twig
                registration_email                      : RzUserBundle:Registration:email.html.twig
                change_password                         : RzUserBundle:ChangePassword:changePassword.html.twig
                change_password_content                 : RzUserBundle:ChangePassword:changePassword_content.html.twig