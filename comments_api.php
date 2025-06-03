<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();

try {
    $connection = getConnection();
    
    switch($method) {
        case 'GET':
            // Get comments for a specific section
            if (isset($_GET['section'])) {
                $section = $connection->real_escape_string($_GET['section']);
                $sql = "SELECT id, name, comment, created_at FROM comments WHERE section = '$section' ORDER BY created_at DESC";
                $result = $connection->query($sql);
                
                $comments = array();
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $comments[] = array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'comment' => $row['comment'],
                            'timestamp' => date('m/d/Y \a\t g:i:s A', strtotime($row['created_at']))
                        );
                    }
                }
                
                $response = array('success' => true, 'comments' => $comments);
            } else {
                $response = array('success' => false, 'message' => 'Section parameter required');
            }
            break;
            
        case 'POST':
            // Add new comment
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (isset($input['section']) && isset($input['name']) && isset($input['comment'])) {
                $section = $connection->real_escape_string($input['section']);
                $name = $connection->real_escape_string($input['name']);
                $comment = $connection->real_escape_string($input['comment']);
                
                // Validate input
                if (empty(trim($name)) || empty(trim($comment))) {
                    $response = array('success' => false, 'message' => 'Name and comment cannot be empty');
                } else {
                    $sql = "INSERT INTO comments (section, name, comment) VALUES ('$section', '$name', '$comment')";
                    
                    if ($connection->query($sql) === TRUE) {
                        $insertId = $connection->insert_id;
                        
                        // Get the inserted comment
                        $getComment = "SELECT id, name, comment, created_at FROM comments WHERE id = $insertId";
                        $result = $connection->query($getComment);
                        $newComment = $result->fetch_assoc();
                        
                        $response = array(
                            'success' => true, 
                            'message' => 'Comment added successfully',
                            'comment' => array(
                                'id' => $newComment['id'],
                                'name' => $newComment['name'],
                                'comment' => $newComment['comment'],
                                'timestamp' => date('m/d/Y \a\t g:i:s A', strtotime($newComment['created_at']))
                            )
                        );
                    } else {
                        $response = array('success' => false, 'message' => 'Error adding comment: ' . $connection->error);
                    }
                }
            } else {
                $response = array('success' => false, 'message' => 'Missing required fields');
            }
            break;
            
        case 'DELETE':
            // Delete comment
            if (isset($_GET['id'])) {
                $commentId = (int)$_GET['id'];
                $sql = "DELETE FROM comments WHERE id = $commentId";
                
                if ($connection->query($sql) === TRUE) {
                    if ($connection->affected_rows > 0) {
                        $response = array('success' => true, 'message' => 'Comment deleted successfully');
                    } else {
                        $response = array('success' => false, 'message' => 'Comment not found');
                    }
                } else {
                    $response = array('success' => false, 'message' => 'Error deleting comment: ' . $connection->error);
                }
            } else {
                $response = array('success' => false, 'message' => 'Comment ID required');
            }
            break;
            
        default:
            $response = array('success' => false, 'message' => 'Method not allowed');
            break;
    }
    
    $connection->close();
    
} catch (Exception $e) {
    $response = array('success' => false, 'message' => 'Database error: ' . $e->getMessage());
}

echo json_encode($response);
?>