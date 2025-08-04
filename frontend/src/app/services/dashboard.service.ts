import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface DashboardStats {
  eleves: number;
  enseignants: number;
  classes: number;
  matieres: number;
}

@Injectable({
  providedIn: 'root'
})
export class DashboardService {
  constructor(private apiService: ApiService) {}

  getStats(): Observable<DashboardStats> {
    return this.apiService.get<DashboardStats>('dashboard/stats');
  }

  getEleveCount(): Observable<{ count: number }> {
    return this.apiService.get<{ count: number }>('eleves/count');
  }

  getEnseignantCount(): Observable<{ count: number }> {
    return this.apiService.get<{ count: number }>('enseignants/count');
  }

  getClasseCount(): Observable<{ count: number }> {
    return this.apiService.get<{ count: number }>('classes/count');
  }

  getMatiereCount(): Observable<{ count: number }> {
    return this.apiService.get<{ count: number }>('matieres/count');
  }
}