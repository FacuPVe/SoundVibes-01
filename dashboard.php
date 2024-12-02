<?php
session_start();


// Validar que solo los usuarios de rol "user" pueden acceder
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>SoundVibes - User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="container mt-5">
        <h1 class="text-3xl font-bold">BIENVENIDO, <?php echo $_SESSION['username']; ?></h1>
        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
            <p class="text-lg text-gray-700 italic">¡Esta sitio de nuestra plataforma todavía está en proceso de
                desarrollo!</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div
                class="bg-blue-100 p-4 rounded-lg hover:bg-blue-200 transition duration-300 cursor-not-allowed opacity-50">
                <h3 class="font-semibold text-blue-800">Próximamente</h3>
                <p class="text-blue-600">Estado de ánimo</p>
            </div>
            <div
                class="bg-green-100 p-4 rounded-lg hover:bg-green-200 transition duration-300 cursor-not-allowed opacity-50">
                <h3 class="font-semibold text-green-800">Próximamente</h3>
                <p class="text-green-600">Jukebox</p>
            </div>
        </div>

        <div class="text-sm text-gray-500 mt-4">
            © 2024 SoundVibes. Todos los derechos reservados.
        </div>
    </div>
</body>

</html>