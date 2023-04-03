<?php
class Login
{
    private $conn; // holds database connection object

    // constructor takes a database connection object as parameter
    public function __construct()
    {
        $this->conn = new mysqli('localhost', 'root', 'password', 'Ouvatech');
        ;

    }

    
    public function login($email, $password, $token)
    {
        if (empty($email) || empty($password) || empty($token) || !hash_equals($_SESSION['token'], $token)) {
            return false;
        }
    
        // sanitize input to prevent SQL injection attacks
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);
    
        // Prepare query to select user by email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        // Get result set
        $result = $stmt->get_result();
    
        // Check if user exists
        $row = $result->fetch_assoc();
    
        if ($result->num_rows == 1 && password_verify($password, $row['password'])) {
            // Check if user is confirmed
            if ($row['confirmed'] == 0) {
                // Redirect user to confirmation page
                header('Location: confirm.php');
                exit();
            } else {
                // Start session and set user information
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_email'] = $row['email'];
    
                // Generate new CSRF token
                $_SESSION['token'] = bin2hex(random_bytes(32));
    
                // Redirect user to index page
                header('Location: index.php');
                exit();
            }
        } else {
            // Invalid email or password
            echo 'Invalid email or password';
        }
    
        // Close statement
        $stmt->close();
        $this->conn->close();
    }
    
}
?>