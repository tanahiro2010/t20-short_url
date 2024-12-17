<?php
require_once '../db.php';

// データベース接続
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
if ($mysqli->connect_error) {
    die("データベース接続エラー: " . $mysqli->connect_error);
}

// リクエストがGETの場合
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // パラメータ 'id' が存在するか確認
    if (isset($_GET['id'])) {
        // 入力を検証（SQLインジェクション対策）
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        // IDが空でないか確認
        if (empty($id)) {
            http_response_code(400); // HTTP 400 Bad Request
            echo json_encode([
                'success' => false,
                'message' => 'IDが空です。'
            ]);
            exit();
        }

        // クエリ準備と実行
        $stmt = $mysqli->prepare("SELECT url FROM short_url WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $stmt->bind_result($url);

            // レコードを取得
            if ($stmt->fetch()) {
                http_response_code(200); // HTTP 200 OK
                header('Location: ' . $url);
            } else {
                http_response_code(404); // HTTP 404 Not Found
                echo json_encode([
                    'success' => false,
                    'message' => '指定されたIDのURLは見つかりませんでした。'
                ]);
            }
            $stmt->close();
        } else {
            http_response_code(500); // HTTP 500 Internal Server Error
            echo json_encode([
                'success' => false,
                'message' => 'クエリ準備エラー: ' . $mysqli->error
            ]);
        }
    } else {
        http_response_code(400); // HTTP 400 Bad Request
        echo json_encode([
            'success' => false,
            'message' => 'IDが指定されていません。'
        ]);
    }
} else {
    http_response_code(405); // HTTP 405 Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => '許可されていないHTTPメソッドです。'
    ]);
}

$mysqli->close();