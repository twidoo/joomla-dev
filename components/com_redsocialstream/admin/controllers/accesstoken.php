<?php
/**
 * @package     redSocialstream
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
require_once (JPATH_COMPONENT . '/helpers/redsocialstream.php');

class AccessTokenController extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

    public function requestToken()
    {
        // Get data which was redirect from social site
        $input = JFactory::getApplication()->input;
        $session = JFactory::getSession();

        $session->set('postData', $input->getMethod());

        $generateToken = $input->get('generateToken', '', 'STRING');
        if(!isset($generateToken) || $generateToken == '')
        {
            $this->setRedirect('index.php?option=com_redsocialstream&view=accesstoken', JText::_('PLEASE_SELECT_SECTION'), 'WARNING');
        }
        else
        {
            $callbackUrl = urlencode(JURI::base() ."index.php?option=com_redsocialstream&controller=accesstoken&task=getaccesstoken&view=accesstoken");
            $model = $this->getModel('AccessToken');
            $settingData = RedSocialStreamHelper::getSettingData();

            switch($generateToken)
            {
                case 'facebook':
                    $fbProfileId    = $input->get('fb_profile_id', 0, 'INT');
                    $appId          = $settingData['app_id'];
                    $appSecret      = $settingData['app_secret'];

                    RedSocialStreamHelper::getFacebookAccessToken($appId, $appSecret, $callbackUrl);
                    break;

                case 'twitter':
                    $twitterProfileId   = $input->get('twitter_profile_id', 0, 'INT');
                    $consumerKey       = $settingData['twitter_consumer_key'];
                    $consumerSecret    = $settingData['twitter_consumer_sec'];

                    $bearerToken = RedSocialStreamHelper::getTwitterAccessToken($consumerKey, $consumerSecret);
                    if(!empty($bearerToken))
                    {
                        // Save to database
                        if($model->saveTwitterAcceesToken($twitterProfileId, $bearerToken))
                        {
                            $msg = JText::_('COM_REDSOCIALSTREAM_TWITTER_TOKEN_GENERATED');
                            $level = 'MESSAGE';
                        }
                        else
                        {
                            $msg = $model->getError();
                            if(empty($msg))
                            {
                                $msg = JText::_('COM_REDSOCIALSTREAM_TWITTER_TOKEN_UNGENERATED');
                            }
                            $level = 'WARNING';
                        }
                    }
                    else
                    {
                        $msg = JText::_('COM_REDSOCIALSTREAM_TWITTER_TOKEN_UNGENERATED');
                        $level = 'WARNING';
                    }
                    $this->setRedirect('index.php?option=com_redsocialstream&view=accesstoken', $msg, $level);

                    break;

                case 'linkedin':
                    break;
                case 'youtube':
                    break;
                default:
                    break;
            }
        }


        // Facebook
        $code = $input->get('code', '', 'STRING');
        if ($code != "")
        {
            $model = $this->getModel('AccessToken');
            $model->saveFacebookAcceesToken($code);
        }

        // Linkedin
        $oauth_verifier = $input->get('oauth_verifier', '', 'STRING');
        $oauth_token = $input->get('oauth_token', '', 'STRING');
        if ($oauth_verifier != "" && $oauth_token != "")
        {
            $model = $this->getModel('AccessToken');
            $model->saveLinkedinAcceesToken($oauth_token, $oauth_verifier);
        }
    }

    public function getAccessToken()
    {

    }

	public function cancel()
	{
		$this->setRedirect('index.php?option=com_redsocialstream');
	}
}
