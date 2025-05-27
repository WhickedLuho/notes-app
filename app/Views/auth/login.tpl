{extends file='layouts/base.tpl'}

{block name='content'}
<div class="login-box">
    <h2>Login</h2>
    
    {if !empty($error) && $error}
        <div class="alert alert-danger">{$error}</div>
    {/if}

    <form method="post" action="/login">
        <input type="hidden" name="csrf_token" value="{$csrf_token}">
        
        <div class="form-group mt-3">
            <label>Email</label>
            <input type="text" name="email" required class="form-control">
        </div>
        
        <div class="form-group mt-3">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>

    </form>
    
    <div class="mt-3">
        <a href="/register">Create an account</a>
    </div>
</div>
{/block}