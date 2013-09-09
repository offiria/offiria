<style>
	ol.recipient-list {
		padding: 0;
		margin: 0;
	}

	ol.recipient-list li {
		background-color: #F8FAFB;
		border: 1px solid #EAEAEA;
		border-radius: 3px 3px 3px 3px;
		float: left;
		font: 11px "Lucida Grande","Verdana";
		line-height: 18px;
		list-style-type: none;
		margin: 4px 0 4px 4px;
		padding: 0 4px 0 0;
	}

	ol.recipient-list li span {
		padding: 0 4px 0 4px;
	}

	ol.recipient-list li a {
		padding: 0 4px 0 4px;
	}

	.stream-post-details input {
		float: left;
	}
</style>

<div class="modal" id="direct-message-modal" style="display: none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Send a Private Message</h3>
	</div>
	<form id="stream-form">
		<div class="modal-body" style="padding-bottom: 0;">

			<div id="stream-post">
				<div class="stream-post-message">

					<!-- Updates -->
					<div id="stream-post-update" class="stream-post-message-share tab-content" style="display: block;">
						<div class="stream-post-details" style="margin: 0 0 10px 0;">
							<ol class="recipient-list">
								<input placeholder="add recipients..."
									   style="float:left; height: 28px; width: 150px; padding: 0; " type="text"
									   autocomplete="off" class="recipient-input recipient-typeahead" name="recipient"/>
							</ol>
							<div class="clear"></div>
						</div>
						<textarea placeholder="type your message here..." cols="63"
								  style="resize: vertical; padding-top: 0; padding-bottom: 0; height: 74px; overflow: hidden;"
								  class="stream-post" name="message" id="message-box"></textarea>
					</div>

					<!--end stream-post-message-share tab-content-->

					<!-- @mention -->
					<div style="display: none" class="stream-post-suggest"></div>

					<div class="stream-post-message-attach">
						<ul id="direct-attachment-list"></ul>
					</div>

					<div class="stream-post-message-tabs">
						<ul>
							<li class="li-text">Attach:</li>
							<li>
								<div id="direct-file-uploader" style="visibility: hidden; width: 1px; height: 1px">
									<noscript>
										<p>Please enable JavaScript to use file uploader.</p>
										<!-- or put a simple form for upload here -->
									</noscript>
								</div>
							</li>
							<li class="stream-post-link-add"><a href="#" onclick="return S.uploader.selectFile('direct-file-uploader');"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD');?>
							</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<span style="float: left; margin: 5px 0 0 5px;" id="direct-error"></span>
			<input type="hidden" value="" name="group_id">
			<input type="hidden" name="type" value="direct">
			<div class="stream-loading" id="stream-post-loading"></div>
			<a class="btn" data-dismiss="modal">Cancel</a>
			<input type="submit" value="Send" class="btn btn-primary">
		</div>
	</form>
</div>