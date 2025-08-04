import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterOutlet } from '@angular/router';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatListModule } from '@angular/material/list';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatCardModule } from '@angular/material/card';
import { MatMenuModule } from '@angular/material/menu';
import { MatBadgeModule } from '@angular/material/badge';
import { MatDividerModule } from '@angular/material/divider';
import { MatChipsModule } from '@angular/material/chips';
import { MatProgressBarModule } from '@angular/material/progress-bar';
import { AuthService } from '../../../services/auth.service';
import { DashboardService } from '../../../services/dashboard.service';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    MatSidenavModule,
    MatToolbarModule,
    MatListModule,
    MatIconModule,
    MatButtonModule,
    MatCardModule,
    MatMenuModule,
    MatBadgeModule,
    MatDividerModule,
    MatChipsModule,
    MatProgressBarModule
  ],
  template: `
    <mat-sidenav-container class="sidenav-container">
      <!-- Sidebar -->
      <mat-sidenav #drawer class="sidenav" fixedInViewport
          [attr.role]="'navigation'"
          [mode]="'side'"
          [opened]="true">

        <div class="sidebar-header">
          <div class="logo-container">
            <mat-icon class="logo-icon">school</mat-icon>
            <span class="logo-text">Gestion Scolaire</span>
          </div>
        </div>

        <mat-nav-list class="sidebar-nav">
          <a mat-list-item routerLink="/admin/dashboard" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>dashboard</mat-icon>
            <span matListItemTitle>Tableau de bord</span>
          </a>

          <a mat-list-item routerLink="/admin/eleves" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>people</mat-icon>
            <span matListItemTitle>Élèves</span>
            <mat-chip matListItemMeta color="primary" class="count-chip">{{stats.eleves}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/admin/enseignants" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>person</mat-icon>
            <span matListItemTitle>Enseignants</span>
            <mat-chip matListItemMeta color="accent" class="count-chip">{{stats.enseignants}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/admin/classes" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>class</mat-icon>
            <span matListItemTitle>Classes</span>
            <mat-chip matListItemMeta color="warn" class="count-chip">{{stats.classes}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/admin/matieres" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>book</mat-icon>
            <span matListItemTitle>Matières</span>
            <mat-chip matListItemMeta color="primary" class="count-chip">{{stats.matieres}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/admin/bulletins" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>assessment</mat-icon>
            <span matListItemTitle>Bulletins</span>
          </a>

          <a mat-list-item routerLink="/admin/parents" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>family_restroom</mat-icon>
            <span matListItemTitle>Parents</span>
          </a>
        </mat-nav-list>

        <mat-divider></mat-divider>

        <div class="sidebar-footer">
          <div class="user-info">
            <mat-icon class="user-avatar">account_circle</mat-icon>
            <div class="user-details">
              <div class="user-name">{{currentUser?.name}}</div>
              <div class="user-role">Administrateur</div>
            </div>
          </div>
          <button mat-icon-button (click)="logout()" class="logout-btn" matTooltip="Se déconnecter">
            <mat-icon>logout</mat-icon>
          </button>
        </div>
      </mat-sidenav>

      <!-- Main content -->
      <mat-sidenav-content class="sidenav-content">
        <!-- Top toolbar -->
        <mat-toolbar color="primary" class="toolbar">
          <button mat-icon-button (click)="drawer.toggle()" class="menu-btn">
            <mat-icon>menu</mat-icon>
          </button>

          <span class="toolbar-title">Administration</span>

          <span class="toolbar-spacer"></span>

          <div class="toolbar-actions">
            <button mat-icon-button [matMenuTriggerFor]="notificationsMenu" class="notification-btn">
              <mat-icon [matBadge]="notifications.length" matBadgeColor="warn">notifications</mat-icon>
            </button>

            <mat-menu #notificationsMenu="matMenu">
              <div class="notifications-header">
                <h3>Notifications</h3>
              </div>
              <mat-divider></mat-divider>
              <div class="notifications-list">
                <div *ngFor="let notification of notifications" class="notification-item">
                  <mat-icon class="notification-icon">{{notification.icon}}</mat-icon>
                  <div class="notification-content">
                    <div class="notification-title">{{notification.title}}</div>
                    <div class="notification-message">{{notification.message}}</div>
                  </div>
                </div>
              </div>
            </mat-menu>

            <button mat-icon-button [matMenuTriggerFor]="userMenu">
              <mat-icon>account_circle</mat-icon>
            </button>

            <mat-menu #userMenu="matMenu">
              <button mat-menu-item>
                <mat-icon>person</mat-icon>
                <span>Mon profil</span>
              </button>
              <button mat-menu-item>
                <mat-icon>settings</mat-icon>
                <span>Paramètres</span>
              </button>
              <mat-divider></mat-divider>
              <button mat-menu-item (click)="logout()">
                <mat-icon>logout</mat-icon>
                <span>Se déconnecter</span>
              </button>
            </mat-menu>
          </div>
        </mat-toolbar>

        <!-- Dashboard content -->
        <div class="dashboard-content" *ngIf="router.url === '/admin/dashboard'">
          <div class="dashboard-header">
            <h1>Tableau de bord</h1>
            <p>Bienvenue dans votre espace d'administration</p>
          </div>

          <!-- Statistics cards -->
          <div class="stats-grid">
            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon students">
                    <mat-icon>people</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{stats.eleves}}</div>
                    <div class="stat-label">Élèves inscrits</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="75" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon teachers">
                    <mat-icon>person</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{stats.enseignants}}</div>
                    <div class="stat-label">Enseignants</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="60" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon classes">
                    <mat-icon>class</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{stats.classes}}</div>
                    <div class="stat-label">Classes actives</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="85" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon subjects">
                    <mat-icon>book</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{stats.matieres}}</div>
                    <div class="stat-label">Matières enseignées</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="90" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>
          </div>

          <!-- Quick actions -->
          <div class="quick-actions">
            <h2>Actions rapides</h2>
            <div class="actions-grid">
              <button mat-raised-button color="primary" routerLink="/admin/eleves" class="action-btn">
                <mat-icon>person_add</mat-icon>
                Ajouter un élève
              </button>

              <button mat-raised-button color="accent" routerLink="/admin/enseignants" class="action-btn">
                <mat-icon>person_add</mat-icon>
                Ajouter un enseignant
              </button>

              <button mat-raised-button color="warn" routerLink="/admin/classes" class="action-btn">
                <mat-icon>add_circle</mat-icon>
                Créer une classe
              </button>

              <button mat-raised-button routerLink="/admin/bulletins" class="action-btn">
                <mat-icon>assessment</mat-icon>
                Générer bulletins
              </button>
            </div>
          </div>
        </div>

        <!-- Router outlet for other admin pages -->
        <router-outlet *ngIf="router.url !== '/admin/dashboard'"></router-outlet>
      </mat-sidenav-content>
    </mat-sidenav-container>
  `,
  styles: [`
    .sidenav-container {
      height: 100vh;
    }

    .sidenav {
      width: 280px;
      background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo-icon {
      font-size: 32px;
      width: 32px;
      height: 32px;
    }

    .logo-text {
      font-size: 18px;
      font-weight: 600;
    }

    .sidebar-nav {
      padding-top: 20px;
    }

    .nav-item {
      margin: 4px 12px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .nav-item:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .nav-item.active {
      background: rgba(255, 255, 255, 0.2);
    }

    .count-chip {
      font-size: 12px;
      height: 20px;
    }

    .sidebar-footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 16px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .user-avatar {
      font-size: 40px;
      width: 40px;
      height: 40px;
    }

    .user-name {
      font-weight: 500;
      font-size: 14px;
    }

    .user-role {
      font-size: 12px;
      opacity: 0.8;
    }

    .logout-btn {
      color: white;
    }

    .sidenav-content {
      background: #f5f5f5;
    }

    .toolbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .menu-btn {
      margin-right: 16px;
    }

    .toolbar-title {
      font-size: 18px;
      font-weight: 500;
    }

    .toolbar-spacer {
      flex: 1 1 auto;
    }

    .toolbar-actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .notification-btn {
      position: relative;
    }

    .dashboard-content {
      padding: 24px;
    }

    .dashboard-header {
      margin-bottom: 32px;
    }

    .dashboard-header h1 {
      font-size: 32px;
      font-weight: 600;
      color: #333;
      margin: 0 0 8px 0;
    }

    .dashboard-header p {
      font-size: 16px;
      color: #666;
      margin: 0;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }

    .stat-card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-4px);
    }

    .stat-content {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 16px;
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-icon mat-icon {
      font-size: 32px;
      width: 32px;
      height: 32px;
      color: white;
    }

    .stat-icon.students {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.teachers {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon.classes {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.subjects {
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .stat-info {
      flex: 1;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 700;
      color: #333;
      line-height: 1;
    }

    .stat-label {
      font-size: 14px;
      color: #666;
      margin-top: 4px;
    }

    .stat-progress {
      height: 6px;
      border-radius: 3px;
    }

    .quick-actions {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .quick-actions h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 20px 0;
    }

    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }

    .action-btn {
      height: 48px;
      border-radius: 8px;
      font-weight: 500;
    }

    .action-btn mat-icon {
      margin-right: 8px;
    }

    .notifications-header {
      padding: 16px;
      background: #f5f5f5;
    }

    .notifications-header h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }

    .notifications-list {
      max-height: 300px;
      overflow-y: auto;
    }

    .notification-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-bottom: 1px solid #f0f0f0;
    }

    .notification-icon {
      color: #667eea;
    }

    .notification-content {
      flex: 1;
    }

    .notification-title {
      font-weight: 500;
      font-size: 14px;
      margin-bottom: 4px;
    }

    .notification-message {
      font-size: 12px;
      color: #666;
    }

    @media (max-width: 768px) {
      .sidenav {
        width: 250px;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .actions-grid {
        grid-template-columns: 1fr;
      }
    }
  `]
})
export class AdminDashboardComponent implements OnInit {
  currentUser: any;
  stats = {
    eleves: 0,
    enseignants: 0,
    classes: 0,
    matieres: 0
  };

