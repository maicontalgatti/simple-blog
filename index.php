<?php
session_start();

// Conexão com o banco de dados PostgreSQL
$host = "localhost";
$dbname = "simple_blog";
$user = "postgres";
$password = "root";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Login simples com usuário fixo
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['logged_in'] = true;
    } else {
        $login_error = "Credenciais inválidas!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Adiciona novo post
if (isset($_POST['new_post']) && $_SESSION['logged_in']) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (:title, :content)");
    $stmt->execute([':title' => $title, ':content' => $content]);
    header("Location: index.php");
    exit();
}

// Lista todos os posts
$posts = [];
if ($_SESSION['logged_in']) {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Simple Blog CRUD</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if (!isset($_SESSION['logged_in'])): ?>
        <h2 class="mb-3">Login</h2>
        <form method="POST" action="">
            <?php if (isset($login_error)): ?>
                <div class="alert alert-danger"><?= $login_error ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
    <?php else: ?>
        <h2 class="mb-3">Posts</h2>
        <a href="?logout=1" class="btn btn-secondary mb-3">Logout</a>
        <button class="btn btn-success mb-3" data-toggle="collapse" data-target="#newPostForm">Postar</button>

        <div id="newPostForm" class="collapse mb-3">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="content">Conteúdo</label>
                    <textarea name="content" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" name="new_post" class="btn btn-primary">Publicar</button>
            </form>
        </div>

        <ul class="list-group">
            <?php foreach ($posts as $post): ?>
                <li class="list-group-item">
                    <h5><?= htmlspecialchars($post['title']) ?></h5>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <small class="text-muted">Publicado em: <?= $post['created_at'] ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
