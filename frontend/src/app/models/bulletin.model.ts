import { Eleve } from './eleve.model';
import { AnneeAcademique, PeriodeEvaluation, Note } from './notes.model';

export interface Bulletin {
  id: number;
  eleve_id: number;
  annee_academique_id: number;
  periode_evaluation_id: number;
  moyenne_generale: number;
  rang: number;
  appreciation_generale?: string;
  date_generation: string;
  created_at: string;
  updated_at: string;
  eleve?: Eleve;
  annee_academique?: AnneeAcademique;
  periode_evaluation?: PeriodeEvaluation;
  notes?: Note[];
}

export interface CreateBulletinRequest {
  eleve_id: number;
  annee_academique_id: number;
  periode_evaluation_id: number;
}

export interface ParentUser {
  id: number;
  nom: string;
  prenom: string;
  date_naissance: string;
  lieu_naissance: string;
  sexe: 'M' | 'F';
  adresse: string;
  telephone: string;
  email: string;
  profession: string;
  statut: 'actif' | 'inactif';
  created_at: string;
  updated_at: string;
  user_id?: number;
  eleves?: Eleve[];
}