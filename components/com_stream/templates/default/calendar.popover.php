<?php
$popOverContent = ''; 
$calContent ='';
	if(array_key_exists($day, $eventList)){
		foreach($eventList[$day] as $e){
			$date = new JDate($e->start_date);
			$data = $e->getData();
			
			$on = $date->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));	
			if($on != '00:00:00')
				$popOverContent .='<li><a href="'.$e->getUri().'"><div class="black-time">'.$on. '</div>';

			$popOverContent .= '<div class="black-info"><div class="black-message">'. JHtmlString::truncate($e->message, 54).'</div>';

			$location = (!empty($data->location))? $data->location : ''; 
			$popOverContent .='<div class="black-location">'.$location. '</div></div>';

			$popOverContent .='<div class="popover-tag"><span class="tag-event">' .ucfirst(JText::_('COM_STREAM_CALENDAR_LABEL_EVENT')). '</span></div>';

			$popOverContent .='<div class="clear"></div></a></li>';
		}
	}
	
	if(array_key_exists($day, $mstoneList)){
		foreach($mstoneList[$day] as $e){
			$date = new JDate($e->start_date);
			$data = $e->getData();
			
			// $on = $date->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));	
			// if($on != '00:00:00')
			// 	$popOverContent .='<li><div class="calendar-time">'.$on. '</div>';

			$popOverContent .= '<li><a href="'.$e->getUri().'"><div class="black-info"><div class="black-message">'.JHtmlString::truncate($e->message, 54).'</div>';
			$location = (!empty($data->location))? $data->location : ''; 
			$popOverContent .='<div class="black-location">'.$location. '</div></div>';

			$popOverContent .='<div class="popover-tag"><span class="tag-milestone">'.ucfirst(JText::_('COM_STREAM_CALENDAR_LABEL_MILESTONE')). '</span></div>';

			$popOverContent .='<div class="clear"></div></a></li>';
		}
	}
	
	if(array_key_exists($day, $todoList)){
		foreach($todoList[$day] as $e){
			$date = new JDate($e->end_date);
			$data = $e->getData();
			$popOverContent .= '<li><a href="'.$e->getUri().'"><div class="black-todo-message">'.JHtmlString::truncate($e->message, 54).'</div>';
			// $on = $date->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));
			//if(!empty($data->location))
			//	$popOverContent .='<span class="hint small">'.$data->location. '</span>';
				
			//if($on != '00:00:00')
			//	$popOverContent .='<span class="hint small"> - '.$on. '</span>';

			$popOverContent .='<div class="popover-tag"><span class="tag-todo">Todo</span></div>';

			$popOverContent .='<div class="clear"></div></a></li>';
		}
	}
	echo $popOverContent;		
