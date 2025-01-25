<?php
header('Content-Type: application/json');

$apiKey = 'your-api-key';
$story = $_POST['story'] ?? '';

$prompt = "请根据以下故事内容生成一个“CCB”格式的三字词语，要求：
1.第一个字的拼音首字母或英文首字母为C（可中文或英文），第二个字的拼音首字母或英文首字母为C（可中文或英文）
2.第三部分拼音或英文单词首字母为B（可中文或英文）
3.组合形式自由（如全中文、全英文或混合）
4.需体现故事核心要素
5.故事：一只鸡被砍头后仍然可以呼吸 → Cut Chicken Breath（砍鸡但是鸡可以呼吸）
6.故事：孩子尝父亲排泄物检查病情 → 尝尝便
7.输出格式：词语单独一行，应给出完整的单词拼写或者汉字，换行后写解释，如果词语中有英文字母需要在解释中给出该字母代表的单词并翻译
8.解释要求：50字以内，简洁明了
故事内容：$story";

$data = [
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ],
    'model' => 'your-model',
    'temperature' => 0.7,
];

$ch = curl_init('your-base-url');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$content = $result['choices'][0]['message']['content'] ?? '';

// 解析生成内容
$parts = explode("\n", trim($content));
$keyword = trim($parts[0]);
$explanation = trim(implode(' ', array_slice($parts, 1)));

echo json_encode([
    'keyword' => htmlspecialchars($keyword),
    'explanation' => htmlspecialchars($explanation)
], JSON_UNESCAPED_UNICODE);
