<?php
require_once 'tarefas.php';  

$tarefas = carregarTarefas();

$filtro = $_GET['filtro'] ?? 'todas';

$tarefas = filtrarTarefas($tarefas, $filtro);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Tarefas</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <h1>Lista de Tarefas</h1>
    <div class="conteudo">

        <form action="tarefas.php" method="POST">
            <input type="text" name="tarefa" placeholder="Digite uma nova tarefa" required autocomplete="off"/>
            <button type="submit" name="acao" value="adicionar">Adicionar</button>
        </form>

        <div style="text-align: center; margin-bottom: 20px;">
            <a href="?filtro=todas">Todas</a> | 
            <a href="?filtro=pendentes">Pendentes</a> |
            <a href="?filtro=concluidas">Concluídas</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tarefa</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php if (empty($tarefas)): ?> 
                    <tr>
                        <td colspan="3" style="text-align:center;">
                            Nenhuma tarefa encontrada.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($tarefas as $id => $t): ?> 
                    <tr>
                        <td class="descricao-tarefa">
                            <?php echo $t['descricao'] ?>
                        </td>
                        <td>
                            <?php echo buscaStatus($t['status']) ?>
                        </td>
                        <td>
                            <form action="tarefas.php" method="POST" class="formulario-botoes">
                                <input type="hidden" name="id" value="<?php echo $id ?>" />

                                <?php if ($t['status'] === 'pendente'): ?>
                                    <button name="acao" value="concluir" class="botao_pequeno_verde" title="Marcar como concluída">✔</button>
                                <?php endif; ?>

                                <button name="acao" value="excluir" class="botao_pequeno_vermelho" title="Excluir tarefa">🗑</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
