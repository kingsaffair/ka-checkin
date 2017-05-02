{extends "main.tpl"}
{block "body"}
<h2>{$ticket['hash']}</h2>
<dl class="simple">
	<dt>Full Name</dt>
	<dd>{$ticket['name']}</dd>
	
	<dt>Ticket Type</dt>
	<dd>{$ticket['type']}</dd>
{if isset($ticket['primary'])}

	<dt>Primary Ticket</dt>
	<dd>{$ticket['primary']}</dd>
{/if}
</dl>
{/block}