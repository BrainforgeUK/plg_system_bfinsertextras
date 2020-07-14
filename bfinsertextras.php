<?php
/**
 * @package    Insert extra items into webpage by BrainforgeUK
 * @author    https://www.brainforge.co.uk
 * @copyright  (C) 2020 Jonathan Brain. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die('Restricted access');

class plgSystemBfinsertextras extends CMSPlugin
{
	public function onAfterRender()
	{
		$app = Factory::getApplication();

		if ($app->isClient('administrator'))
		{
			return;
		}

		$extras = $this->params->get('extras');
		if (empty($extras))
		{
			return;
		}

		$body = $app->getBody();

		foreach($extras as $extra)
		{
			if (empty($extra->status))
			{
				continue;
			}

			if (empty($extra->access))
			{
				continue;
			}

			if (!isset($userAccessLevels))
			{
				$userAccessLevels = JAccess::getAuthorisedViewLevels(Factory::getUser()->id);
			}

			if (empty(array_intersect($extra->access, $userAccessLevels)))
			{
				continue;
			}

			switch($extra->location)
			{
				case 'endbody':
					$search = '</body>';
					$replace = $extra->extracode . $search;
					break;
				default:
					continue 2;
			}

			$body = str_replace($search, $replace, $body);
		}

		$app->setBody($body);
	}
}
