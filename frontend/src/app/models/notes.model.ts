import { Eleve } from './eleve.model';
import { Enseignant } from './enseignant.model';
import { Matiere } from './matiere.model';
import { Classe } from './classe.model';

export interface Note {
  id: number;
  eleve_id: number;
  cours_id: number;
  periode_evaluation_id: number;
  note: number;
  appreciation?: string;
  created_at: string;
  updated_at: string;
  eleve?: Eleve;
  cours?: Cours;
  periode_evaluation?: PeriodeEvaluation;
}

export interface CreateNoteRequest {
  eleve_id: number;
  cours_id: number;
  periode_evaluation_id: number;
  note: number;
  appreciation?: string;
}

export interface UpdateNoteRequest extends Partial<CreateNoteRequest> {
  id: number;
}

export interface Cours {
  id: number;
  enseignant_id: number;
  matiere_id: number;
  classe_id: number;
  annee_academique_id: number;
  created_at: string;
  updated_at: string;
  enseignant?: Enseignant;
  matiere?: Matiere;
  classe?: Classe;
  annee_academique?: AnneeAcademique;
}

export interface PeriodeEvaluation {
  id: number;
  nom: string;
  date_debut: string;
  date_fin: string;
  annee_academique_id: number;
  statut: 'actif' | 'inactif';
  created_at: string;
  updated_at: string;
  annee_academique?: AnneeAcademique;
}

export interface AnneeAcademique {
  id: number;
  nom: string;
  date_debut: string;
  date_fin: string;
  statut: 'actif' | 'inactif';
  created_at: string;
  updated_at: string;
}