<?php 
$config 	  = new JXConfig();
$allow_invite = $config->get('allow_invite');
?>
<link rel="stylesheet" href="<?php echo JURI::root();?>media/uploader/fileuploader.css" type="text/css" />
<script src="<?php echo JURI::root();?>media/uploader/fileuploader.js" type="text/javascript"></script>
<div class="welcome-message modal" style="display:none;">

		<div class="modal-header">
			<h2 id="header"><?php echo JText::sprintf('COM_STREAM_LABEL_WELCOME_TO_COMPANY', JText::_('CUSTOM_SITE_NAME'));?></h2>
			<?php /*<h4>Here are some few basic features that you need to know:</h4>*/?>
		</div>
		
		<div id="step-1-avatar" class="modal-body">
			<div class="modal-title">
				<ul class="steps clearfix <?php echo ($allow_invite == '1') ? '' : 'is-user';?>">
					<li class="step1"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD_AVATAR');?></li>
					<li class="steps-no-divider"></li>
					<?php if ($allow_invite == '1') { ?>
					<li><?php echo JText::_('COM_STREAM_LABEL_INVITE_FRIENDS');?></li>
					<li class="steps-no-divider"></li>
					<?php } ?>
					<li><?php echo JText::_('COM_STREAM_LABEL_FEATURES');?></li>
					<li class="steps-no-divider"></li>
					<li><?php echo JText::_('COM_STREAM_LABEL_FEATURES');?></li>
				</ul>
				<h3><?php echo JText::_('COM_STREAM_LABEL_PICK_AVATAR');?></h3>
			</div>
			
			<div class="pull-left">
				<h4><?php echo JText::_('COM_STREAM_LABEL_CHOOSE_YOUR_OWN');?></h4>	
				<div id="upload-avatar">
					<noscript>
						<p>Please enable JavaScript to use file uploader.</p>
						<!-- or put a simple form for upload here -->
					</noscript>
				</div>
				<div class="avatar-container">
					<span>
						<img class="avatar-preview" src="<?php echo $my->getThumbAvatarURL();?>" />
					</span>
				</div>
			</div>
			<div class="pull-right">
				<h4><?php echo JText::_('COM_STREAM_LABEL_PICK_ONE_HERE');?></h4>
				<ul class="avatars-list clearfix">
					<li><label><img src="<?php echo JURI::base().'images/avatar/male_1.png';?>" alt="Option 1" /><input type="radio" name="sample_avatar" value="male_1" /></label></li>
					<li><label><img src="<?php echo JURI::base().'images/avatar/male_2.png';?>" alt="Option 2" /><input type="radio" name="sample_avatar" value="male_2" /></label></li>
					<li><label><img src="<?php echo JURI::base().'images/avatar/male_3.png';?>" alt="Option 3" /><input type="radio" name="sample_avatar" value="male_3" /></label></li>
					<li><label><img src="<?php echo JURI::base().'images/avatar/female_1.png';?>" alt="Option 4" /><input type="radio" name="sample_avatar" value="female_1" /></label></li>
					<li><label><img src="<?php echo JURI::base().'images/avatar/female_2.png';?>" alt="Option 5" /><input type="radio" name="sample_avatar" value="female_2" /></label></li>
					<li><label><img src="<?php echo JURI::base().'images/avatar/female_3.png';?>" alt="Option 6" /><input type="radio" name="sample_avatar" value="female_3" /></label></li>
				</ul>
			</div>
			<div class="clear"></div>
			<div class="modal-footer">
				<input type="button" class="btn btn-info progress-button" value="<?php echo JText::_('COM_STREAM_LABEL_BUTTON_NEXT');?>" id="first-step-next" data-progress="modalSteps['first']"/>
			</div>
		</div>
	
		<div id="step-2-invitation" class="modal-body" style="display:none">
			
			<div class="modal-title">
				<ul class="steps clearfix <?php echo ($allow_invite == '1') ? '' : 'is-user';?>">
					<li class="step1"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD_AVATAR');?></li>
					<li class="steps-divider"></li>
					<?php if ($allow_invite == '1') { ?>
					<li class="step2"><?php echo JText::_('COM_STREAM_LABEL_INVITE_FRIENDS');?></li>
					<li class="steps-no-divider"></li>
					<?php } ?>
					<li><?php echo JText::_('COM_STREAM_LABEL_FEATURES');?></li>
					<li class="steps-no-divider"></li>
					<li><?php echo JText::_('COM_STREAM_LABEL_FEATURES');?></li>
				</ul>
				<h3><?php echo JText::_('COM_STREAM_LABEL_THEN_INVITE_3_FRIENDS');?></h3>
			</div>
			
			<div class="invitation-message alert alert-error" style="display:none"></div>
			
			<form method="get" id="guest_invite_form" action="/component/account/?view=invite">
				
				<ul class="invite-form">
					<li>
						<label><?php echo JText::_('COM_STREAM_LABEL_EMAIL1');?></label>
						<input type="text" id="guest_email_1" name="invitation">
					</li>
					<li>
						<label><?php echo JText::_('COM_STREAM_LABEL_EMAIL2');?></label>
						<input type="text" id="guest_email_2" name="invitation">
					</li>
					<li>
						<label><?php echo JText::_('COM_STREAM_LABEL_EMAIL3');?></label>
						<input type="text" id="guest_email_3" name="invitation">
					</li>
					<li>
						<!--span><a href="javascript:void(0);" onclick="gettingStarted.showHighlight(0);"><?php echo JText::_('COM_STREAM_LABEL_SKIP_THIS');?></a></span-->
					</li>
				</ul>
				<div class="modal-footer">
					<span id="invitation-message" style="display:none"><?php echo JText::_('COM_STREAM_LABEL_SENDING_INVITATION');?></span>
					<input type="button" class="btn btn-warning progress-button" onclick="gettingStarted.showHighlight(0);" value="<?php echo JText::_('COM_STREAM_LABEL_SKIP_THIS');?>" id="skip-invite-3-friends" />
					<input type="button" class="btn btn-info progress-button" value="<?php echo JText::_('COM_STREAM_LABEL_BUTTON_INVITE');?>" id="invite-3-friends" data-progress="modalSteps['second']" />
				</div>
				
			</form>
			
		</div>
	
		<!-- SLIDER STARTS HERE -->
		<div id="step-3-feature-highlight" class="modal-body" style="display:none">
			
			<div class="modal-title">
				<ul class="steps clearfix <?php echo ($allow_invite == '1') ? '' : 'is-user';?>">
					<li class="step1"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD_AVATAR');?></li>
					<li class="steps-divider"></li>
					<?php if ($allow_invite == '1') { ?>
					<li class="step2"><?php echo JText::_('COM_STREAM_LABEL_INVITE_FRIENDS');?></li>
					<li class="steps-divider"></li>
					<?php } ?>
					<li class="step4"><?php echo JText::_('COM_STREAM_LABEL_VIEW_FEATURES');?></li>
					<li class="steps-no-divider"></li>
					<li><?php echo JText::_('COM_STREAM_LABEL_FEATURES');?></li>
				</ul>
				<h3><?php echo JText::_('COM_STREAM_LABEL_INTRODUCE_FEATURE');?></h3>
			</div>
			
			<div id="welcome-slider">

				<div class="slider-container">

					<!-- IMAGE CONTAINER -->
					<div class="image-container">
						<div id="image-feature-1">
							<h3 class="image-title"><?php echo JText::_('COM_STREAM_LABEL_INTRODUCE_FEATURE1_TITLE');?></h3>
							<div class="img-container-one clearfix">
								<div class="img-container-one-inner">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-stream-message.png" alt="Stream Message" />
								</div>
								<div class="img-container-one-inner second-img">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-pinned-message.png" alt="Pinned Stream Message" />
								</div>
							</div>
						</div>
						<div id="image-feature-2" style="display:none">
							<h3 class="image-title"><?php echo JText::_('COM_STREAM_LABEL_INTRODUCE_FEATURE2_TITLE');?></h3>
							<div class="img-container-two clearfix">
								<div class="img-container-two-inner">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-milestone-task.png" alt="Milestone / Task" />
								</div>
								<div class="img-container-two-inner second-img">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-milestone-task.png" alt="Milestone / Task" />
								</div>
							</div>
						</div>
						<div id="image-feature-3" style="display:none">
							<h3 class="image-title"><?php echo JText::_('COM_STREAM_LABEL_INTRODUCE_FEATURE3_TITLE');?></h3>
							<div class="img-container-three clearfix">
								<div class="img-container-three-inner">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-event-management.png" alt="Event Management" />
								</div>
								<div class="img-container-three-inner second-img">
									<img src="<?php echo JURI::base(); ?>templates/offiria/images/welcome/nw-event-management.png" alt="Event Management" />
								</div>
							</div>
						</div>
					</div>

				</div>

				<!-- THUMBNAIL CONTAINER -->
				<!--div class="feature-container">
					<ul>
						<li id="feature-1" class="active tips" original-title="Activity Stream"><span class="info-balloon"></span></li>
						<li id="feature-2" class="tips" original-title="Group Page"><span class="info-balloon"></span></li>
						<li id="feature-3" class="tips" original-title="Project Milestone"><span class="info-balloon"></span></li>
					</ul>

					<div class="clear"></div>
				</div-->

				<!-- INFOS CONTAINER -->
				<div class="feature-info-container">
					<div id="info-feature-1">
						<p><?php echo JText::sprintf('COM_STREAM_LABEL_INTRODUCE_FEATURE1_DESC', JText::_('CUSTOM_SITE_NAME'));?></p>
					</div>
					<div id="info-feature-2" style="display:none">
						<p><?php echo JText::sprintf('COM_STREAM_LABEL_INTRODUCE_FEATURE2_DESC', JText::_('CUSTOM_SITE_NAME'));?></p>
					</div>
					<div id="info-feature-3" style="display:none">
						<p><?php echo JText::sprintf('COM_STREAM_LABEL_INTRODUCE_FEATURE2_DESC', JText::_('CUSTOM_SITE_NAME'));?></p>
					</div>
				</div>

			</div>
			<!-- END OF SLIDER -->

			<div class="modal-footer">
				<a href="#" data-show_feature="1" class="btn btn-info progress-button"  data-progress="gettingStarted.showHighlight(1)"><?php echo JText::_('COM_STREAM_LABEL_BUTTON_NEXT');?></a>
			</div>
		</div>
	
	</div>
	
