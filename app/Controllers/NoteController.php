<?php
namespace App\Controllers;

use App\Models\Note;

class NoteController extends BaseController
{
    public function list()
    {
        $notes = [
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
            [
                'id' => 1,
                'title' => 'Első jegyzet',
                'content' => 'Ez egy példa jegyzet tartalma.',
                'color' => '#f8d7da',
            ],
            [
                'id' => 2,
                'title' => 'Második jegyzet',
                'content' => 'Egy másik jegyzet valami fontos gondolattal.',
                'color' => '#d1ecf1',
            ],
            [
                'id' => 3,
                'title' => 'TODO lista',
                'content' => '✔ Feladat A\n✔ Feladat B\n✖ Feladat C',
                'color' => '#fff3cd',
            ],
        ];

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

        $note = [
            'id' => 2,
            'title' => 'Második jegyzet',
            'content' => 'Egy másik jegyzet valami fontos gondolattal.',
            'color' => '#d1ecf1',
        ];

        // $this->render('notes/edit.tpl', ['note' => $note]);
        $this->smarty->assign([
            'note' => $note,
            'activePage' => 'notes',
        ]);
        $this->smarty->display('notes/edit.tpl');
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
