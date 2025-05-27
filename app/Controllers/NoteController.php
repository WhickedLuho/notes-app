<?php
namespace App\Controllers;

use App\Models\Note;

class NoteController extends Controller
{
    public function list()
    {
        $noteModel = new Note($this->db);
        $notes = $noteModel->getAllByUser($_SESSION['user']['id']);
        $this->render('notes/list.tpl', ['notes' => $notes]);
    }

    public function edit($params)
    {
        $noteModel = new Note($this->db);
        $note = null;

        if (!empty($params['id'])) {
            $note = $noteModel->getById($params['id'], $_SESSION['user']['id']);
        }

        $this->render('notes/form.tpl', ['note' => $note]);
    }

    public function save($post)
    {
        $noteModel = new Note($this->db);

        $data = [
            'id' => $post['id'] ?? null,
            'user_id' => $_SESSION['user']['id'],
            'title' => $post['title'],
            'content' => $post['content'],
            'color' => $post['color'] ?? '#FFFFFF',
            'is_pinned' => isset($post['is_pinned']) ? 1 : 0,
            'is_archieved' => isset($post['is_archieved']) ? 1 : 0
        ];

        $noteModel->saveNote($data);
        header("Location: /notes/list");
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
