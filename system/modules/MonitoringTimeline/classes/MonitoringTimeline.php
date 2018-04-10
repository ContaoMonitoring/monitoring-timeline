<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2018 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2018-2018
 * @author     Cliff Parnitzky
 * @package    MonitoringTimeline
 * @license    LGPL
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Monitoring;

/**
 * Class MonitoringTimeline
 *
 * Contains functions to be used with the timeline components.
 * @copyright  Cliff Parnitzky 2018-2018
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class MonitoringTimeline extends \Backend
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Get all ids of the current filtered list of monitoring entries 
   */
  public function navigateToMonitoringTimeline()
  {
    $arrFilter = \Session::getInstance()->get('filter')['tl_monitoring'];
    unset($arrFilter['limit']);
    
    $select = "SELECT id FROM tl_monitoring";
    if (!empty($arrFilter))
    {
      $select .= " WHERE " . implode(" = ? AND ", array_keys($arrFilter)) . " = ?";
    }
    
    $objIds = \Database::getInstance()->prepare($select)
                                      ->execute(array_values($arrFilter));
    
    $arrIds = $objIds->numRows ? $objIds->fetchEach('id') : array();

    $path = \Environment::get('base') . 'contao/main.php?do=monitoringTimeline';
    if (!empty($arrIds))
    {
      $path .= '&amp;ids=' . implode(',', $arrIds);
    }
    $this->redirect($path, 301);
  }

}

?>