{extends file='layouts/base.tpl'}

{block name="content"}
<h2 class="mb-4">📝 Jegyzeteim</h2>

{if $notes|@count == 0}
    <p class="text-muted">Nincsenek jegyzeteid.</p>
{else}
    <div class="row g-4">
        {foreach $notes as $note}
            <div class="col-md-4">
                <div class="card h-100 shadow-sm" style="background-color: {$note.color|default:'#ffffff'};">
                    <div class="card-body">
                        <h5 class="card-title">{$note.title}</h5>
                        <p class="card-text">{$note.content|nl2br}</p>
                        <a href="/notes/edit/{$note.id}" class="btn btn-sm btn-outline-dark">✏️ Szerkesztés</a>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
{/if}
{/block}
