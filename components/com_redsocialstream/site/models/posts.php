<?php
/**
 * @package     redSocialstream
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
require_once(JPATH_SITE . '/components/com_redsocialstream/helpers/helper.php');

class redsocialstreamModelposts extends JModel
{
	var $_pagination = null;
	var $_total = null;
	function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__';
	}

	function getfeedslist($groupid = "", $profiletypeid = "")
	{
		$mainframe = JFactory::getApplication();

		$params = $mainframe->getparams();
		if ($groupid == "")
		{
			if ($params->get('groupid') != "")
			{
				$groupid = $params->get('groupid');
			}
		}
		if ($profiletypeid == "")
		{
			if ($params->get('profiletypeid') != "")
			{
				$profiletypeid = $params->get('profiletypeid');
			}
		}

		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__redsocialstream_profilereference";
		if ($groupid != "" or $profiletypeid != "")
		{
			$query .= " WHERE (";
			if ($groupid != "")
			{
				if (is_array($groupid))
				{
					foreach ($groupid as $key => $id)
					{
						$query .= "groupid = '" . $id . "' OR ";
					}
					$query = substr($query, 0, -4);
				}
				else
				{
					$query .= "groupid = " . $groupid;
				}
			}
			if ($groupid != "" AND $profiletypeid != "")
			{
				$query .= ") AND (";
			}
			if ($profiletypeid != "")
			{
				if (is_array($profiletypeid))
				{
					foreach ($profiletypeid as $key => $id)
					{
						$query .= "profiletypeid = '" . $id . "' OR ";
					}
					$query = substr($query, 0, -4);
				}
				else
				{
					$query .= "profiletypeid = " . $profiletypeid;
				}
			}
			if ($groupid != "" OR $profiletypeid != "")
			{
				$query .= ") AND published = 1 AND update_time < '" . date('Y-m-d H:i:s') . "'";
			}
		}

		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}

	function getposts($group_id = "", $profile_type_id = "", $limit = "", $orderby = "p.created_time DESC")
	{

		$mainframe = JFactory::getApplication();
		$params = $mainframe->getparams();
		if ($orderby == "")
		{
			$orderby = " p.created_time DESC ";
		}
		if ($group_id == "")
		{
			if ($params->get('groupid') != "")
			{
				$group_id = $params->get('groupid');
			}
		}
		if ($profile_type_id == "")
		{
			if ($params->get('profiletypeid') != "")
			{
				$profile_type_id = $params->get('profiletypeid');
			}
		}
		if ($limit == "")
		{
			if ($params->get('limit') != "")
			{
				$limit = $params->get('limit');
			}
		}

		$db = JFactory::getDbo();
		$query = "SELECT p.*,t.title as type_title FROM #__redsocialstream_posts as p
  				LEFT JOIN #__redsocialstream_profilereference as pr ON p.profile_id = pr.id LEFT JOIN #__redsocialstream_profiletype as t ON pr.profiletypeid = t.id ";

		$query .= " WHERE p.published = 1";
		if ($group_id != "")
		{
			if (is_array($group_id))
			{
				foreach ($group_id as $key => $id)
				{
					$query .= " AND( p.group_id = '" . $id . "' OR ";
				}
				$query = substr($query, 0, -4);
			}
			else
			{
				$query .= " AND( p.group_id = " . $group_id;
			}
		}
		if ($group_id != "" AND $profile_type_id != "")
		{
			$query .= ") AND (";
		}
		if ($profile_type_id != "")
		{
			if (is_array($profile_type_id))
			{
				foreach ($profile_type_id as $key => $id)
				{
					$query .= "p.type = '" . $id . "' OR ";
				}
				$query = substr($query, 0, -4);
			}
			else
			{
				$query .= "p.type = " . $profile_type_id;
			}
		}
		if ($group_id != "" OR $profile_type_id != "")
		{
			$query .= ")";
		}

		$query .= " ORDER BY " . $orderby . " limit " . $limit . "";
		//echo "Response : ".$query;exit;
		$db->setQuery($query);
		$row = $db->loadObjectList();

		return $row;
	}

	function getfeeds($groupid = "", $profiletypeid = "", $oauth_token = "")
	{
        $redsocialHelper = new redsocialHelper();

		$red_facebook_unsorted_objlist = array();
		$youtubevideodata = array();
		$tweetdatalist = array();
		$linkedindatalist = array();
		$ids = array();


		$getfeedslist = $this->getfeedslist($groupid, $profiletypeid);

		$i = 1;
		$FBids = "";
		$youtubeusernames = "";
		$twitterProfiles = "";
		$linkedinprofiles = "";
		foreach ($getfeedslist as $list)
		{
			if ($list->profiletypeid == FACEBOOK)
			{
				if ($list->profilename != "")
				{
					$db = JFactory::getDbo();
					$query = "SELECT created_time FROM #__redsocialstream_posts WHERE profile_id = " . $list->id . " ORDER BY created_time DESC LIMIT 1";
					$db->setQuery($query);
					$created_time = $db->loadObject();
					$FBids[$i]['title'] = $list->profilename;
					$FBids[$i]['id'] = $list->id;
					$FBids[$i]['update'] = $list->update_time;
					$FBids[$i]['group_id'] = $list->groupid;
					if (isset($created_time->created_time))
					{
						$FBids[$i]['newest_post'] = $created_time->created_time;
					}
					else
					{
						$FBids[$i]['newest_post'] = "";
					}
					$i++;
				}
			}

			if ($list->profiletypeid == YOUTUBE)
			{
				if ($list->profilename != "")
				{
					$db = JFactory::getDbo();
					$query = "SELECT created_time FROM #__redsocialstream_posts WHERE profile_id = " . $list->id . " ORDER BY created_time DESC LIMIT 1";
					$db->setQuery($query);
					$created_time = $db->loadObject();
					$youtubeusernames[$i]['title'] = $list->profilename;
					$youtubeusernames[$i]['id'] = $list->id;
					$youtubeusernames[$i]['update'] = $list->update_time;
					if (isset($created_time->created_time))
					{
						$youtubeusernames[$i]['newest_post'] = $created_time->created_time;
					}
					else
					{
						$youtubeusernames[$i]['newest_post'] = "";
					}
					$youtubeusernames[$i]['group_id'] = $list->groupid;
					$i++;
				}
			}

			if ($list->profiletypeid == TWITTER)
			{
				if ($list->profilename != "")
				{
					$db = JFactory::getDbo();
					$query = "SELECT created_time,ext_post_id FROM #__redsocialstream_posts WHERE profile_id = " . $list->id . " ORDER BY created_time DESC LIMIT 1";
					$db->setQuery($query);
					$created_time = $db->loadObject();
					$twitterProfiles[$i]['title'] = $list->profilename;
					$twitterProfiles[$i]['id'] = $list->id;
					$twitterProfiles[$i]['update'] = $list->update_time;

					if (isset($created_time->created_time))
					{
						$twitterProfiles[$i]['newest_post'] = $created_time->created_time;
					}
					else
					{
						$twitterProfiles[$i]['newest_post'] = "";
					}

					if (isset($created_time->ext_post_id))
					{
						$twitterProfiles[$i]['ext_post_id'] = $created_time->ext_post_id;
					}
					else
					{
						$twitterProfiles[$i]['ext_post_id'] = "";
					}

					$twitterProfiles[$i]['group_id'] = $list->groupid;
					$i++;
				}
			}

			if ($list->profiletypeid == LINKEDIN)
			{
				$linkedlistdata['group_id'] = $list->groupid;
				$linkedlistdata['profile_id'] = $list->id;
			}
			$ids[] = $list->id;
		}

		$db = JFactory::getDbo();
		$query = "UPDATE #__redsocialstream_profilereference SET update_time=Now() WHERE id IN(" . implode(',', $ids) . ")";
		$db->setQuery($query);
		$db->query();

		if ($FBids != "" && isset($FBids))
		{
            $fbDataList = $redsocialHelper->getFacebookData($FBids);
            if (isset($fbDataList))
            {
                $this->savePost($fbDataList);
            }
		}
		if ($youtubeusernames != "" && isset($youtubeusernames))
		{
			$youtubevideodata = $this->getyoutubedata($youtubeusernames);
		}

		if ($twitterProfiles != "" && isset($twitterProfiles))
		{
			$tweetDataList = $redsocialHelper->getTwitterData($twitterProfiles);

            if (isset($tweetDataList))
            {
                $this->savePost($tweetDataList);
            }
		}

		if ($list->profiletypeid == LINKEDIN)
		{
			$linkedindatalist = $this->getlinkedindata($linkedlistdata);
		}

	}

	function getyoutubedata($youtubeusernames)
	{
		$mainframe = JFactory::getApplication();

		$params = $mainframe->getparams();
		$limit = 2 * $params->get('limit');
		$youtubevideodata = array();
		foreach ($youtubeusernames as $youtubeusername)
		{
			if ($youtubefeeds = file_get_contents('https://gdata.youtube.com/feeds/api/users/' . $youtubeusername['title'] . '/uploads?v=2&alt=json&limit=' . $limit))
			{
				$youtubevideolists[$youtubeusername['id']] = json_decode($youtubefeeds);
				$keyid = $youtubeusername['id'];
				$group_id = $youtubeusername['group_id'];
			}
		}

		$i = 0;

		foreach ($youtubevideolists as $youtubevideolist)
		{

			foreach ($youtubevideolist->feed->entry as $key => $youtubevideo)
			{
				foreach ($youtubevideolist->feed->author[0]->uri as $uri)
				{
				}

				$youtubeporfile = file_get_contents($uri . "?alt=json&fields=media:thumbnail");
				$youtubeporfile = json_decode($youtubeporfile);
				foreach ($youtubeporfile->entry as $proimgkey => $proimgval)
				{

					if ($proimgkey == 'media$thumbnail')
					{

						$profileimgurl = $youtubeporfile->entry->$proimgkey->url;
					}
				}
				foreach ($youtubevideo->published as $youtubepublishedtime)
				{
					$publishedtime = $youtubepublishedtime;
				}
				foreach ($youtubevideo->id as $youtubeid)
				{
				}

				if (date("Y-m-d H:i:s", strtotime($youtubepublishedtime)) > $youtubeusername['newest_post'])
				{

					$youtubevideodata[$i]['profileimgurl'] = $profileimgurl;

					$youtubevideodata[$i]['data'] = $youtubevideo;

					$youtubevideodata[$i]['created_time'] = $publishedtime;

					$youtubevideodata[$i]['type'] = 'youtube';

					$savedata[$i]['type'] = YOUTUBE;

					foreach ($youtubevideo as $key => $test)
					{
						$j = 0;
						if ($key == 'media$group')
						{
							foreach ($test as $innerkey => $hat)
							{
								if ($innerkey == 'yt$duration')
								{
									$savedata[$i]['duration'] = $hat->seconds;
								}
								if ($innerkey == 'media$description')
								{
									foreach ($hat as $t => $hathat)
									{
										if ($t == '$t')
										{
											$savedata[$i]['message'] = addslashes($hathat);
										}
									}
								}
							}

						}
					}

					foreach ($youtubevideo->author as $authordata)
					{

						foreach ($authordata as $key => $values)
						{

							foreach ($values as $name)
							{
								$savedata[$i]['ext_post_name'] = $name;
							}
							if ($key == 'yt$userId')
							{
								foreach ($values as $value)
								{
									$savedata[$i]['ext_profile_id'] = $value;
								}
								//echo $value;
							}
						}
					}
					foreach ($youtubevideo->title as $title)
					{
						$savedata[$i]['title'] = addslashes($title);
					}
					$savedata[$i]['thumb_uri'] = $profileimgurl;
					$savedata[$i]['ext_post_id'] = $youtubeid;
					$savedata[$i]['ext_post_name'] = $youtubeusername['title'];
					$savedata[$i]['source_link'] = $youtubevideo->content->src;
					$savedata[$i]['created_time'] = date("Y-m-d H:i:s", strtotime($youtubepublishedtime));
					$savedata[$i]['profile_id'] = $keyid;
					$savedata[$i]['group_id'] = $group_id;
					$savedata[$i]['published'] = 1;
				}
				$i++;
			}
		}

		if (isset($savedata))
		{
			$this->savePost($savedata);
		}
		return $youtubevideodata;
	}

	function getlinkedindata($linkedlistdata)
	{
		$session = JFactory::getSession();
		$redsocialhelper = new redsocialhelper();

		$login = $redsocialhelper->getsettings();

		global $mainframe;

		$api_key = $login['linked_api_key']; // Linkedin Api key
		$secret_key = $login['linked_secret_key']; //Linkedin Secret key

		$access_tokens = $this->getLinkedinAccessToken();

		include(JPATH_SITE . '/components/com_redsocialstream/helpers/linkedin/linkedin.php');
		require_once(JPATH_SITE . '/components/com_redsocialstream/helpers/twitter/OAuth.php');


		$API_CONFIG = array(
			'appKey' => $api_key,
			'appSecret' => $secret_key,
			'callbackUrl' => ""
		);
		$linkedin = new LinkedIn($API_CONFIG);
		$access_token = $access_tokens[0]->linkedin_token;
		$access_secret = $access_tokens[0]->linkedin_secret;
		$token = Array
		(

			"oauth_token" => $access_token,
			"oauth_token_secret" => $access_secret,

		);
		$linkedin->setTokenAccess($token);

		$query = '?type=SHAR&count=30';
		$response = $linkedin->updates($query);

		if ($response['success'] === TRUE)
		{
			$updates = new SimpleXMLElement($response['linkedin']);

			if ((int) $updates['total'] > 0)
			{
				$i = 0;
				foreach ($updates->update as $update)
				{

					$person = $update->{'update-content'}->person;
					$share = $update->{'update-content'}->person->{'current-share'};

					$linkedinlist[$i]['data'] = $update;
					$linkedinlist[$i]['created_time'] = date("Y-m-d", trim($share->timestamp));
					$linkedinlist[$i]['type'] = 'linkedin';
					$savedata[$i]['group_id'] = $linkedlistdata['group_id'];
					$savedata[$i]['type'] = LINKEDIN;
					$savedata[$i]['ext_profile_id'] = $person->{id};
					$savedata[$i]['ext_post_id'] = $share->{id};
					$savedata[$i]['ext_post_name'] = $person->{'first-name'} . ' ' . $person->{'last-name'};
					if (isset($share->comment))
					{
						$savedata[$i]['message'] = addslashes($share->comment);
					}
					$savedata[$i]['title'] = '';
					$savedata[$i]['source_link'] = $person->{'site-standard-profile-request'}->url;
					$savedata[$i]['created_time'] = date("Y-m-d", trim($share->timestamp));
					$savedata[$i]['duration'] = '';
					$savedata[$i]['profile_id'] = $linkedlistdata['profile_id'];

					$savedata[$i]['published'] = 1;
					$savedata[$i]['thumb_uri'] = $person->{'picture-url'};
					$i++;
				}
			}
		}

		if (isset($savedata))
		{
			$this->savePost($savedata);
		}

		return $linkedinlist;

	}

	function getgroupdata($id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__redsocialstream_group WHERE id = " . $id;
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}

	function  getLinkedinAccessToken()
	{
		//$profile_id = $twitterprofile['id'];
		$db = JFactory::getDbo();
		//$query = "SELECT * FROM #__redsocialstream_twitter_accesstoken where profile_id='".$profile_id."'";
		$query = "SELECT * FROM #__redsocialstream_linkedin_accesstoken";
		$db->setQuery($query);
		$row = $db->loadObjectList();
		return $row;
	}

    /**
     * @param $data
     */
    function savePost($data)
    {
        $db = JFactory::getDbo();
        $insert_values = "";

        foreach ($data as $values)
        {
            $db = JFactory::getDbo();
            $query = "SELECT * FROM #__redsocialstream_posts WHERE ext_post_id = '" . $values['ext_post_id'] . "' and group_id = '" . $values['group_id'] . "' ORDER BY created_time DESC LIMIT 1";
            $db->setQuery($query);
            $checkvalue = $db->loadObject();
            if ($checkvalue == "")
            {
                $insert_values .= "(null,'" . $values['type'] . "','" . $values['ext_profile_id'] . "','" . $values['message'] . "','" . $values['source_link'] . "','" . $values['created_time'] . "','" . $values['duration'] . "','" . $values['profile_id'] . "', '" . $values['group_id'] . "', '" . $values['published'] . "', '" . $values['ext_post_id'] . "', '" . $values['ext_post_name'] . "', '" . $values['title'] . "', '" . $values['thumb_uri'] . "') , ";
            }
        }

        $insert_values = substr($insert_values, 0, -2);
        $q = "INSERT INTO #__redsocialstream_posts (id, type, ext_profile_id, message, source_link, created_time, duration, profile_id, group_id, published, ext_post_id, ext_post_name, title, thumb_uri) VALUES " . $insert_values;
        $db->setQuery($q);
        $db->query();
    }
} 
