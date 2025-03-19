<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateArticlesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true
            ],
            'content' => [
                'type' => 'TEXT'
            ],
            'excerpt' => [
                'type' => 'TEXT'
            ],
            'cover_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'references' => 'categories.id',
                'onDelete' => 'SET NULL'
            ],
            'author_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'references' => 'users.id',
                'onDelete' => 'CASCADE'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published'],
                'default' => 'draft'
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('articles');
    }

    public function down()
    {
        $this->forge->dropTable('articles');
    }
} 