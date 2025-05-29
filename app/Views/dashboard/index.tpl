{extends file='layouts/base.tpl'}

{block name='title'}Dashboard{/block}

{* {block name='head_css'}
<link href="/assets/css/dashboard.css" rel="stylesheet">
{/block} *}

{block name='content'}
<div class="row my-4">
    <div class="col-md-12">
        <h2>Welcome, {$user.nickname}!</h2>
        <p class="text-muted">Here's a quick overview of your activity.</p>
    </div>
</div>

<div class="row text-center">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Notes</h5>
                <p class="display-6">{$stats.total_notes}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3  col-md-6 col-sm-6">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Pinned</h5>
                <p class="display-6">{$stats.pinned_notes}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3  col-md-6 col-sm-6">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Archived</h5>
                <p class="display-6">{$stats.archived_notes}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3  col-md-6 col-sm-6">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Tags</h5>
                <p class="display-6">{$stats.total_tags}</p>
            </div>
        </div>
    </div>
</div>

<hr class="my-5">

<div class="row">
    <div class="col-md-6">
        <h4>Quick Create Note</h4>
        <form method="POST" action="/notes/save">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Note title" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="4" placeholder="Write something..." required></textarea>
            </div>
            <div class="mb-3">
                <input type="color" name="color" value="#ffffff" class="form-control form-control-color" title="Choose note color">
            </div>
            <button type="submit" class="btn btn-primary">Save Note</button>
        </form>
    </div>

    <div class="col-md-6">
        <h4>Pinned Notes</h4>
        {if $pinnedNotes|@count > 0}
            <div class="row g-2">
            {foreach from=$pinnedNotes item=note}
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card h-100 shadow-sm" style="background-color: {$note.color|escape};">
                        <div class="card-body">
                            <h5 class="card-title">{$note.title|escape|truncate:20:"…":true}</h5>
                            <p class="card-text">
                                {$note.content|strip_tags|escape|truncate:20:"…":true|nl2br}
                            </p>
                            <a href="/notes/edit/{$note.id}" class="btn btn-sm btn-outline-dark">Edit</a>
                        </div>
                    </div>
                </div>
            {/foreach}
            </div>
        {else}
            <p class="text-muted">No pinned notes.</p>
        {/if}
    </div>
</div>
{/block}
