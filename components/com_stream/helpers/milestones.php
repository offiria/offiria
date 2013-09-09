<?php
class StreamMilestonesHelper {
	
	/**
	 *
	 */	 	
	public static function getSelectList($group_id = null, $selected = '')
	{
		$streamModel = StreamFactory::getModel('stream');
		$milestones = $streamModel->getStream(array('type' => 'milestone', 'group_id'=> $group_id ));
		
		$html = ' <select name="milestone"><option value="">None</option>';

		$now = new JDate();
		
		foreach( $milestones as $mstone ){

			// Don't list overdue milestones
			$startDate = new JDate($mstone->start_date);
			$dateDiff = JXDate::timeDifference($startDate->toUnix(), $now->toUnix());
			if((!empty($dateDiff['days']) && ($dateDiff['days'] > 0) )) {
				continue;
			}

			$html.= '<option value="'. $mstone->id.'">'.$mstone->message.'</option>';
		}
		$html.= '</select>';
		
		return $html;
	}
}
?>
