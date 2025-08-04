<?php

function validarDescricao($descricao): bool|int {
    return preg_match('/^[a-zA-Z0-9çáéíóúãõâêîôû\s\/:.,!?-]+$/i', $descricao);
}

function carregarTarefas() {
    if (!file_exists('tarefas.txt')) return [];

    $linhas = file('tarefas.txt');
    $tarefas = [];

    foreach ($linhas as $linha) {
        [$descricao, $status] = explode('|', trim($linha));
        $tarefas[] = [
            'descricao' => $descricao,
            'status' => $status
        ];
    }
    return $tarefas;
}

function filtrarTarefas(array $tarefas, string $filtro): array {
    if ($filtro === 'pendentes') {
        return array_filter($tarefas, fn($t) => $t['status'] === 'pendente');
    } elseif ($filtro === 'concluidas') {
        return array_filter($tarefas, fn($t) => $t['status'] === 'concluida');
    }
    return $tarefas;
}

function buscaStatus(string $status): string {
    if ($status === 'concluida') {
        return '<span class="status_verde">Concluída</span>';
    } else {
        return '<span class="status_amarelo">Pendente</span>';
    }
}

function salvarTarefas($tarefas) {
    $linhas = array_map(fn($t) => "{$t['descricao']}|{$t['status']}", $tarefas);
    file_put_contents('tarefas.txt', implode("\n", $linhas));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarefas = carregarTarefas();
    $acao = $_POST['acao'];

    if ($acao === 'adicionar') {
        $desc = trim($_POST['tarefa']);
        if (validarDescricao($desc)) {
            $tarefas[] = ['descricao' => $desc, 'status' => 'pendente'];
        }
    } elseif ($acao === 'excluir') {
        $id = $_POST['id'];
        unset($tarefas[$id]);
    } elseif ($acao === 'concluir') {
        $id = $_POST['id'];
        $tarefas[$id]['status'] = 'concluida';
    }

    salvarTarefas($tarefas);
    header('Location: index.php');
    exit;
}