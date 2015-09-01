<?php

namespace Rz\UserBundle\Model;

interface UserAgeDemographicsInterface {

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt);
}