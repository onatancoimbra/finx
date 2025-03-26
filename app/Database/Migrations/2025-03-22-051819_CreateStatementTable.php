<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatementTable extends Migration
{
    public function up()
    {
        // Criação da tabela users_csv
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'date' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'value' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true); // Define a chave primária
        $this->forge->createTable('statements'); // Cria a tabela
    }

    public function down()
    {
        // Remove a tabela caso precise fazer rollback
        $this->forge->dropTable('statements');
    }
}
