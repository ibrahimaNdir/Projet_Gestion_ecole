export interface Classe {
  id: number;
  nom: string;
  niveau: string;
  capacite: number;
  description?: string;
  statut: 'actif' | 'inactif';
  created_at: string;
  updated_at: string;
  eleves_count?: number;
  enseignants_count?: number;
}

export interface CreateClasseRequest {
  nom: string;
  niveau: string;
  capacite: number;
  description?: string;
}

export interface UpdateClasseRequest extends Partial<CreateClasseRequest> {
  id: number;
}