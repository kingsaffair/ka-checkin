<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>{$event_name}{if $page_name != '' } &mdash; {/if}{$page_name}</title>
	<link rel="stylesheet" href="{resource src='css/default.css'}" />
	{block "head"}{/block}
</head>
<body>
<div id="mainContainer">
<h1>{$event_name}</h1>
{if $user->user_level > 0 && !(isset($footer) && !$footer)}
	<a href="{url mode=""}" class="toplink">Main Menu</a>
{/if}
<div id="contentContainer">
{block "body"}{/block}
</div>
<span class="copyright">Copyright &copy; {$event_name}. All rights reserved.</span>
</div>
</body>
</html>