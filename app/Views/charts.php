<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficos Financeiros</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Gráficos Financeiros</h1>
    
    <!-- Gráfico de Fluxo de Caixa: Entrada vs Saída -->
    <canvas id="fluxoDeCaixa" width="400" height="200"></canvas>
    <script>
        var ctx1 = document.getElementById('fluxoDeCaixa').getContext('2d');
        var fluxoDeCaixaChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartData['labels']) ?>,
                datasets: [{
                    label: 'Entradas',
                    data: <?= json_encode($chartData['entradas']) ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }, {
                    label: 'Saídas',
                    data: <?= json_encode($chartData['saidas']) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                }]
            }
        });
    </script>

    <!-- Gráfico de Total de Entradas vs Saídas -->
    <canvas id="totalEntradasSaidas" width="400" height="200"></canvas>
    <script>
        var ctx2 = document.getElementById('totalEntradasSaidas').getContext('2d');
        var totalEntradasSaidasChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Entradas', 'Saídas'],
                datasets: [{
                    label: 'Total de Entradas e Saídas',
                    data: [<?= $chartData['totalEntrada'] ?>, <?= $chartData['totalSaida'] ?>],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            }
        });
    </script>

    <!-- Gráfico de Distribuição de Gastos por Categoria -->
    <canvas id="distribuicaoCategorias" width="400" height="200"></canvas>
    <script>
        var ctx3 = document.getElementById('distribuicaoCategorias').getContext('2d');
        var distribuicaoCategoriasChart = new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_keys($chartData['categorias'])) ?>,
                datasets: [{
                    label: 'Distribuição de Gastos',
                    data: <?= json_encode(array_values($chartData['categorias'])) ?>,
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                    borderWidth: 1
                }]
            }
        });
    </script>

    <!-- Gráfico de Evolução do Saldo Diário -->
    <canvas id="evolucaoSaldo" width="400" height="200"></canvas>
    <script>
        var ctx4 = document.getElementById('evolucaoSaldo').getContext('2d');
        var saldo = [];
        var acumulado = 0;
        var labels = <?= json_encode($chartData['labels']) ?>;
        var entradas = <?= json_encode($chartData['entradas']) ?>;
        var saidas = <?= json_encode($chartData['saidas']) ?>;

        for (let i = 0; i < entradas.length; i++) {
            acumulado += entradas[i] - saidas[i];
            saldo.push(acumulado);
        }

        var evolucaoSaldoChart = new Chart(ctx4, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Saldo Diário',
                    data: saldo,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false
                }]
            }
        });
    </script>

    <!-- Gráfico de Comparação de Entradas e Saídas por Mês -->
    <canvas id="entradasSaidasMes" width="400" height="200"></canvas>
    <script>
        var ctx5 = document.getElementById('entradasSaidasMes').getContext('2d');
        var entradasMensal = [];
        var saidasMensal = [];
        var meses = [];

        // Agrupando os dados por mês
        for (let i = 0; i < labels.length; i++) {
            let mes = labels[i].substring(3, 5); // Pegando o mês (assumindo formato DD/MM/YYYY)
            if (!meses.includes(mes)) {
                meses.push(mes);
                entradasMensal.push(entradas[i]);
                saidasMensal.push(saidas[i]);
            } else {
                let idx = meses.indexOf(mes);
                entradasMensal[idx] += entradas[i];
                saidasMensal[idx] += saidas[i];
            }
        }

        var entradasSaidasMesChart = new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Entradas',
                    data: entradasMensal,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Saídas',
                    data: saidasMensal,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>

    <!-- Gráfico de Top 5 Categorias com Maior Gasto -->
    <canvas id="topCategorias" width="400" height="200"></canvas>
    <script>
        var ctx6 = document.getElementById('topCategorias').getContext('2d');
        var categorias = Object.entries(<?= json_encode($chartData['categorias']) ?>)
            .sort((a, b) => b[1] - a[1]) // Ordenando as categorias por valor
            .slice(0, 5); // Pegando as 5 maiores
        var topCategorias = categorias.map(item => item[0]);
        var topValores = categorias.map(item => item[1]);

        var topCategoriasChart = new Chart(ctx6, {
            type: 'bar',
            data: {
                labels: topCategorias,
                datasets: [{
                    label: 'Top 5 Categorias de Gastos',
                    data: topValores,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>

</body>
</html>
