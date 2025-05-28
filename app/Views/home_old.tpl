{extends file='layouts/base.tpl'}

{block name='title'}Homepage{/block}

{block name='head_css'}
    <!-- Page-specific CSS -->
    <link href="/assets/css/home.css" rel="stylesheet">
{/block}

{block name='content'}
    <h1>Welcome to {$app_name}</h1>
    
    {if $notes}
        <div class="notes-list">
            {foreach $notes as $note}
                <div class="note">
                    <h3>{$note.title|escape}</h3>
                    <p>{$note.content|escape|nl2br}</p>
                </div>
            {/foreach}
        </div>
    {else}
        <p class="alert alert-info">No notes found</p>
    {/if}
{/block}

{block name='footer_js'}
    <!-- Page-specific JS -->
    <script src="/assets/js/home.js"></script>
    {$smarty.block.parent} <!-- Includes parent footer JS too -->
{/block}