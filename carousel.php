<?php
$imagens_json = file_get_contents('imagens.json');

if ($imagens_json === false) {
    echo "Erro: arquivo imagens.json não encontrado.";
    $imagens = null;
} else {
    $imagens = json_decode($imagens_json, true);
    if ($imagens === null && $imagens_json !== 'null') {
        echo "Erro: conteúdo do arquivo imagens.json inválido.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrossel Estático</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="carousel">
        <?php
        if (is_array($imagens)) {
            $firstItem = true;
            foreach ($imagens as $imagem) {
                echo '<div class="banner-image" style="display: '. ($firstItem ? 'block' : 'none') .'">';
                echo '<img src="' . $imagem['imagem_path'] . '" alt="' . $imagem['alt_text'] . '">';
                echo '</div>';
                $firstItem = false;
            }
        } else {
            echo "Erro ao carregar imagens.";
        }
        ?>
    </div>

    <script src="js/script.js"></script>
</body>
</html>