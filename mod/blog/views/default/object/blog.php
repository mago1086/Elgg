<?php
/**
 * View for blog objects
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$full = (isset($vars['full'])) ? $vars['full'] : FALSE;
$blog = (isset($vars['entity'])) ? $vars['entity'] : FALSE;

if (!$blog) {
	return '';
}

$owner = get_entity($blog->owner_guid);
$container = get_entity($blog->container_guid);
$linked_title = "<a href=\"{$blog->getURL()}\" title=\"" . htmlentities($blog->title) . "\">{$blog->title}</a>";
$categories = elgg_view('categories/view', $vars);
$excerpt = $blog->excerpt;
$body = $blog->description;
$owner_icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
$tags = elgg_view('output/tags', array('tags' => $blog->tags));
$date = friendly_time($blog->time_created);

if ($blog->comments_on == 'Yes') {
	$comments_count = elgg_count_comments($blog);
	$comments_link = "<a href=\"{$blog->getURL()}#annotations\">" . sprintf(elgg_echo("comments"), $comments_count) . '</a>';
} else {
	$comments_link = '';
}

// links to delete or edit.
if ($blog->canEdit()) {
	$edit_url = "{$vars['url']}pg/blog/{$owner->username}/edit/{$blog->getGUID()}/";
	$edit_link = "<a href=\"$edit_url\">" . elgg_echo('edit') . '</a>';

	$delete_url = "{$vars['url']}action/blog/delete?guid={$blog->getGUID()}";
	$delete_link = "<span class='delete_button'>".elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'confirm' => elgg_echo('deleteconfirm')
	))."</span>";

	$edit = "$edit_link $delete_link";
} else {
	$edit = '';
}

if ($full) {
	// The "on" status changes for comments, so best to check for !Off
	if ($blog->comments_on != 'Off') {
		$comments = elgg_view_comments($blog);
	} else {
		$comments = '';
	}

	$owner_title = sprintf(elgg_echo('blog:owner_title'), $user->name);

echo <<<___END
<div class="blogpost clearfloat">
	<div id="content_header" class="clearfloat">
		<div class="content_header_title"><h2>$owner_title</h2></div>
	</div>	
	<div class="entity_listing_icon">
		$owner_icon
	</div>
	<div class="entity_listing_info">
		<div class="entity_metadata">$edit</div>
		<p class="entity_title">{$blog->title}</p>
		<p class="entity_subtext">
			$date
			$categories
			$comments_link
		</p>
		<p class="tags">$tags</p>
		<span class="body">$body</span>
	</div>
</div>

$comments

___END;

} else {
	echo <<<___END
<div class="blog entity_listing clearfloat">
	<div class="entity_listing_icon">
		$owner_icon
	</div>
	<div class="entity_listing_info">
		<div class="entity_metadata">$edit</div>
		<p class="entity_title">$linked_title</p>
		<p class="entity_subtext">
			$date
			$categories
			$comments_link
		</p>
		<p class="tags">$tags</p>
		<p>$excerpt</p>
	</div>
</div>

___END;
}