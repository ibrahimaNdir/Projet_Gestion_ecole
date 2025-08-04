import { Matiere } from './matiere.model';
import { Classe } from './classe.model';

export interface Enseignant {
  id: number;
  nom: string;
  prenom: string;
  date_naissance: string;
  lieu_naissance: string;
  sexe: 'M' | 'F';
  adresse: string;
  telephone: string;
  email: string;
  specialite: string;
  diplome: string;
  date_embauche: string;
  statut: 'actif' | 'inactif';
  photo?: string;
  created_at: string;
  updated_at: string;
  user_id?: number;
  matieres?: Matiere[];
  classes?: Classe[];
}

export interface CreateEnseignantRequest {
  nom: string;
  prenom: string;
  date_naissance: string;
  lieu_naissance: string;
  sexe: 'M' | 'F';
  adresse: string;
  telephone: string;
  email: string;
  specialite: string;
  diplome: string;
  date_embauche: string;
  photo?: File;
}

export interface UpdateEnseignantRequest extends Partial<CreateEnseignantRequest> {
  id: number;
}