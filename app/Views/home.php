<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
    <h1>Importar CSV</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="color: green;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('home/upload_csv') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label for="csv_file">Escolha o arquivo CSV:</label>
        <input type="file" name="csv_file" id="csv_file" required>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
