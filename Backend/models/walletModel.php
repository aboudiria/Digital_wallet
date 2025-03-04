<?php

class WalletModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getWalletOverview($userId) {
        $overview = [
            'balance' => 0.00,
            'total_deposits' => 0.00,
            'total_withdrawals' => 0.00
        ];
        
        $queryWallet = "SELECT balance FROM wallets WHERE user_id = ?";
        if ($stmt = $this->conn->prepare($queryWallet)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $overview['balance'] = $row['balance'];
            }
            $stmt->close();
        }
        
        $queryDeposits = "SELECT SUM(amount) AS total_deposits FROM transactions WHERE user_id = ? AND type = 'deposit' AND status = 'completed'";
        if ($stmt = $this->conn->prepare($queryDeposits)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $overview['total_deposits'] = $row['total_deposits'] ? $row['total_deposits'] : 0;
            }
            $stmt->close();
        }
        
        $queryWithdrawals = "SELECT SUM(amount) AS total_withdrawals FROM transactions WHERE user_id = ? AND type = 'withdrawal' AND status = 'completed'";
        if ($stmt = $this->conn->prepare($queryWithdrawals)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $overview['total_withdrawals'] = $row['total_withdrawals'] ? $row['total_withdrawals'] : 0;
            }
            $stmt->close();
        }
        
        return $overview;
    }
}
?>
