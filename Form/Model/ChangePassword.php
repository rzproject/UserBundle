<?php

namespace Rz\UserBundle\Form\Model;

class ChangePassword
{
    /**
     * @var string
     */
    protected $new;

    /**
     * @return string
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param string $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }
}
