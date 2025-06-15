<?php
require_once '../src/config.php';
require_once '../src/db.php';
require_once '../src/classes/Event.php';
require_once '../src/classes/User.php';
require_once '../src/classes/Comment.php';

use classes\Event;
use classes\User;
use classes\Comment;

session_start();

$eventsCount = Event::count($pdo);
$usersCount = User::count($pdo);
$commentsCount = Comment::count($pdo);

include 'assets/header.php';
?>

    <h2 class="page-title" style="text-align: center; margin-bottom: 30px;">Statystyki systemu</h2>

    <main class="container" style="max-width: 800px; margin: 0 auto;">
        <section style="
        display: flex;
        justify-content: space-around;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        gap: 20px;
        flex-wrap: wrap;
    ">
            <div style="
            flex: 1 1 200px;
            background: #4a90e2;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        ">
                <h3 style="font-size: 1.2rem; margin-bottom: 10px;">Wydarzenia</h3>
                <p style="font-size: 3rem; font-weight: bold; margin: 0;"><?= $eventsCount ?></p>
            </div>

            <div style="
            flex: 1 1 200px;
            background: #7ed6df;
            color: #222;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        ">
                <h3 style="font-size: 1.2rem; margin-bottom: 10px;">Użytkownicy</h3>
                <p style="font-size: 3rem; font-weight: bold; margin: 0;"><?= $usersCount ?></p>
            </div>

            <div style="
            flex: 1 1 200px;
            background: #f6b93b;
            color: #222;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        ">
                <h3 style="font-size: 1.2rem; margin-bottom: 10px;">Komentarze</h3>
                <p style="font-size: 3rem; font-weight: bold; margin: 0;"><?= $commentsCount ?></p>
            </div>
        </section>
    </main>

<?php include 'assets/footer.php'; ?>