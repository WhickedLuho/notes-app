<?php
namespace Database\Seeders;

use PDO;
use Faker\Factory;

class NotesTableSeeder
{
    private PDO $db;
    private \Faker\Generator $faker;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create();
    }

    public function run(int $userId, int $count = 50): void
    {
        $colors = ['#FF5733', '#33FF57', '#3357FF', '#F033FF', '#FF33F0', '#33FFF0'];
        $categories = ['Work', 'Personal', 'Ideas', 'Shopping', 'Recipes'];

        $stmt = $this->db->prepare("
            INSERT INTO notes (
                user_id, 
                title, 
                content, 
                color, 
                is_pinned, 
                is_archived, 
                created_at, 
                modified_at
            ) VALUES (
                :user_id, 
                :title, 
                :content, 
                :color, 
                :is_pinned, 
                :is_archived, 
                :created_at, 
                :modified_at
            )
        ");

        for ($i = 0; $i < $count; $i++) {
            $date = $this->faker->dateTimeBetween('-6 months')->format('Y-m-d H:i:s');
            
            $stmt->execute([
                ':user_id' => $userId,
                // ':title' => $this->generateNoteTitle(),
                ':title' => $this->faker->sentence(),
                ':content' => $this->generateNoteContent(),
                ':color' => $colors[array_rand($colors)],
                ':is_pinned' => rand(0,1), // 20% chance
                ':is_archived' => rand(0,1), // 10% chance
                ':created_at' => $date,
                ':modified_at' => $this->faker->dateTimeBetween($date)->format('Y-m-d H:i:s')
            ]);
        }
    }

    private function generateNoteTitle(): string
    {
        $templates = [
            'Meeting with {company}',
            'Ideas for {project}',
            '{day} shopping list',
            'Notes about {topic}',
            'Discussion with {person}'
        ];

        return $this->faker->parse(array_shift($templates));
    }

    private function generateNoteContent(): string
    {
        $content = '';
        $paragraphs = $this->faker->numberBetween(1, 5);
        
        for ($i = 0; $i < $paragraphs; $i++) {
            $content .= '<p>' . $this->faker->paragraph() . '</p>';
            
            if ($this->faker->boolean(30)) {
                $content .= '<ul>';
                $items = $this->faker->numberBetween(3, 8);
                for ($j = 0; $j < $items; $j++) {
                    $content .= '<li>' . $this->faker->sentence() . '</li>';
                }
                $content .= '</ul>';
            }
            
            if ($this->faker->boolean(20)) {
                $content .= '<blockquote>' . $this->faker->sentence() . '</blockquote>';
            }
        }
        
        return $content;
    }
}