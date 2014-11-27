<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2014
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
 * @copyright  Cliff Parnitzky 2014
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
		
		$objMonitoringTests = $this->Database->prepare("SELECT * FROM tl_monitoring_test WHERE pid = ? ORDER BY date")
											 ->execute($monitoringEntryId);
																				 
		$strData = "";
		while($objMonitoringTests->next())
		{
			$strData .= "{'start': new Date(" . date('Y', $objMonitoringTests->date) . ", "
					  . (date('m', $objMonitoringTests->date) - 1) . ", "
					  . date('d', $objMonitoringTests->date) . ", "
					  . date('H', $objMonitoringTests->date) . ", 0, 0), 'end': new Date("
					  . date('Y', $objMonitoringTests->date) . ", "
					  . (date('m', $objMonitoringTests->date) - 1) . ", "
					  . date('d', $objMonitoringTests->date) . ", "
					  . date('H', $objMonitoringTests->date) . ", 59, 59), 'content': '&nbsp;', 'className': '" . strtolower($objMonitoringTests->status) . "'},";
		}
		
		$arrHeaderFields[$GLOBALS['TL_LANG']['tl_monitoring']['timeline'][0]] = <<<EOT
<div id="monitoring-timeline">
	<div id="monitoring-timeline-menu">
		<img id="zoomIn" src="system/modules/MonitoringTimeline/assets/zoom-in.png" alt="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['zoom-in']}" title="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['zoom-in']}" />
		<img id="zoomOut" src="system/modules/MonitoringTimeline/assets/zoom-out.png" alt="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['zoom-out']}" title="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['zoom-out']}" />
		<img id="moveLeft" src="system/modules/MonitoringTimeline/assets/move-left.png" alt="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['move-left']}" title="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['move-left']}" />
		<img id="moveRight" src="system/modules/MonitoringTimeline/assets/move-right.png" alt="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['move-right']}" title="{$GLOBALS['TL_LANG']['tl_monitoring_test']['timeline']['menu']['move-right']}" />
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
		selectable: false
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