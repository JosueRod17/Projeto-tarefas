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
                            <?php if (isset($_GET['editar']) && $_GET['editar'] == $id): ?>
                                <form action="tarefas.php" method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                    <input type="text" name="nova_descricao" value="<?php echo htmlspecialchars($t['descricao']) ?>" required>
                                    <button type="submit" name="acao" value="editar" title="Salvar edição">💾</button>
                                    <a href="index.php" title="Cancelar edição">❌</a>
                                </form>
                            <?php else: ?>
                                <?php echo htmlspecialchars($t['descricao']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo buscaStatus($t['status']); ?>
                        </td>
                        <td class="acoes-tarefa">
                            <form action="tarefas.php" method="POST" class="formulario-botoes">
                                <input type="hidden" name="id" value="<?php echo $id ?>" />
                                
                                <?php if ($t['status'] === 'pendente'): ?>
                                    <button name="acao" value="concluir" class="botao_acao botao_verde" title="Concluir">✔</button>
                                <?php endif; ?>

                                <button name="acao" value="excluir" class="botao_acao botao_vermelho" title="Excluir">🗑</button>
                            </form>
                            <a href="?editar=<?php echo $id ?>" class="botao_acao botao_amarelo" title="Editar">✏️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
