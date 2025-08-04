import { Routes } from '@angular/router';
import { authGuard } from './guards/auth.guard';
import { roleGuard } from './guards/role.guard';

export const routes: Routes = [
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: 'login', loadComponent: () => import('./pages/login/login.component').then(m => m.LoginComponent) },
  {
    path: 'admin',
    canActivate: [authGuard, roleGuard],
    data: { roles: ['admin'] },
    loadComponent: () => import('./pages/admin/admin-dashboard/admin-dashboard.component').then(m => m.AdminDashboardComponent),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      { path: 'dashboard', loadComponent: () => import('./pages/admin/admin-dashboard/admin-dashboard.component').then(m => m.AdminDashboardComponent) },
      { path: 'eleves', loadComponent: () => import('./pages/admin/eleves/eleves.component').then(m => m.ElevesComponent) },
      { path: 'enseignants', loadComponent: () => import('./pages/admin/enseignants/enseignants.component').then(m => m.EnseignantsComponent) },
      { path: 'classes', loadComponent: () => import('./pages/admin/classes/classes.component').then(m => m.ClassesComponent) },
      { path: 'matieres', loadComponent: () => import('./pages/admin/matieres/matieres.component').then(m => m.MatieresComponent) },
      { path: 'bulletins', loadComponent: () => import('./pages/admin/bulletins/bulletins.component').then(m => m.BulletinsComponent) },
      { path: 'parents', loadComponent: () => import('./pages/admin/parents/parents.component').then(m => m.ParentsComponent) }
    ]
  },
  {
    path: 'enseignant',
    canActivate: [authGuard, roleGuard],
    data: { roles: ['enseignant'] },
    loadComponent: () => import('./pages/enseignant/enseignant-dashboard/enseignant-dashboard.component').then(m => m.EnseignantDashboardComponent),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      { path: 'dashboard', loadComponent: () => import('./pages/enseignant/enseignant-dashboard/enseignant-dashboard.component').then(m => m.EnseignantDashboardComponent) },
      { path: 'notes', loadComponent: () => import('./pages/enseignant/notes/notes.component').then(m => m.NotesComponent) },
      { path: 'cours', loadComponent: () => import('./pages/enseignant/cours/cours.component').then(m => m.CoursComponent) }
    ]
  },
  {
    path: 'eleve',
    canActivate: [authGuard, roleGuard],
    data: { roles: ['eleve'] },
    loadComponent: () => import('./pages/eleve/eleve-dashboard/eleve-dashboard.component').then(m => m.EleveDashboardComponent),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      { path: 'dashboard', loadComponent: () => import('./pages/eleve/eleve-dashboard/eleve-dashboard.component').then(m => m.EleveDashboardComponent) },
      { path: 'notes', loadComponent: () => import('./pages/eleve/notes/notes.component').then(m => m.NotesComponent) },
      { path: 'bulletins', loadComponent: () => import('./pages/eleve/bulletins/bulletins.component').then(m => m.BulletinsComponent) }
    ]
  },
  {
    path: 'parent',
    canActivate: [authGuard, roleGuard],
    data: { roles: ['parent'] },
    loadComponent: () => import('./pages/parent/parent-dashboard/parent-dashboard.component').then(m => m.ParentDashboardComponent),
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
      { path: 'dashboard', loadComponent: () => import('./pages/parent/parent-dashboard/parent-dashboard.component').then(m => m.ParentDashboardComponent) },
      { path: 'enfants/:id/notes', loadComponent: () => import('./pages/parent/enfants-notes/enfants-notes.component').then(m => m.EnfantsNotesComponent) },
      { path: 'enfants/:id/bulletins', loadComponent: () => import('./pages/parent/enfants-bulletins/enfants-bulletins.component').then(m => m.EnfantsBulletinsComponent) }
    ]
  },
  { path: '**', redirectTo: '/login' }
];
