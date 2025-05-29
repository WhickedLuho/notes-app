<?php

namespace App\Controllers;

use App\Models\Note;

class DashboardController extends BaseController
{
    public function index()
    {
        $noteModel = new Note($this->db);

        // Last 6 pinned note
        $pinnedNotes = $noteModel->getPinnedByUser($this->user->id, 6);
        // debug($pinnedNotes);
        $totalNotesCount = $noteModel->getAllNoteCount($this->user->id);
        $pinnedNotesCount = $noteModel->getPinnedNoteCount($this->user->id, 10000);
        $archivedNotesCount = $noteModel->getArchivedCount($this->user->id);
        // $tagsCount = $noteModel->getTagCount($this->user->id);

        $stats = [
            'total_notes' => $totalNotesCount,
            'pinned_notes' => $pinnedNotesCount,
            'archived_notes' => $archivedNotesCount,
            'total_tags' => 5,
        ];

        $this->smarty->assign([
            'stats' => $stats,
            'pinnedNotes' => $pinnedNotes,
            'activePage' => 'dashboard'
        ]);
        $this->smarty->display('dashboard/index.tpl');
    }
}
