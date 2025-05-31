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
        $note = [
            'title' => '',
            'content' => '',
        ];

        if (!empty($params['id'])) {
            $note = $noteModel->getById($params['id'], $this->user->id);
        }

        $this->smarty->assign([
            'note' => $note,
            'activePage' => 'notes',
        ]);
        $this->smarty->display('notes/edit.tpl');
    }

    public function save($params)
    {
        $postData = $this->request->post();
        $data = [
            'id' => $params['id'] ?? null,
            'user_id' => $this->user->id,
            'title' => trim($postData['title'] ?? ''),
            'content' => trim($postData['content'] ?? ''),
            'color' => $postData['color'] ?? '#FFFFFF',
            'is_pinned' => isset($postData['is_pinned']) ? 1 : 0,
            'is_archived' => isset($postData['is_archived']) ? 1 : 0
        ];

        $noteModel = new Note($this->db);
        $noteModel->saveNote($data);

        header("Location: /notes");
        exit;
    }

    public function delete($params)
    {
        $noteModel = new Note($this->db);
        $noteModel->softDelete($params['id'], $_SESSION['user']['id']);
        header("Location: /notes");
        exit;
    }
}
