<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<title>{$event_name}{if $page_name != '' } &mdash; {/if}{$page_name}</title>
	<meta content='True' name='HandheldFriendly' />
	<meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="{resource src='css/m_default.css'}" />
	<script type="text/javascript" src="{resource src='scripts/m_screen.js'}"></script>
	{block "head"}{/block}
</head>
<body>
<div id="headerContainer">
	<h1>{$event_name}</h1>
{if $user->user_level > 0 && !(isset($footer) && !$footer)}
	<span class="menu"><a href="{url mode=""}">Menu</a></span>
{/if}
</div>
<div id="contentContainer">
{block "body"}{/block}
</div>
</body>
</html>