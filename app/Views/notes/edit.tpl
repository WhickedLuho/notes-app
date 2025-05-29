{extends file="layouts/base.tpl"}

{block name="content"}
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Jegyzet szerkesztése #{$note.id|default:''}</h2>

            <form method="POST" action="/notes/save/{$note.id|default:0}">
                <div class="mb-3">
                    <label for="title" class="form-label">Cím</label>
                    <input type="text" name="title" id="title" class="form-control" value="{$note.title|escape|default:''}" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Tartalom</label>
                    <textarea name="content" id="content" class="form-control" rows="6" required>{$note.content|strip_tags|escape|default:''}</textarea>
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Háttérszín</label>
                    <input type="color" name="color" id="color" class="form-control form-control-color" value="{$note.color|default:'#ffffff'}" title="Jegyzet színe">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/notes" class="btn btn-secondary">Mégse</a>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>
{/block}
