<?php

namespace Rz\UserBundle;

/**
 * Contains all events thrown in the RzUserBundle
 */
final class RzUserEvents
{
    /**
     * LOGIN
     *
     */
    const RZ_LOGIN_INITIALIZE = 'rz_user.login.initialize';
    const RZ_LOGIN_PROCESS = 'rz_user.login.process';
    const RZ_LOGIN_SUCCESS = 'rz_user.login.success';
    const RZ_LOGIN_COMPLETED = 'rz_user.login.completed';
}
