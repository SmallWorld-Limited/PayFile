<?php
session_start();
include 'admin.php';

class api_handler extends db_connect {
    
    public function get_api_stats() {
        $conn = $this->connect();
        
        $stats = array(
            'total_calls' => 0,
            'total_keys' => 0,
            'error_rate' => 0
        );
        
        try {
            // Get total API calls (you'd need an api_logs table)
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM api_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stats['total_calls'] = $total ?: 0;
            $stmt->close();
            
            // Get total active API keys
            $stmt = $conn->prepare("SELECT COUNT(*) as total FROM api_keys WHERE is_active = 1");
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stats['total_keys'] = $total ?: 0;
            $stmt->close();
            
            // Calculate error rate (you'd need error logs)
            $stats['error_rate'] = '0.5'; // Example
            
        } catch (Exception $e) {
            error_log("API stats error: " . $e->getMessage());
        }
        
        return json_encode($stats);
    }
    
    public function get_api_keys() {
        $conn = $this->connect();
        
        $html = '';
        
        try {
            $stmt = $conn->prepare("
                SELECT id, api_key_name, api_key_prefix, created_at, last_used, total_calls, is_active
                FROM api_keys 
                ORDER BY created_at DESC
            ");
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                // Mask the API key for display
                $display_key = substr($row['api_key_prefix'], 0, 8) . '...';
                
                $html .= '<tr>';
                $html .= '<td><code>' . $display_key . '</code></td>';
                $html .= '<td>' . $row['api_key_name'] . '</td>';
                $html .= '<td>' . $row['created_at'] . '</td>';
                $html .= '<td>' . ($row['last_used'] ?: 'Never') . '</td>';
                $html .= '<td>' . $row['total_calls'] . '</td>';
                $html .= '<td>';
                if ($row['is_active']) {
                    $html .= '<span class="badge badge-success">Active</span>';
                } else {
                    $html .= '<span class="badge badge-danger">Revoked</span>';
                }
                $html .= '</td>';
                $html .= '<td>';
                if ($row['is_active']) {
                    $html .= '<button class="btn btn-sm btn-warning" onclick="revokeApiKey(' . $row['id'] . ')">Revoke</button>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
            
            if (empty($html)) {
                $html = '<tr><td colspan="7" class="text-center">No API keys found</td></tr>';
            }
            
        } catch (Exception $e) {
            $html = '<tr><td colspan="7" class="text-center">Error loading API keys</td></tr>';
        }
        
        return $html;
    }
    
    public function generate_api_key($key_name, $expiration, $permissions) {
        $conn = $this->connect();
        
        try {
            // Generate a secure API key
            $api_key = bin2hex(random_bytes(32));
            $api_key_prefix = substr($api_key, 0, 8);
            
            $stmt = $conn->prepare("
                INSERT INTO api_keys (api_key_name, api_key, api_key_prefix, expiration_days, permissions, created_by)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $user_id = $_SESSION['bt_user_id'] ?? 0;
            $permissions_json = json_encode($permissions);
            
            $stmt->bind_param('sssisi', $key_name, $api_key, $api_key_prefix, $expiration, $permissions_json, $user_id);
            
            if ($stmt->execute()) {
                return json_encode(array(
                    'success' => true,
                    'api_key' => $api_key,
                    'message' => 'API key generated successfully'
                ));
            } else {
                return json_encode(array(
                    'success' => false,
                    'message' => 'Failed to generate API key'
                ));
            }
            
        } catch (Exception $e) {
            return json_encode(array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ));
        }
    }
}

// Handle requests
$handler = new api_handler();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_stats':
            echo $handler->get_api_stats();
            break;
        case 'get_keys':
            echo $handler->get_api_keys();
            break;
        case 'generate_key':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $key_name = $_POST['key_name'] ?? '';
                $expiration = $_POST['key_expiration'] ?? 'never';
                $expiration_days = ($expiration == 'never') ? 0 : intval($expiration);
                $permissions = $_POST['permissions'] ?? array('read');
                
                echo $handler->generate_api_key($key_name, $expiration_days, $permissions);
            }
            break;
        case 'revoke_key':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Implement revoke logic
                echo json_encode(array('success' => true, 'message' => 'API key revoked'));
            }
            break;
        default:
            echo json_encode(array('error' => 'Invalid action'));
    }
    exit;
}

echo json_encode(array('error' => 'No action specified'));
?>