<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2014-2017
 * @author     Cliff Parnitzky
 * @package    MonitoringTimeline
 * @license    LGPL
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Monitoring;

/**
 * Class MonitoringTimelineHookImpl
 *
 * Implementation of hooks.
 * @copyright  Cliff Parnitzky 2014-2017
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class MonitoringTimelineHookImpl extends \Backend
{
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Modify the header ... add the timeline
   * @param  $arrHeaderFields  the headerfields given from list->sorting
   * @param  DataContainer $dc a DataContainer Object
   * @return Array The manipulated headerfields
   */
  public function addTimelineToHeader($arrHeaderFields, \DataContainer $dc)
  {
    $monitoringEntryId = (int) $dc->id;
    
    // Make sure the dcaconfig.php file is loaded
    @include TL_ROOT . '/system/config/vis.js.php';
    
    $GLOBALS['TL_CSS'][] = 'assets/vis.js/' . VIS_JS . '/vis.min.css';
    $GLOBALS['TL_JAVASCRIPT'][] = 'assets/vis.js/' . VIS_JS . '/vis.min.js';
    
    $GLOBALS['TL_CSS'][] = 'system/modules/MonitoringTimeline/assets/timeline.min.css';
    
    $GLOBALS['TL_CSS'][] = 'system/modules/MonitoringTimeline/assets/timeline-menu.min.css';
    $GLOBALS['TL_MOOTOOLS'][] = '<script src="system/modules/MonitoringTimeline/assets/timeline-menu.min.js"></script>';
    
    $strData = "";
    $objMonitoringTest = \MonitoringTestModel::findByPid($monitoringEntryId, array('order' => "date"));
    if ($objMonitoringTest !== null)
    {
      $objLastMonitoringTestDate = null;
      if ($objMonitoringTest->next())
      {
        $objLastMonitoringTestDate = $objMonitoringTest->date;
      }
      while($objMonitoringTest->next())
      {
        $strData .= "{'start': new Date"
                      . "("
                        . date('Y', $objLastMonitoringTestDate) . ", "
                        . (date('m', $objLastMonitoringTestDate) - 1) . ", "
                        . date('d', $objLastMonitoringTestDate) . ", "
                        . date('H', $objLastMonitoringTestDate) . ", "
                        . date('i', $objLastMonitoringTestDate) . ", "
                        . date('s', $objLastMonitoringTestDate)
                      . "),"
                   . "'end': new Date"
                      . "("
                        . date('Y', $objMonitoringTest->date) . ", "
                        . (date('m', $objMonitoringTest->date) - 1) . ", "
                        . date('d', $objMonitoringTest->date) . ", "
                        . date('H', $objMonitoringTest->date) . ", "
                        . date('i', $objMonitoringTest->date) . ", "
                        . date('s', $objMonitoringTest->date)
                      . "), 'content': '&nbsp;', 'className': '" . strtolower($objMonitoringTest->status) . "'},";
        $objLastMonitoringTestDate = $objMonitoringTest->date;
      }
    }
    
    $today = time();
    
    $startDate = mktime(0, 0, 0, date("m", $today)  , date("d", $today) - 30, date("Y", $today));
    $startDateDay = date("d", $startDate);
    $startDateMonth = date("m", $startDate) - 1; // note that months are zero-based in the JavaScript Date object
    $startDateYear = date("Y", $startDate);
    
    $endDate = mktime(0, 0, 0, date("m", $today)  , date("d", $today) + 2, date("Y", $today));
    $endDateDay = date("d", $endDate);
    $endDateMonth = date("m", $endDate) - 1; // note that months are zero-based in the JavaScript Date object
    $endDateYear = date("Y", $endDate);
    
    $arrHeaderFields[$GLOBALS['TL_LANG']['tl_monitoring']['timeline'][0]] = <<<EOT
<div id="monitoring-timeline">
  <div id="monitoring-timeline-menu">
    <img id="zoomInTimeline" src="system/modules/MonitoringTimeline/assets/zoom-in.png" alt="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['zoom-in']}" title="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['zoom-in']}" />
    <img id="zoomOutTimeline" src="system/modules/MonitoringTimeline/assets/zoom-out.png" alt="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['zoom-out']}" title="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['zoom-out']}" />
    <img id="moveLeftTimeline" src="system/modules/MonitoringTimeline/assets/move-left.png" alt="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['move-left']}" title="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['move-left']}" />
    <img id="moveRightTimeline" src="system/modules/MonitoringTimeline/assets/move-right.png" alt="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['move-right']}" title="{$GLOBALS['TL_LANG']['MSC']['MonitoringTimeline']['menu']['move-right']}" />
  </div>
</div>

<script type="text/javascript">
  // create data
  // note that months are zero-based in the JavaScript Date object
  var data = new vis.DataSet([{$strData}]);

  // specify options
  var options = {
    editable: false,
    stack: false,
    selectable: false,
    start: new Date({$startDateYear}, {$startDateMonth}, {$startDateDay}, 0, 0, 0),
    end: new Date({$endDateYear}, {$endDateMonth}, {$endDateDay}, 0, 0, 0)
  };

  // create the timeline
  var container = document.getElementById('monitoring-timeline');
  timeline = new vis.Timeline(container, data, options);

</script>
EOT;
    
    return $arrHeaderFields;
  }
}

?>