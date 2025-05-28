<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $username = 'JohnDoe';
        $stats = [
            'total_notes' => 12,
            'pinned_notes' => 3,
            'archived_notes' => 2,
            'total_tags' => 5,
        ];

        $pinnedNotes = [
            [
                'id' => 10,
                'title' => 'Meeting notes',
                'color' => '#f8f9fa',
                'content' => 'Discussed product roadmap and deadlines.',
            ],
            [
                'id' => 31,
                'title' => 'Weekly ToDos',
                'color' => '#d1e7dd',
                'content' => 'Finish dashboard UI, fix login bug.',
            ]
        ];

        // $user = $_SESSION['user'] ?? null;

        $this->smarty->assign([
            'stats' => $stats,
            'pinnedNotes' => $pinnedNotes,
            'activePage' => 'dashboard'
        ]);
        $this->smarty->display('dashboard/index.tpl');
    }
}
