<?php

namespace App\Controllers;

use App\Models\Statement;
use App\Models\User;
use App\Models\UserCsvModel;

class Home extends BaseController
{

    // Função para a página home
    public function index()
    {

        $userModel = new User();  // Cria uma instância do UserModel

        $data['users'] = $userModel->getUsers();  // Obtém todos os usuários

        // Carrega a view e passa os dados
        return view('home', $data);
    }

    public function uploadCSV()
    {

        // Validar se o arquivo foi enviado
        if (
            !$this->validate([
                'csv_file' => 'uploaded[csv_file]|max_size[csv_file,10240]|ext_in[csv_file,csv]',
            ])
        ) {
            // Se houver erro de validação, retornar para a página de upload
            return redirect()->back()->withInput()->with('error', 'Erro ao fazer upload do arquivo.');
        }


        // Pegar o arquivo CSV
        $file = $this->request->getFile('csv_file');



        // Abrir e ler o CSV
        if ($file->isValid() && !$file->hasMoved()) {
            $filePath = $file->getRealPath();
            $fileName = $file->getName();
            $data = $this->parseCSV($filePath);


            // Inserir dados na tabela
            $model = new Statement();
            foreach ($data as $row) {
                $model->save($row);
            }

            // Redirecionar com sucesso
            return redirect()->to('/')->with('success', 'Arquivo CSV importado com sucesso!');
        }


        return redirect()->back()->with('error', 'Erro ao processar o arquivo.');
    }

    // Função para processar o CSV
    private function parseCSV($filePath)
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Ler cada linha do CSV
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                // Cada linha será convertida em um array associativo
                $rows[] = [
                    'date' => $data[0],
                    'label' => $data[1],
                    'value' => $data[2],
                ];

            }
            fclose($handle);
        }

        return $rows;
    }

    public function charts()
    {
        $model = new Statement();
        $statements = $model->orderBy('date', 'ASC')->findAll(); // Ordenação por data ASC
        
        $data = [];
        $categories = []; // Para armazenar as categorias de gastos
        $totalEntrada = 0;
        $totalSaida = 0;
    
        foreach ($statements as $statement) {
            $date = $statement['date'];
            $value = floatval(str_replace(',', '.', $statement['value']));
            $label = $statement['label'];
    
            // Organiza por data
            if (!isset($data[$date])) {
                $data[$date] = ['entrada' => 0, 'saida' => 0];
            }
    
            // Organiza as categorias de gastos
            if (!isset($categories[$label])) {
                $categories[$label] = 0;
            }
    
            if ($value >= 0) {
                $data[$date]['entrada'] += $value;
                $totalEntrada += $value;
            } else {
                $data[$date]['saida'] += abs($value);
                $totalSaida += abs($value);
                $categories[$label] += abs($value); // Adiciona a saída na categoria
            }
        }
    
        // Organizando os dados para o gráfico
        $chartData = [
            'labels' => array_keys($data), // Labels com as datas
            'entradas' => array_column($data, 'entrada'), // Valores de entradas
            'saidas' => array_column($data, 'saida'), // Valores de saídas
            'categorias' => $categories, // Categorias de gastos
            'totalEntrada' => $totalEntrada, // Total de entradas
            'totalSaida' => $totalSaida  // Total de saídas
        ];
    
        return view('charts', ['chartData' => $chartData]);
    }
    

}
