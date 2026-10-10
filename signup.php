<?php

$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$success = false;
$name = '';
$city = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $age  = $_POST['age'] ?? '';
    $city = $_POST['city'] ?? '';

    // TODO 1: Validate name and city. Put messages in $errors.
    if (trim($name) === '') {
        $errors['name'] = 'Name is required';
    }
    if (trim($city) === '') {
        $errors['city'] = 'City is required';
    }

    // TODO 2: If $errors is empty, INSERT the user with a prepared statement.
    if (empty($errors)) {
        $statement = $pdo->prepare(
            'INSERT INTO users (name, age, city) VALUES (:name, :age, :city)'
        );
        $statement->execute([
            'name' => $name,
            'age'  => $age === '' ? null : $age,
            'city' => $city,
        ]);
        $success = true;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>

    <!-- TODO 3: Show the errors here. -->
    <?php if (!empty($errors)) : ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error) : ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <p><input name="name" placeholder="Name" value="<?= htmlspecialchars($name) ?>"></p>
        <p><input name="age" placeholder="Age" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>"></p>
        <p><input name="city" placeholder="City" value="<?= htmlspecialchars($city) ?>"></p>
        <button>Sign Up</button>
    </form>

    <!-- TODO 4: Show "Welcome ___ from ___!" after a successful sign up. -->
    <?php if ($success) : ?>
        <p>
            Welcome <?= htmlspecialchars($name) ?>
            from <?= htmlspecialchars($city) ?>!
        </p>
    <?php endif; ?>

    <h2>All Users</h2>
<ul>
    <?php
    $users = $pdo->query('SELECT name, city FROM users ORDER BY id DESC')->fetchAll();
    foreach ($users as $user) : ?>
        <li>
            <?= htmlspecialchars($user['name']) ?>
            from <?= htmlspecialchars($user['city']) ?>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>