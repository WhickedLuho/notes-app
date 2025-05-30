<?php
namespace App\Controllers;

use App\Models\Note;

class NoteController extends BaseController
{
    public function list()
    {
        $noteModel = new Note($this->db);
        $notes = $noteModel->getAllByUser($this->user->id);

        $this->smarty->assign([
            'notes' => $notes,
            'activePage' => 'notes',
        ]);
        $this->smarty->display('notes/list.tpl');
    }

    public function edit($params)
    {
        $noteModel = new Note($this->db);
        $note = null;

        if (!empty($params['id'])) {
            $note = $noteModel->getById($params['id'], $this->user->id);
        }

        $this->smarty->assign([
            'note' => $note,
            'activePage' => 'notes',
        ]);
        $this->smarty->display('notes/edit.tpl');
    }

    public function save($note)
    {
        $noteModel = new Note($this->db);
        $postData = $this->request->post();

        $data = [
            'id' => $note['id'] ?? null,
            'user_id' => $this->user->id,
            'title' => $postData['title'],
            'content' => $postData['content'],
            'color' => $postData['color'] ?? '#FFFFFF',
            'is_pinned' => isset($postData['is_pinned']) ? 1 : 0,
            'is_archieved' => isset($postData['is_archieved']) ? 1 : 0
        ];

        $noteModel->saveNote($data);
        header("Location: /notes");
        exit;
    }

    public function delete($params)
    {
        $noteModel = new Note($this->db);
        $noteModel->softDelete($params['id'], $_SESSION['user']['id']);
        header("Location: /notes/list");
        exit;
    }
}