  notifications = [
    {
      icon: 'notifications',
      title: 'Nouveau bulletin disponible',
      message: 'Le bulletin du 1er trimestre est prêt'
    },
    {
      icon: 'person_add',
      title: 'Nouvel élève inscrit',
      message: 'Marie Dupont a été ajoutée à la classe 6ème A'
    }
  ];

  constructor(
    private authService: AuthService,
    public router: Router,
    private dashboardService: DashboardService
  ) {}

  ngOnInit(): void {
    this.currentUser = this.authService.getCurrentUser();
    this.loadStats();
  }

  loadStats(): void {
    // Load real statistics from API
    this.dashboardService.getEleveCount().subscribe({
      next: (response) => this.stats.eleves = response.count,
      error: (error) => console.error('Error loading student count:', error)
    });

    this.dashboardService.getEnseignantCount().subscribe({
      next: (response) => this.stats.enseignants = response.count,
      error: (error) => console.error('Error loading teacher count:', error)
    });

    this.dashboardService.getClasseCount().subscribe({
      next: (response) => this.stats.classes = response.count,
      error: (error) => console.error('Error loading class count:', error)
    });

    this.dashboardService.getMatiereCount().subscribe({
      next: (response) => this.stats.matieres = response.count,
      error: (error) => console.error('Error loading subject count:', error)
    });
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}