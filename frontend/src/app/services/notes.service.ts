import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Note, CreateNoteRequest, UpdateNoteRequest } from '../models/notes.model';

@Injectable({
  providedIn: 'root'
})
export class NotesService {
  constructor(private apiService: ApiService) {}

  getNotes(): Observable<Note[]> {
    return this.apiService.get<Note[]>('notes');
  }

  getNote(id: number): Observable<Note> {
    return this.apiService.get<Note>(`notes/${id}`);
  }

  createNote(note: CreateNoteRequest): Observable<Note> {
    return this.apiService.post<Note>('notes', note);
  }

  updateNote(id: number, note: UpdateNoteRequest): Observable<Note> {
    return this.apiService.put<Note>(`notes/${id}`, note);
  }

  deleteNote(id: number): Observable<any> {
    return this.apiService.delete<any>(`notes/${id}`);
  }

  // Teacher specific endpoints
  getNotesByCoursAndPeriode(coursId: number, periodeId: number): Observable<Note[]> {
    return this.apiService.get<Note[]>(`notes/mes-cours/${coursId}/periode/${periodeId}`);
  }

  getNotesByEleveAndCours(eleveId: number, coursId: number): Observable<Note[]> {
    return this.apiService.get<Note[]>(`notes/mes-eleves/${eleveId}/cours/${coursId}`);
  }

  getNotesByEleveAndPeriode(eleveId: number, periodeId: number): Observable<Note[]> {
    return this.apiService.get<Note[]>(`notes/mes-eleves/${eleveId}/periode/${periodeId}`);
  }

  getAverageNoteByEleveAndCours(eleveId: number, coursId: number): Observable<{ average: number }> {
    return this.apiService.get<{ average: number }>(`notes/moyenne-eleve-cours?eleve_id=${eleveId}&cours_id=${coursId}`);
  }

  getAverageNoteByEleveAndPeriode(eleveId: number, periodeId: number): Observable<{ average: number }> {
    return this.apiService.get<{ average: number }>(`notes/moyenne-eleve-periode?eleve_id=${eleveId}&periode_id=${periodeId}`);
  }

  // Student specific endpoints
  getMesNotes(periodeId?: number): Observable<Note[]> {
    const params = periodeId ? `?periode_id=${periodeId}` : '';
    return this.apiService.get<Note[]>(`eleve/mes-notes${params}`);
  }

  // Parent specific endpoints
  getEnfantNotes(eleveId: number, periodeId?: number): Observable<Note[]> {
    const params = periodeId ? `?periode_id=${periodeId}` : '';
    return this.apiService.get<Note[]>(`parent/mes-enfants/${eleveId}/notes${params}`);
  }
}