<!DOCTYPE html>
<html lang="en">
<head>
    {include file='layouts/header.tpl'}
</head>
<body>
    {if !empty($user)}
        {include file='layouts/navbar.tpl'}
    {/if}

    <main class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-10 offset-md-2">
                {block name='content'}{/block}
            </div>
        </div>
    </main>

    {include file='layouts/footer.tpl'}
</body>
</html>