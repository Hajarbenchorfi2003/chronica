<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupTables extends Migration
{
    public function up()
    {
        // Supprimer toutes les tables dans l'ordre inverse des dépendances
        $this->forge->dropTable('comments', true);
        $this->forge->dropTable('articles_tags', true);
        $this->forge->dropTable('tags', true);
        $this->forge->dropTable('articles', true);
        $this->forge->dropTable('categories', true);
        $this->forge->dropTable('users', true);
    }

    public function down()
    {
        // Ne rien faire dans down() car c'est une migration de nettoyage
    }
} 