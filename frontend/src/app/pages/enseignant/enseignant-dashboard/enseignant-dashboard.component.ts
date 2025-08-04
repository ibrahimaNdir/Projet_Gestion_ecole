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
import { MatTableModule } from '@angular/material/table';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-enseignant-dashboard',
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
    MatProgressBarModule,
    MatTableModule
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
            <span class="logo-text">Espace Enseignant</span>
          </div>
        </div>

        <mat-nav-list class="sidebar-nav">
          <a mat-list-item routerLink="/enseignant/dashboard" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>dashboard</mat-icon>
            <span matListItemTitle>Tableau de bord</span>
          </a>

          <a mat-list-item routerLink="/enseignant/notes" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>grade</mat-icon>
            <span matListItemTitle>Saisie des notes</span>
            <mat-chip matListItemMeta color="warn" class="count-chip">{{pendingGrades}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/enseignant/cours" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>class</mat-icon>
            <span matListItemTitle>Mes cours</span>
            <mat-chip matListItemMeta color="primary" class="count-chip">{{myCourses.length}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/enseignant/emploi-temps" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>schedule</mat-icon>
            <span matListItemTitle>Emploi du temps</span>
          </a>
        </mat-nav-list>

        <mat-divider></mat-divider>

        <div class="sidebar-footer">
          <div class="user-info">
            <mat-icon class="user-avatar">account_circle</mat-icon>
            <div class="user-details">
              <div class="user-name">{{currentUser?.name}}</div>
              <div class="user-role">Enseignant</div>
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

          <span class="toolbar-title">Espace Enseignant</span>

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
        <div class="dashboard-content" *ngIf="router.url === '/enseignant/dashboard'">
          <div class="dashboard-header">
            <h1>Tableau de bord</h1>
            <p>Bienvenue dans votre espace enseignant</p>
          </div>

          <!-- Statistics cards -->
          <div class="stats-grid">
            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon courses">
                    <mat-icon>class</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{myCourses.length}}</div>
                    <div class="stat-label">Cours dispensés</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="80" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon students">
                    <mat-icon>people</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{totalStudents}}</div>
                    <div class="stat-label">Élèves</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="65" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon grades">
                    <mat-icon>grade</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{pendingGrades}}</div>
                    <div class="stat-label">Notes en attente</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="45" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>

            <mat-card class="stat-card">
              <mat-card-content>
                <div class="stat-content">
                  <div class="stat-icon subjects">
                    <mat-icon>book</mat-icon>
                  </div>
                  <div class="stat-info">
                    <div class="stat-number">{{mySubjects.length}}</div>
                    <div class="stat-label">Matières enseignées</div>
                  </div>
                </div>
                <mat-progress-bar mode="determinate" [value]="90" class="stat-progress"></mat-progress-bar>
              </mat-card-content>
            </mat-card>
          </div>

          <!-- My courses section -->
          <div class="courses-section">
            <div class="section-header">
              <h2>Mes cours</h2>
              <button mat-raised-button color="primary" routerLink="/enseignant/cours">
                Voir tous mes cours
              </button>
            </div>

            <div class="courses-grid">
              <mat-card *ngFor="let course of myCourses.slice(0, 4)" class="course-card">
                <mat-card-header>
                  <mat-card-title>{{course.matiere}}</mat-card-title>
                  <mat-card-subtitle>{{course.classe}}</mat-card-subtitle>
                </mat-card-header>
                <mat-card-content>
                  <div class="course-info">
                    <div class="info-item">
                      <mat-icon>schedule</mat-icon>
                      <span>{{course.horaire}}</span>
                    </div>
                    <div class="info-item">
                      <mat-icon>people</mat-icon>
                      <span>{{course.nombreEleves}} élèves</span>
                    </div>
                    <div class="info-item">
                      <mat-icon>grade</mat-icon>
                      <span>{{course.notesEnAttente}} notes à saisir</span>
                    </div>
                  </div>
                </mat-card-content>
                <mat-card-actions>
                  <button mat-button color="primary" (click)="viewCourse(course)">
                    Voir détails
                  </button>
                  <button mat-button color="accent" (click)="saisirNotes(course)">
                    Saisir notes
                  </button>
                </mat-card-actions>
              </mat-card>
            </div>
          </div>

          <!-- Recent activities -->
          <div class="activities-section">
            <h2>Activités récentes</h2>
            <div class="activities-list">
              <div *ngFor="let activity of recentActivities" class="activity-item">
                <div class="activity-icon">
                  <mat-icon>{{activity.icon}}</mat-icon>
                </div>
                <div class="activity-content">
                  <div class="activity-title">{{activity.title}}</div>
                  <div class="activity-time">{{activity.time}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Router outlet for other teacher pages -->
        <router-outlet *ngIf="router.url !== '/enseignant/dashboard'"></router-outlet>
      </mat-sidenav-content>
    </mat-sidenav-container>
  `,
  styles: [`
    .sidenav-container {
      height: 100vh;
    }

    .sidenav {
      width: 280px;
      background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%);
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

    .stat-icon.courses {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.students {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.grades {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

    .courses-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 24px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .section-header h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .course-card {
      border-radius: 12px;
      transition: transform 0.3s ease;
    }

    .course-card:hover {
      transform: translateY(-4px);
    }

    .course-info {
      margin-top: 12px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
      font-size: 14px;
      color: #666;
    }

    .info-item mat-icon {
      font-size: 18px;
      width: 18px;
      height: 18px;
      color: #4facfe;
    }

    .activities-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .activities-section h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 20px 0;
    }

    .activities-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .activity-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px;
      border-radius: 8px;
      background: #f8f9fa;
      transition: background 0.3s ease;
    }

    .activity-item:hover {
      background: #e9ecef;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #4facfe;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .activity-icon mat-icon {
      color: white;
      font-size: 20px;
      width: 20px;
      height: 20px;
    }

    .activity-content {
      flex: 1;
    }

    .activity-title {
      font-weight: 500;
      color: #333;
      margin-bottom: 4px;
    }

    .activity-time {
      font-size: 12px;
      color: #666;
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
      color: #4facfe;
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

      .courses-grid {
        grid-template-columns: 1fr;
      }

      .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }
    }
  `]
})
export class EnseignantDashboardComponent implements OnInit {
  currentUser: any;
  pendingGrades = 0;
  totalStudents = 0;

  myCourses = [
    {
      id: 1,
      matiere: 'Mathématiques',
      classe: '6ème A',
      horaire: 'Lundi 8h-10h',
      nombreEleves: 25,
      notesEnAttente: 5
    },
    {
      id: 2,
      matiere: 'Mathématiques',
      classe: '5ème B',
      horaire: 'Mardi 14h-16h',
      nombreEleves: 28,
      notesEnAttente: 3
    },
    {
      id: 3,
      matiere: 'Physique',
      classe: '4ème A',
      horaire: 'Mercredi 10h-12h',
      nombreEleves: 22,
      notesEnAttente: 8
    },
    {
      id: 4,
      matiere: 'Physique',
      classe: '3ème B',
      horaire: 'Jeudi 16h-18h',
      nombreEleves: 30,
      notesEnAttente: 2
    }
  ];

  mySubjects = ['Mathématiques', 'Physique'];

  recentActivities = [
    {
      icon: 'grade',
      title: 'Notes saisies pour la classe 6ème A',
      time: 'Il y a 2 heures'
    },
    {
      icon: 'class',
      title: 'Cours de Mathématiques terminé',
      time: 'Il y a 4 heures'
    },
    {
      icon: 'notifications',
      title: 'Nouveau bulletin à générer',
      time: 'Il y a 1 jour'
    }
  ];

  notifications = [
    {
      icon: 'grade',
      title: 'Notes en attente',
      message: 'Vous avez 18 notes à saisir cette semaine'
    },
    {
      icon: 'notifications',
      title: 'Réunion pédagogique',
      message: 'Réunion prévue vendredi à 14h'
    }
  ];

  constructor(
    private authService: AuthService,
    public router: Router
  ) {}

  ngOnInit(): void {
    this.currentUser = this.authService.getCurrentUser();
    this.loadTeacherStats();
  }

  loadTeacherStats(): void {
    // Calculate stats from courses data
    this.pendingGrades = this.myCourses.reduce((total, course) => total + course.notesEnAttente, 0);
    this.totalStudents = this.myCourses.reduce((total, course) => total + course.nombreEleves, 0);
  }

  viewCourse(course: any): void {
    // Navigate to course details
    this.router.navigate(['/enseignant/cours', course.id]);
  }

  saisirNotes(course: any): void {
    // Navigate to grade entry
    this.router.navigate(['/enseignant/notes'], { queryParams: { course: course.id } });
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}