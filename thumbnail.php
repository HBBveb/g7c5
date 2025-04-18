<?php
// thumbnail.php
$videoFile = $_GET['video'] ?? '';
$thumbDir = __DIR__.'/thumbs';
$ffmpegPath = '/usr/bin/ffmpeg'; // 修改为实际路径

// 验证文件名格式
if (!preg_match('/^[a-zA-Z0-9_\-]+\.mp4$/', $videoFile)) {
    http_response_code(400);
    exit('无效的文件名');
}

// 创建缩略图目录
if (!file_exists($thumbDir)) {
    mkdir($thumbDir, 0755, true);
}

$videoPath = __DIR__."/{$videoFile}";
$thumbPath = "{$thumbDir}/".pathinfo($videoFile, PATHINFO_FILENAME).'.jpg';

// 生成缩略图
if (!file_exists($thumbPath)) {
    $command = "{$ffmpegPath} -i {$videoPath} -ss 00:00:01 -vframes 1 -q:v 2 {$thumbPath}";
    exec($command, $output, $returnCode);
    
    if ($returnCode !== 0 || !file_exists($thumbPath)) {
        header('Content-Type: image/jpeg');
        readfile(__DIR__.'/fallback.jpg');
        exit;
    }
}

// 输出缩略图
header('Content-Type: image/jpeg');
readfile($thumbPath);
