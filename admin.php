<?php
session_start();

$usersFile = 'data/users.json'; // Archivo JSON que almacena usuarios.

// Asegurarse de que el archivo JSON existe.
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode(['users' => []], JSON_PRETTY_PRINT));
}

// Función para leer usuarios.
function readUsers($usersFile)
{
    $usersData = json_decode(file_get_contents($usersFile), true);
    return $usersData['users'] ?? [];
}

// Función para crear un usuario.
function createUser($usersFile, $name, $email, $password, $role)
{
    $usersData = json_decode(file_get_contents($usersFile), true);

    $newUser = [
        'id' => uniqid(),
        'name' => htmlspecialchars($name),
        'email' => filter_var($email, FILTER_VALIDATE_EMAIL),
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role
    ];

    $usersData['users'][] = $newUser;
    file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

    return $newUser;
}

// Función para actualizar un usuario.
function updateUser($usersFile, $id, $name, $email, $role)
{
    $usersData = json_decode(file_get_contents($usersFile), true);

    foreach ($usersData['users'] as &$user) {
        if ($user['id'] === $id) {
            $user['name'] = htmlspecialchars($name);
            $user['email'] = filter_var($email, FILTER_VALIDATE_EMAIL);
            $user['role'] = $role;
            break;
        }
    }

    file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
}

// Función para eliminar un usuario.
function deleteUser($usersFile, $id)
{
    $usersData = json_decode(file_get_contents($usersFile), true);

    $usersData['users'] = array_filter($usersData['users'], fn($user) => $user['id'] !== $id);

    file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));
}

// Validar que solo los usuarios de rol "admin" pueden acceder
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Manejo de funciones Create, Update, Delete).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'create') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        createUser($usersFile, $name, $email, $password, $role);
        header('Location: admin.php');
        exit();
    }

    if ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';

        updateUser($usersFile, $id, $name, $email, $role);
        header('Location: admin.php');
        exit();
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';

        deleteUser($usersFile, $id);
        header('Location: admin.php');
        exit();
    }
}

// Obtener la lista de usuarios para mostrarla en la tabla.
$users = readUsers($usersFile);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel de administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body class="bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center min-h-screen">
    <div class="container mx-auto px-4 py-8 animate-[fadeIn_0.5s_ease-out]">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            </div>
            <div class="grid md:grid-cols-3 gap-6 p-6">
                <div
                    class="md:col-span-2 bg-gray-50 rounded-lg p-6 shadow-md transition transform hover:scale-[1.01] duration-300">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">User Management</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-blue-100 text-gray-700">
                                <tr>
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Name</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">Role</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr class="border-b hover:bg-blue-50 transition duration-200">
                                        <td class="p-3"><?= $user['id'] ?></td>
                                        <td class="p-3"><?= $user['name'] ?></td>
                                        <td class="p-3"><?= $user['email'] ?></td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded-full 
                                            <?= $user['role'] == 'admin'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-green-100 text-green-800' ?> 
                                            text-xs">
                                                <?= $user['role'] ?>
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <div class="flex space-x-2">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button
                                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-300">
                                                        Delete
                                                    </button>
                                                </form>
                                                <button
                                                    onclick="editUser('<?= $user['id'] ?>', '<?= $user['name'] ?>', '<?= $user['email'] ?>', '<?= $user['role'] ?>')"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-300">
                                                    Edit
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-md transform transition hover:scale-[1.02] duration-300">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Add User</h2>
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="action" value="create">

                        <div>
                            <label class="block text-gray-700 mb-2">Name</label>
                            <input type="text" name="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" name="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Password</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Role</label>
                            <select name="role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-blue-500 text-white py-2 rounded-md 
                        hover:from-green-600 hover:to-blue-600 transition duration-300 transform hover:scale-[1.02]">
                            Add User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/app.js"></script>