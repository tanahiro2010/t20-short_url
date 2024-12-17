<?php
require_once '../db.php';
// CORSヘッダーを設定
header("Access-Control-Allow-Origin: https://t20.jp"); // 許可するオリジンを指定
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS"); // 許可するHTTPメソッド
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // 許可するリクエストヘッダー
header("Access-Control-Max-Age: 86400"); // プリフライトリクエストのキャッシュ時間（秒）

// プリフライトリクエスト（OPTIONS）の対応
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // HTTP 200を返して終了
    exit();
}

header('Content-Type: application/json');

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

// 接続確認
if ($mysqli->connect_error) {
    die("データベースへの接続に失敗しました: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // リンク作成
    if (isset($_POST['url']) && isset($_POST['id']) && isset($_POST['password'])) {
        $url = htmlspecialchars($_POST['url']);
        $id = htmlspecialchars($_POST['id']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // IDにクエリなどが使われていない確認
        if (strpos("&", $id) || strpos("?", $id)) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(array(
                'success' => false,
                'message' => 'The id includes "?" or "&"',
                'code'    => 0
            ), JSON_PRETTY_PRINT);
            exit();
        }

        if (str_replace(" ", "", $id) == "" || str_replace("　", "", $id) == "") {
            echo json_encode(array(
                'success' => false,
                'message' => 'Id is empty.',
                'code'    => 0
            ), JSON_PRETTY_PRINT);
            exit();
        }

        // 既にそのIDが使われていないか確認
        $stmt = $mysqli->prepare('SELECT EXISTS (SELECT 1 FROM short_url WHERE id = ?)');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();

        if ($exists) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array(
                'success' => false,
                'message' => 'The id is already in use',
                'code'    => 4
            ), JSON_PRETTY_PRINT);
            exit();
        }

        // 新しいショートURLを作成
        $stmt = $mysqli->prepare("INSERT INTO short_url (id, url, password) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $id, $url, $password);
            if ($stmt->execute()) {
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Successfully created shortened URL!',
                    'url'     => 'https://t20.jp/' . $id
                ), JSON_PRETTY_PRINT);
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Database error: Could not insert record',
                    'code'    => 1
                ), JSON_PRETTY_PRINT);
            }
            $stmt->close();
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to prepare statement',
                'code'    => 2
            ), JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Missing parameters!',
            'code'    => 4,
            'params'  => $_POST
        ), JSON_PRETTY_PRINT);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? null;
    if ($contentType !== 'application/json') {
        echo json_encode(array(
            'success'            => false,
            'message'            => 'Wrong content type!',
            'allow_content-type' => 'application/json',
            'code'               => 5,
            'content-type'       => $contentType
        ), JSON_PRETTY_PRINT);
        exit();
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id']) && isset($input['password'])) {
        $id = htmlspecialchars($input['id']);
        $password = $input['password'];

        // IDに対応するパスワードを取得
        $stmt = $mysqli->prepare("SELECT password FROM short_url WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->bind_result($hashed_password);

            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    $stmt->close();

                    // URLを削除
                    $stmt = $mysqli->prepare("DELETE FROM short_url WHERE id = ?");
                    if ($stmt) {
                        $stmt->bind_param("s", $id);
                        if ($stmt->execute()) {
                            echo json_encode(array(
                                'success' => true,
                                'message' => 'Successfully deleted shortened URL!',
                                'url'     => 'https://t20.jp/' . $id
                            ), JSON_PRETTY_PRINT);
                        } else {
                            echo json_encode(array(
                                'success' => false,
                                'message' => 'Database error: Could not delete record',
                                'code'    => 6
                            ), JSON_PRETTY_PRINT);
                        }
                    } else {
                        echo json_encode(array(
                            'success' => false,
                            'message' => 'Failed to prepare DELETE statement',
                            'code'    => 7
                        ), JSON_PRETTY_PRINT);
                    }
                } else {
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Wrong password!',
                        'code'    => 2
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Invalid id!',
                    'code'    => 3
                ), JSON_PRETTY_PRINT);
            }
            $stmt->close();
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Failed to prepare SELECT statement',
                'code'    => 8
            ), JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Missing parameters!',
            'code'    => 4,
            'params'  => $input
        ), JSON_PRETTY_PRINT);
    }
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}
