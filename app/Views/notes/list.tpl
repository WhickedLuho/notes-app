{extends file='layouts/base.tpl'}

{block name="content"}
<h2 class="mb-4">📝 Jegyzeteim</h2>

{if $notes|@count == 0}
    <p class="text-muted">Nincsenek jegyzeteid.</p>
{else}
    <div class="row g-2">
        {foreach $notes as $note}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 shadow-sm" style="background-color: {$note.color|default:'#ffffff'};">
                    <div class="card-body">
                        <h5 class="card-title">{$note.title}</h5>
                        <p class="card-text">
                            {$note.content|strip_tags|escape|truncate:30:"…":true|nl2br}
                        </p>
                        <a href="/notes/edit/{$note.id}" class="btn btn-sm btn-outline-dark">✏️ Szerkesztés</a>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
{/if}
{/block}
