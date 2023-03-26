<?php
    final class User {
        private string $email;
        private string $password;
        private DateTime $created_at;
        private DateTime $updated_at;
        public function __construct(string $email, string $password, DateTime $created_at, DateTime $updated_at) {
            $this->email = $email;
            $this->password = $password;
            $this->created_at = $created_at;
            $this->updated_at = $updated_at;
        }
        public function __construct2(string $email, string $password) {
            $this->email = $email;
            $this->password = $password;
            //$this->created_at = $created_at;
        }
        public function getName(): string {
            return $this->email;
        }
        public function getPassword(): string {
            return $this->password;
        }
    }
