import { ParentUser } from './bulletin.model';
import { Classe } from './classe.model';

export interface Eleve {
  id: number;
  nom: string;
  prenom: string;
  date_naissance: string;
  lieu_naissance: string;
  sexe: 'M' | 'F';
  adresse: string;
  telephone: string;
  email: string;
  photo?: string;
  statut: 'actif' | 'inactif';
  date_inscription: string;
  created_at: string;
  updated_at: string;
  user_id?: number;
  parents?: ParentUser[];
  classes?: Classe[];
}

export interface CreateEleveRequest {
  nom: string;
  prenom: string;
  date_naissance: string;
  lieu_naissance: string;
  sexe: 'M' | 'F';
  adresse: string;
  telephone: string;
  email: string;
  photo?: File;
}

export interface UpdateEleveRequest extends Partial<CreateEleveRequest> {
  statut?: 'actif' | 'inactif';
}