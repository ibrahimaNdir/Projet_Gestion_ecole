import { Classe } from './classe.model';

export interface Matiere {
  id: number;
  nom: string;
  code: string;
  description?: string;
  statut: 'actif' | 'inactif';
  created_at: string;
  updated_at: string;
  enseignants_count?: number;
  classes_count?: number;
}

export interface CreateMatiereRequest {
  nom: string;
  code: string;
  description?: string;
}

export interface UpdateMatiereRequest extends Partial<CreateMatiereRequest> {
  id: number;
}

export interface MatiereCoefClasse {
  id: number;
  matiere_id: number;
  classe_id: number;
  coefficient: number;
  created_at: string;
  updated_at: string;
  matiere?: Matiere;
  classe?: Classe;
}