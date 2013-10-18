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
				<?php if ($allowInvite) { ?>
				<input class="btn" type="button" onclick="javascript: invitation.resendInvitation('<?php echo $rowData->invite_email;?>', '<?php echo $rowData->id;?>', this);" value="<?php echo JText::_('COM_ACCOUNT_LABEL_RESEND');?>" <?php echo ($rowData->status == $pendingStat) ? '' : 'disabled="disabled"';?>/>
				<?php } ?>
				<input class="btn" type="button" onclick="javascript: invitation.deleteInvitation('<?php echo $rowData->id;?>');" value="<?php echo JText::_('COM_ACCOUNT_LABEL_DELETE');?>" />
			</td>
		</tr>