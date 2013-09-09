<?php 
  preg_match('/[0-9]+/', JRequest::getVar('category_id'), $category_id);
  $category_id = (isset($category_id[0])) ? $category_id[0] : NULL;
?>
<div class="blue-button">
	<a class="btn btn-info" href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>#newBlog">
		<i class="icon-plus icon-white"></i>New Blog
	</a>
</div>
<ul class="nav nav-pills filter">
	<!-- <input type="text" class="input-medium search-query" style="float: left; width: 82px; margin: 4px 10px 4px 0px;"> -->
	 <li <?php echo ($category_id) ? '': ' class="active"'; ?>><a href="#all"><?php echo JText::_('COM_STREAM_BLOG_LABEL_ALL_CATEGORY'); ?></a></li>
	<?php
	  $cCategory = new StreamCategory();
	  // get blog entries rom database
	  $blogLists = $cCategory->getBlogs();
	  if ($blogLists) {
		  $categories = array();
		  // we want to sort which category contains the most entries 
		  foreach ($blogLists as $key=>$blogs) {
			  $categories[$key]['category'] = $blogs->category;
			  $categories[$key]['id'] = $blogs->id;
			  $categories[$key]['count'] = $cCategory->countMessageByCategoryId($blogs->id);
		  }

		  function sortByMessageCount(&$arr, $col) {
			  $sortCol = array();
			  foreach ($arr as $key=> $row) {
				  $sortCol[$key] = $row[$col];
			  }

			  array_multisort($sortCol, SORT_DESC, $arr);
		  }		  
		  sortByMessageCount($categories, 'count');

	?>
	<?php
	  /* limit for category in the navigation area */
	  $CATEGORY_LIMIT = 3;
	  $CATEGORY_LIMIT--;	/* -1 to for easy referencing to array association */

	  // iterate through categories and to wrap the until $CATEGORY_LIMIT on the navigation
	  // and the rest into a dropdown
	  foreach ($categories as $key=>$value) {
		  /* add styling on active category */
		  $active = ($category_id == $value['id']) ? 'class="active"' : '';
		  /* display within CATEGORY_LIMIT here */
		  if ($key <= $CATEGORY_LIMIT) {
	  ?>
	<li <?php echo $active; ?>>
		<a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog?category_id='.$value['id']); ?>"><?php echo $value['category'];?></a>
	</li>
	<?php }
	}
	  /* there might be situation where the category doesnt have more that $CATEGORY_LIMIT
	   * so dropdown container is NOT NEEDED */
	  /* only show category above the limit inside the slider */
	  if (count($categories) > ($CATEGORY_LIMIT + 1)) {
		  if (count($categories) > ($CATEGORY_LIMIT + 1)) {
			  $inDropdown = array_slice($categories, $CATEGORY_LIMIT + 1);
			  $activeDropdown = false;

			  foreach ($inDropdown as $dropdown) {
				  if ($activeDropdown) break;
				  $activeDropdown = ($category_id == $dropdown['id']) ? 'active' : '';
			  }
		  }
	?>

	<li style="float:right"  class="dropdown <?php echo $activeDropdown; ?>">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('COM_STREAM_BLOG_LABEL_MORE_CATEGORY'); ?><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<?php 
			  /* display above limit in a dropdown */
			  foreach ($categories as $key=>$value) {
		  if ($key > $CATEGORY_LIMIT) {
			?>
			<li>
				 <a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog?category_id='.$value['id']); ?>"><?php echo $value['category'];?></a>
			</li>
			<?php 
			  }
			  }
			} 
			} ?>
		</ul>
	</li>
</ul>

    
