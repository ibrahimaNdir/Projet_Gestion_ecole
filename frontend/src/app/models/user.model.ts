export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'enseignant' | 'eleve' | 'parent';
  created_at: string;
  updated_at: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  user: User;
  token: string;
  message: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  role: string;
}