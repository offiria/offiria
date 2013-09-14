		<tr id="row<?php echo $rowData->id;?>">
			<td><div class="key"><span><?php echo $rowData->from_email;?></span></div></td>
			<td class="to-update">
				<div class="key">
					<span><?php echo $rowData->invite_email;?></span>
					<span class="small">
						<?php echo JText::_('COM_ACCOUNT_LABEL_LAST_INVITED_DATE');?>:
						<span class="last-invite-date"><?php echo JXDate::formatDate($rowData->last_invite_date);?></span>

						<?php if( !empty($rowData->group_limited)): ?>
							<?php echo JText::_('Limited to these groups');?>:
							<span class="last-invite-date">
								<?php
									$groupModel    = StreamFactory::getModel('groups');
									$limitedGroups = $groupModel->getGroups(array('id' => $rowData->group_limited), 100);

									foreach($limitedGroups as $idx=>$group) {
										echo '&bull;&nbsp;' . StreamTemplate::escape($group->name) . '<br />';
									}
								?>
							</span>
						<?php endif; ?>
					</span>
				</div>
			
			</td>
			<td><span class="label label-info"><?php echo $rowData->translateStatus($rowData->status);?></span></td>
			<td>
				<div class="custom-listing joined">
				<h3 class="clearfix">
				<?php if ($allowInvite) { ?>
				<a id="group-create" class="btn" href="#" onclick="javascript: invitation.resendInvitation('<?php echo $rowData->invite_email;?>', '<?php echo $rowData->id;?>', this);" <?php echo ($rowData->status == $pendingStat) ? '' : 'disabled="disabled"';?>><span><?php echo JText::_('COM_ACCOUNT_LABEL_RESEND');?></span></a>
				<?php } ?></h3><h3 class="clearfix">
				<a id="group-create" class="btn" href="#" onclick="javascript: invitation.deleteInvitation('<?php echo $rowData->id;?>');"><span><?php echo JText::_('COM_ACCOUNT_LABEL_DELETE');?></span></a>
				</h3>
				</div>			
			</td>
		</tr>