</div>

<script type="text/javascript">

var gettingStarted = {
	showAvatar: function() {
		$('div.modal-body').fadeOut().hide();
		$('div#step-1-avatar').fadeIn().show();
	},
	useDefAvatar: function() {
		if ($('input:radio:checked').val())
		{			
			$.ajax({
				url: "<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=ajaxUseDefAvatar');?>",
				dataType: "json",
				data: 'selection='+$('input:radio:checked').val(),
				type: "post",
				success: function( data ) {		
					$('img.avatar-preview').attr('src', data.preview);		
				}
			});
		}	
	},
	firstToSecond: function() {
		gettingStarted.useDefAvatar();
		gettingStarted.showInvitation();
	},
	showInvitation: function() {				
		$('div.modal-body').fadeOut().hide();
		$('div#step-2-invitation').fadeIn().show();
	},
	sendInvitation: function() {
		if ($('#guest_email_1').val() == '' || $('#guest_email_2').val() == '' || $('#guest_email_3').val() == '')
		{
			showInvitationMsg('<?php echo JText::_('COM_STREAM_LABEL_SENDING_ERROR');?>');
			return false;
		}
		$.ajax({
			url: "<?php echo JRoute::_('index.php?option=com_account&view=invite&task=ajaxMemberInvite');?>",
			dataType: "json",
			data: 'inviteType=welcome&invitation='+$('#guest_email_1').val()+','+$('#guest_email_2').val()+','+$('#guest_email_3').val(),
			type: "post",
			beforeSend: function( xhr ) {
				$('#invitation-message, #invite-3-friends').toggle();
			},
			success: function( data ) {	
				$('#invitation-message, #invite-3-friends').toggle();			
				var error = (data.error || '1')
				var msg = (data.msg || '')
				if (error == '1')
				{			
					showInvitationMsg(msg);
					setTimeout("$('#guest_email_1').focus()", 100);
					return false;
				}
				gettingStarted.showHighlight(0);
			}
		});
	},
	secondToThird: function() {
		gettingStarted.sendInvitation();
	},
	firstToThird: function() {
		gettingStarted.useDefAvatar();
		gettingStarted.showHighlight(0);
	},
	showHighlight: function(count) {
		if (count == 3) // largest number
		{
			$.ajax({
				type: "POST",
				url: S.path['system.hidewelcome'],
				dataType: 'json',
				cache: false,
				success: function(data){
					window.location.href = '<?php echo JURI::base();?>';
				}
			});
		}
		else
		{
			var next = parseInt(count) + 1;
			$('div.modal-body').hide();
			$('div#step-3-feature-highlight').fadeIn().show();
			
			$('div#image-feature-'+count+', div#info-feature-'+count).fadeOut().hide();
			$('div#image-feature-'+next+', div#info-feature-'+next).fadeIn().show();
			$('li#feature-'+count).removeClass('active');
			$('li#feature-'+next).addClass('active');
			$('a.btn-info').data('progress', 'gettingStarted.showHighlight('+next+')');
			
			if (next == 3)
			{
				$('a.btn-info').html('<?php echo JText::sprintf('COM_STREAM_LABEL_BUTTON_START_USING', JText::_('CUSTOM_SITE_NAME'));?>');
			}
		}
	},
	init: function() {
		// pop up  welcome modal
		var container = $('div.welcome-message');
		container.modal({ backdrop:'static', show:true, keyboard:false });
		
		$('.progress-button').click(function() {
			var funcToRun = $(this).data('progress');
			
			if (funcToRun.match(/\([0-9]{1}\)$/))
			{
				eval(funcToRun);
			}
			else
			{
				eval(funcToRun+'()');
			}
		});

		// initiate uploader
		gettingStarted.createAvatarUploader();

		gettingStarted.showAvatar();
	},
	createAvatarUploader : function() {
		var uploader = new qq.FileUploader({
			element: document.getElementById('upload-avatar'),
			action: '<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=ajaxUploadAvatar');?>',
			showMessage:function(message) {},
			onSubmit: function(id, fileName) {
				$('.modal-body .qq-upload-list').html('');
			},
			onComplete:function(id, fileName, response){
				$('img.avatar-preview').attr('src', response.preview);
				$('input:radio:checked').removeAttr('checked');
			}
		});
	}
};

var modalSteps = {'first' : <?php echo ($allow_invite == '1') ? 'gettingStarted.firstToSecond' : 'gettingStarted.firstToThird';?>, 
					'second': gettingStarted.secondToThird
				};
				
$(document).ready(function() {
	gettingStarted.init();
});

function showInvitationMsg(msg)
{
	$('.invitation-message').show().html(msg);
}
</script>