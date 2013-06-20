<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="task-list">
	<div class="milestone-task <?php //if($task->status == StreamTodoType::COMPLETED) { echo 'toggle-item'; } ?>">
		<!--CSSHACK-->
		<div class="milestone-task-title">
			<a href="<?php echo $task->getUri(); ?>"><?php echo StreamTemplate::escape($task->message); ?></a>
			<span>&nbsp;&#8226;&nbsp;<a href="#toggleTasks">View all tasks</a></span>
		</div>
		<ul class="todolist noBorder" id="todo-list-<?php echo $task->id;?>">
			<?php
			$todoData = json_decode($task->raw);
			$todoIndex = 0;
			$numTodo = count($todoData->todo);
			$doneTodo = 0;
			foreach( $todoData->todo as $todo )
			{
				// @todo: move checking to input filtering
				$isDone = $task->getState($todoIndex);
				$doneBy = JXFactory::getUser( ( intval($task->getDoneBy($todoIndex))));
				$doneOn = $task->getDoneOn($todoIndex);
				$doneOn = empty($doneOn) ? '': ' - '.JXDate::formatDate($doneOn);
				if(!empty($todo)) {?>
					<li class="clearfix todo-item <?php if($isDone){ echo 'todo-done'; $doneTodo++; } ?>">
						<a href="javascript: void(0);" data-message_id="<?php echo $task->id; ?>" data-type="list" data-todo_index="<?php echo $todoIndex; ?>" class="done-todo-item <?php echo ($my->authorise('stream.todo.done',  $task)) ? '' :'readonly'; ?>"></a>
						<span><?php echo StreamMessage::format($todo); ?>
							<?php if($isDone){ ?>&nbsp;<span class="small hint"><?php echo $this->escape($doneBy->name); ?><?php echo $doneOn; ?></span><?php }?>
						</span>
					</li>
					<?php
					$todoIndex++;
				}
			}
			?>
		</ul>
	</div>
</div>	