{extends "main.tpl"}
{block "head"}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">mode = 1; ignore = {if $separate}1{else}0{/if};</script>
	<script type="text/javascript" src="{resource src='scripts/tickets.js'}"></script>
{/block}
{block "body"}
<form method="post" action="{url mode=$hash}" class="tickets">
{foreach $tickets as $ticket}
<div class="ticket{if $ticket['entered'] != 0} complete{elseif $ticket['selected']} selected{/if}{if $ticket['primary']} primary{/if}">
	<input type="hidden" name="t_{$ticket['id']}" value="{if $ticket['selected'] && $ticket['entered'] == 0}1{else}0{/if}" />
{if $ticket['selected']}
	<div class="initial"></div>
{/if}
	<ul class="icons">
{if $ticket['primary']}
	 	<li>Primary</li>
{/if}
{if ($ticket['type'] == 'q')}
		<li>QueueJump</li>
{elseif ($ticket['type'] == 'g')}
		<li>Guest List:{$guest_sections_full[$ticket['gl']]}</li>
{elseif ($ticket['type'] == 'r')}
		<li>Re-entry</li>
{else}
		<li>Normal</li>
{/if}
{if $ticket['committee'] == 3}
		<li>Committee</li>
{elseif $ticket['committee'] == 2}
		<li>Shadow Committee</li>
{elseif $ticket['committee'] == 1}
		<li>Ex-Committee</li>
{/if}
	</ul>
{if $ticket['entered'] != 0}
	<div class="status">Already Entered</div>
{/if}
	<h3>{$ticket['name']}</h3>
	<h4>{$ticket['hash']}</h4>
	<h5>{$ticket['entrance']}</h5>
	<h6>{if $separate}Separated Entry{/if}</h6>
</div>
<span class="error"></span>
{/foreach}
<span id="warning" class="error"></span>
{if !$complete}
	<input type="hidden" name="hash" value="{$hash}" />
	<input type="submit" name="form_submit" value="Check In" class="button" />
{/if}
</form>
{/block}