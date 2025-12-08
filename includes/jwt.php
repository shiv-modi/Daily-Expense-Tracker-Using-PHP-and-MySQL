<?php
if (defined('JWT_PHP_INCLUDED')) {
    return;
}
define('JWT_PHP_INCLUDED', true);

class JWT {
    private static $secret_key = null;
    private static $expiry_days = 90;
    
    private static function getSecretKey() {
        if (self::$secret_key === null) {
            self::$secret_key = getenv('SESSION_SECRET') ?: 'expenditure_jwt_secret_key_2024';
        }
        return self::$secret_key;
    }
    
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
    
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        
        $payload['iat'] = time();
        $payload['exp'] = time() + (self::$expiry_days * 24 * 60 * 60);
        
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecretKey(), true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    
    public static function decode($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return null;
        }
        
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
        
        $signature = self::base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::getSecretKey(), true);
        
        if (!hash_equals($signature, $expectedSignature)) {
            return null;
        }
        
        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);
        
        if ($payload['exp'] < time()) {
            return null;
        }
        
        return $payload;
    }
    
    public static function getTokenFromHeader() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    public static function validateRequest() {
        $token = self::getTokenFromHeader();
        
        if (!$token) {
            return null;
        }
        
        return self::decode($token);
    }
    
    public static function getUserId() {
        $payload = self::validateRequest();
        
        if ($payload && isset($payload['user_id'])) {
            return $payload['user_id'];
        }
        
        return null;
    }
}

function requireAuth() {
    $userId = JWT::getUserId();
    
    if (!$userId) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized - Invalid or expired token']);
        exit;
    }
    
    return $userId;
}
?>
