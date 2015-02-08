<?php

namespace app\modules\users\components;

use Yii;
use yii\authclient\OAuth2;

/**
 * GreenCubes OAuth2
 *
 * @see https://wiki.greencubes.org/API#OAuth_2.0_.E2.80.93_Server-flow
 */
class GreencubesAuth extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://api.greencubes.org/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.greencubes.org/oauth/access_token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.greencubes.org';
    /**
     * @inheritdoc
     */
    public $scope = 'profile,email,regions';
    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'greencubes';
    }
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'GreenCubes';
    }
    /**
     * @inheritdoc
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 650,
            'popupHeight' => 450,
        ];
    }
    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('user', 'GET');
    }
    /**
     * @inheritdoc
     */
    protected function apiInternal($accessToken, $url, $method, array $params, array $headers = null)
    {
        $params['access_token'] = $accessToken->getToken();
        $params['access_token'] = $params['access_token']['token'];

        return $this->sendRequest($method, $url, $params);
    }
}