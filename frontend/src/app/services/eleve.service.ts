import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Eleve, CreateEleveRequest, UpdateEleveRequest } from '../models/eleve.model';

@Injectable({
  providedIn: 'root'
})
export class EleveService {
  constructor(private apiService: ApiService) {}

  getEleves(): Observable<Eleve[]> {
    return this.apiService.get<Eleve[]>('eleves');
  }

  getEleve(id: number): Observable<Eleve> {
    return this.apiService.get<Eleve>(`eleves/${id}`);
  }

  createEleve(eleve: CreateEleveRequest): Observable<Eleve> {
    return this.apiService.post<Eleve>('eleves', eleve);
  }

  updateEleve(id: number, eleve: UpdateEleveRequest): Observable<Eleve> {
    return this.apiService.put<Eleve>(`eleves/${id}`, eleve);
  }

  deleteEleve(id: number): Observable<any> {
    return this.apiService.delete<any>(`eleves/${id}`);
  }

  getEleveCount(): Observable<{ count: number }> {
    return this.apiService.get<{ count: number }>('eleves/count');
  }

  uploadDocument(eleveId: number, file: File): Observable<any> {
    const formData = new FormData();
    formData.append('document', file);

    return this.apiService.post<any>(`documents/${eleveId}`, formData);
  }
}