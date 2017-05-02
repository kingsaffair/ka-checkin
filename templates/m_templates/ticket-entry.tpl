{extends "main.tpl"}
{block "head"}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript">mode = 0;ignore = {if $separate}1{else}0{/if};</script>
	<script type="text/javascript" src="{resource src='scripts/m_tickets.js'}"></script>
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
	 	<li>1</li>
{/if}
{if ($ticket['type'] == 'q')}
		<li>QJ</li>
{elseif ($ticket['type'] == 'g')}
		<li>GL:{$guest_sections_m[$ticket['gl']]}</li>
{elseif ($ticket['type'] == 'r')}
		<li>R-E</li>
{else}
		<li>N</li>
{/if}
{if $ticket['committee'] == 3}
		<li>Cmte.</li>
{elseif $ticket['committee'] == 2}
		<li>S. Cmte.</li>
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