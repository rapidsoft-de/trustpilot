<?php
/*
 * This file is part of the TrustPilot library.
 *
 * (c) Graphem Solutions <info@graphem.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TrustPilot\Api;

/**
 * @author Graphem Solutions <info@graphem.ca>
 */

use Carbon\Carbon;

class Authorize extends AbstractApi{

    
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $type_settings;

    /**
     * Set the token
     *
     * @param string $token
     */
    public function setToken($token = '')
    {
        $this->token = $token;
    }

    /**
     * Get the token
     *
     * @return String
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get Authorization Token with password method
     *
     * @param string $username
     * @param string $password
     * @return mixed|string
     */
    public function createPasswordToken($username = '', $password = '')
    {        
       $data = array('username' => $username, 'password' => $password);
       $this->token = $this->createToken('password', $data);
       return $this->token;
    }

    /*
     * Redirect to get Authorization Code
     *
     * @param  
     * @return 
     *
    public function redirectToAuth($apiKey, $redirect_uri = '')
    {        
       $data = array('code' => $code, 'redirect_uri' => $redirect_uri);
       header('Location: https://authenticate.trustpilot.com?client_id='.$apiKey.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code');
       exit;
    }*/

    /**
     * Get Authorization Token with Authrorization Code
     *
     * @param string $code
     * @param string $redirect_uri
     * @return mixed|String
     */
    public function createAuthToken($code = '', $redirect_uri = '')
    {        
       $data = array('code' => $code, 'redirect_uri' => $redirect_uri);
       $this->token = $this->createToken('authorization_code', $data);
       return $this->token;
    }

    /**
     * Get Authorization Token with authorization code
     *
     * @param $type
     * @param $data
     * @return mixed
     */
    protected function createToken($type,$data)
    {
        $body = array(
                    'grant_type' => $type
                );
        $fullBody = array_merge($body, $data);

        return $response = json_decode($this->api->post(
            'oauth/oauth-business-users-for-applications/accesstoken',
            array(
                'form_params' => $fullBody
            )
        ));
    }

    /**
     * Refresh the token
     */
    public function refreshToken()
    {
        $response = json_decode($this->api->post(
            'oauth/oauth-business-users-for-applications/refresh',
            array(
                'form_params' => array(
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->token->refresh_token
                )
            )
        ));

       $this->token = $response;
       return $response;

    }

    /**
     * Validate Token
     *
     * @return bool
     */
    public function isRefreshedToken()
    {
        
        if(!$this->isValidToken()){
            $this->refreshToken();
            return true;
        }
        return false;
    }

    /**
     * Check if the token is valid
     *
     * @return bool
     */
    public function isValidToken()
    {
        if(!isset($this->token)){
            return false;
        }
        $issued_at = intval(substr($this->token->issued_at, 0, -3));
        $expires_in = intval($this->token->expires_in);
        $expiry = $issued_at + $expires_in;

        $now = Carbon::now()->timestamp;
        
        if($now > ($expiry - 7200)){
            return false;
        }

        return true;

    }

}