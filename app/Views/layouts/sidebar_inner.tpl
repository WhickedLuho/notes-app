<a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
    <span class="fs-4">My Notes App</span>
</a>
<hr>
<ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="/dashboard" class="nav-link {if $activePage == 'dashboard'}active{/if}">
            📊 Dashboard
        </a>
    </li>
    <li>
        <a href="/notes" class="nav-link {if $activePage == 'notes'}active{/if}">
            📝 Jegyzeteim
        </a>
    </li>
    <li>
        <a href="/notes/create" class="nav-link {if $activePage == 'note_create'}active{/if}">
            ➕ Új jegyzet
        </a>
    </li>
    <li>
        <a href="/tags" class="nav-link {if $activePage == 'tags'}active{/if}">
            🏷️ Tag-ek
        </a>
    </li>
    <li>
        <a href="/profile" class="nav-link {if $activePage == 'profile'}active{/if}">
            👤 Profilom
        </a>
    </li>
    <li>
        <a href="/logout" class="nav-link text-danger">
            🚪 Kijelentkezés
        </a>
    </li>
</ul>
{if !empty($user)}
    <hr>
    <div class="text-muted small">
        Bejelentkezve: <strong>{$user.nickname|escape}</strong>
    </div>
{/if}
