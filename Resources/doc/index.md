UserBundle
==========

Symfony2 Bundle - An extension of [SonataUserBundle](https://github.com/sonata-project/SonataUserBundle "SonataUserBundle")

Uses a forked version of SonataUserBundle

For installation and configuration you can follow [SonataUserBundle](http://sonata-project.org/bundles/user "SonataUserBundle")

The bundle is used to extend SonataUserBundle and requires RzBundles.

Change Logs
-----------

-1.0.1 - 2014-09-29 -- Refactored User bundle to be more compatible with SonataUserBundle and FOSUserBundle, code clean-up.

-1.0.0 - 2014-09-16 -- Initial stable branch

Installation
------------

Follow SonataUserBundle Installation but instrad of using SonataUserBundle routes us RzUserBundle Routes Instead:

Replace:

.. code-block:: yaml

    sonata_user_security:
        resource: "@SonataUserBundle/Resources/config/routing/sonata_security_1.xml"

    sonata_user_resetting:
        resource: "@SonataUserBundle/Resources/config/routing/sonata_resetting_1.xml"
        prefix: /resetting

    sonata_user_profile:
        resource: "@SonataUserBundle/Resources/config/routing/sonata_profile_1.xml"
        prefix: /profile

    sonata_user_register:
        resource: "@SonataUserBundle/Resources/config/routing/sonata_registration_1.xml"
        prefix: /register

    sonata_user_change_password:
        resource: "@SonataUserBundle/Resources/config/routing/sonata_change_password_1.xml"
        prefix: /profile

With:

.. code-block:: yaml

    rz_user_security:
        resource: "@RzUserBundle/Resources/config/routing/security.xml"

    rz_user_resetting:
        resource: "@RzUserBundle/Resources/config/routing/resetting.xml"
        prefix: /resetting

    rz_user_profile:
        resource: "@RzUserBundle/Resources/config/routing/profile.xml"
        prefix: /profile

    rz_user_register:
        resource: "@RzUserBundle/Resources/config/routing/registration.xml"
        prefix: /register

    rz_user_change_password:
        resource: "@RzUserBundle/Resources/config/routing/change_password.xml"
        prefix: /profile
        
Advanced Configuration
======================

Full configuration options:

.. code-block:: yaml

        templates:
          login: RzUserBundle:Security:login.html.twig
          
        admin:
          user:
            templates:
                edit: 'RzUserBundle:Admin:CRUD/edit.html.twig'
                
        registration:
          form:
              type:               rz_user_registration
              name:               rz_user_registration_form
              handler:            rz.user.registration.form.handler.default
              validation_groups:  [Registration, Default]
    
        profile:
            form:
                type:               rz_user_profile
                name:               rz_user_profile_form
                handler:            rz.user.profile.form.handler.default
                validation_groups:  [Profile, Default]
    
            update_password:
                form:
                    type:               rz_user_profile_update_password
                    name:               rz_user_profile_update_password_form
                    handler:            rz.user.profile.update_password.form.handler.default
                    validation_groups:  [UpdatePassword, Default]
    
        change_password:
            form:
                type:               rz_user_change_password
                name:               rz_user_change_password_form
                handler:            rz.user.change_password.form.handler.default
                validation_groups:  [ChangePassword, Default]
    
        resetting:
            form:
                type:               rz_user_resetting
                name:               rz_user_resetting_form
                handler:            rz.user.resetting.form.handler.default
                validation_groups:  [Resetting]
    
        password_security:
          requirement:
            min_length: 8
            require_letters: true
            require_case_diff: false
            require_numbers: false
            require_special_character: false
          strength:
            min_length: 8
            min_strength: 3


Installing Specific Version
---------------------------

To install 1.0.0 use **"rz/user-bundle": 1.0.0** 

To install 1.0.1 or greater use **"rz/user-bundle": ~1.0** 


**STABLE VERSION**
==================
