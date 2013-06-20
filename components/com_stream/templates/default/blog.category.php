<style type="text/css" media="all">
#blog-category-container ul {
	border:1px solid #ccc;
	margin:30px 0 10px 0px;
	list-style:none;
	border-radius:4px;
}

#blog-category-container ul li, #blog-category-container ul li:hover {
	padding:8px 0px 8px 8px;
}
#blog-category-container ul li:hover {
	background:#eee;
}
#blog-category-container table tr {
	vertical-align:middle;
}
#blog-category-container ul li span {
	float:right;
	margin-right:8px;
}
</style>
<div class="blue-button"><a class="btn btn-info" href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>#newBlog">+ New Blog</a></div>
<div id="blog-category-container">
	<h3>Blog categories</h3>
	<ul>
		<?php 
		  // grab existing categories and output them
		  foreach ($blogLists as $blog) {
		?>
		<li>
			<?php echo $blog->category; ?>
			<span>
				<a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog&task=category&action=remove&category=' . $category->category);
				?>">remove</a>
			</span>
		</li>
		<?php } ?>
	</ul>
	<form method="post" action="<?php echo JRoute::_('index.php?option=com_stream&view=blog&task=category'); ?>">
	<table>
		<tr>
			<td>Create a new category</td>
			<td><input type="text" id="new-category" name="category" value="" /></td>
			<td><input type="submit" value="Create" /></td>
		</tr>
	</table>
	</form>
</div>							<!--end blog-category-container-->
<script type="text/javascript">
$('#blog-category-container').submit(function() {
	S.enqueueMessage();
	if (!S.validate.element('notEmpty', $('#new-category'), 'reset', 'New category')) {
	   	return false;
	}
});
</script>