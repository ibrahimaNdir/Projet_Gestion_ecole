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
  selector: 'app-eleve-dashboard',
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
            <span class="logo-text">Espace Élève</span>
          </div>
        </div>

        <mat-nav-list class="sidebar-nav">
          <a mat-list-item routerLink="/eleve/dashboard" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>dashboard</mat-icon>
            <span matListItemTitle>Tableau de bord</span>
          </a>

          <a mat-list-item routerLink="/eleve/notes" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>grade</mat-icon>
            <span matListItemTitle>Mes notes</span>
            <mat-chip matListItemMeta color="primary" class="count-chip">{{newGrades}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/eleve/bulletins" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>assessment</mat-icon>
            <span matListItemTitle>Mes bulletins</span>
            <mat-chip matListItemMeta color="accent" class="count-chip">{{newBulletins}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/eleve/emploi-temps" routerLinkActive="active" class="nav-item">
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
              <div class="user-role">Élève</div>
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

          <span class="toolbar-title">Espace Élève</span>

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
        <div class="dashboard-content" *ngIf="router.url === '/eleve/dashboard'">
          <div class="dashboard-header">
            <h1>Tableau de bord</h1>
            <p>Bienvenue dans votre espace élève</p>
          </div>

          <!-- Student info card -->
          <mat-card class="student-info-card">
            <div class="student-profile">
              <div class="student-avatar">
                <img *ngIf="studentInfo.photo" [src]="studentInfo.photo" [alt]="studentInfo.nom">
                <mat-icon *ngIf="!studentInfo.photo">account_circle</mat-icon>
              </div>
              <div class="student-details">
                <h2>{{studentInfo.nom}} {{studentInfo.prenom}}</h2>
                <p class="student-class">{{studentInfo.classe}}</p>
                <p class="student-year">Année scolaire {{studentInfo.anneeScolaire}}</p>
              </div>
            </div>
          </mat-card>

          <!-- Academic performance -->
          <div class="performance-section">
            <h2>Performance académique</h2>
            <div class="performance-grid">
              <mat-card class="performance-card">
                <mat-card-content>
                  <div class="performance-header">
                    <mat-icon class="performance-icon">trending_up</mat-icon>
                    <div class="performance-title">Moyenne générale</div>
                  </div>
                  <div class="performance-value">{{averageGrade}}/20</div>
                  <mat-progress-bar mode="determinate" [value]="(averageGrade/20)*100" class="performance-progress"></mat-progress-bar>
                  <div class="performance-detail">Rang: {{rank}}/{{totalStudents}}</div>
                </mat-card-content>
              </mat-card>

              <mat-card class="performance-card">
                <mat-card-content>
                  <div class="performance-header">
                    <mat-icon class="performance-icon">grade</mat-icon>
                    <div class="performance-title">Notes récentes</div>
                  </div>
                  <div class="performance-value">{{newGrades}}</div>
                  <div class="performance-detail">Nouvelles notes cette semaine</div>
                </mat-card-content>
              </mat-card>

              <mat-card class="performance-card">
                <mat-card-content>
                  <div class="performance-header">
                    <mat-icon class="performance-icon">assessment</mat-icon>
                    <div class="performance-title">Bulletins</div>
                  </div>
                  <div class="performance-value">{{newBulletins}}</div>
                  <div class="performance-detail">Nouveaux bulletins disponibles</div>
                </mat-card-content>
              </mat-card>
            </div>
          </div>

          <!-- Recent grades -->
          <div class="grades-section">
            <div class="section-header">
              <h2>Notes récentes</h2>
              <button mat-raised-button color="primary" routerLink="/eleve/notes">
                Voir toutes mes notes
              </button>
            </div>

            <div class="grades-table">
              <table mat-table [dataSource]="recentGrades" class="grades-table">
                <ng-container matColumnDef="matiere">
                  <th mat-header-cell *matHeaderCellDef>Matière</th>
                  <td mat-cell *matCellDef="let grade">
                    <div class="subject-info">
                      <mat-icon class="subject-icon">{{grade.icon}}</mat-icon>
                      <span>{{grade.matiere}}</span>
                    </div>
                  </td>
                </ng-container>

                <ng-container matColumnDef="note">
                  <th mat-header-cell *matHeaderCellDef>Note</th>
                  <td mat-cell *matCellDef="let grade">
                    <div class="grade-value" [class]="getGradeClass(grade.note)">
                      {{grade.note}}/20
                    </div>
                  </td>
                </ng-container>

                <ng-container matColumnDef="coefficient">
                  <th mat-header-cell *matHeaderCellDef>Coeff.</th>
                  <td mat-cell *matCellDef="let grade">
                    <mat-chip color="primary" selected>{{grade.coefficient}}</mat-chip>
                  </td>
                </ng-container>

                <ng-container matColumnDef="date">
                  <th mat-header-cell *matHeaderCellDef>Date</th>
                  <td mat-cell *matCellDef="let grade">
                    {{grade.date | date:'dd/MM/yyyy'}}
                  </td>
                </ng-container>

                <ng-container matColumnDef="appreciation">
                  <th mat-header-cell *matHeaderCellDef>Appréciation</th>
                  <td mat-cell *matCellDef="let grade">
                    <span class="appreciation">{{grade.appreciation}}</span>
                  </td>
                </ng-container>

                <tr mat-header-row *matHeaderRowDef="gradeColumns"></tr>
                <tr mat-row *matRowDef="let row; columns: gradeColumns;"></tr>
              </table>
            </div>
          </div>

          <!-- Next classes -->
          <div class="schedule-section">
            <h2>Prochains cours</h2>
            <div class="schedule-list">
              <div *ngFor="let course of nextClasses" class="schedule-item">
                <div class="schedule-time">
                  <div class="time">{{course.time}}</div>
                  <div class="duration">{{course.duration}}</div>
                </div>
                <div class="schedule-content">
                  <div class="course-name">{{course.matiere}}</div>
                  <div class="course-details">
                    <span class="teacher">{{course.teacher}}</span>
                    <span class="room">{{course.room}}</span>
                  </div>
                </div>
                <div class="schedule-status">
                  <mat-chip [color]="course.status === 'now' ? 'warn' : 'primary'" selected>
                    {{course.status === 'now' ? 'En cours' : course.status}}
                  </mat-chip>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Router outlet for other student pages -->
        <router-outlet *ngIf="router.url !== '/eleve/dashboard'"></router-outlet>
      </mat-sidenav-content>
    </mat-sidenav-container>
  `,
  styles: [`
    .sidenav-container {
      height: 100vh;
    }

    .sidenav {
      width: 280px;
      background: linear-gradient(180deg, #43e97b 0%, #38f9d7 100%);
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
      margin-bottom: 24px;
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

    .student-info-card {
      margin-bottom: 24px;
      border-radius: 12px;
    }

    .student-profile {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .student-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f0f0;
    }

    .student-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .student-avatar mat-icon {
      font-size: 48px;
      color: #999;
    }

    .student-details h2 {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      margin: 0 0 8px 0;
    }

    .student-class {
      font-size: 16px;
      color: #666;
      margin: 0 0 4px 0;
    }

    .student-year {
      font-size: 14px;
      color: #999;
      margin: 0;
    }

    .performance-section {
      margin-bottom: 24px;
    }

    .performance-section h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 16px 0;
    }

    .performance-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .performance-card {
      border-radius: 12px;
      transition: transform 0.3s ease;
    }

    .performance-card:hover {
      transform: translateY(-4px);
    }

    .performance-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
    }

    .performance-icon {
      color: #43e97b;
      font-size: 24px;
      width: 24px;
      height: 24px;
    }

    .performance-title {
      font-weight: 500;
      color: #333;
    }

    .performance-value {
      font-size: 32px;
      font-weight: 700;
      color: #333;
      margin-bottom: 12px;
    }

    .performance-progress {
      height: 8px;
      border-radius: 4px;
      margin-bottom: 8px;
    }

    .performance-detail {
      font-size: 14px;
      color: #666;
    }

    .grades-section {
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

    .grades-table {
      width: 100%;
      overflow-x: auto;
    }

    .subject-info {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .subject-icon {
      color: #43e97b;
      font-size: 20px;
      width: 20px;
      height: 20px;
    }

    .grade-value {
      font-weight: 600;
      font-size: 16px;
    }

    .grade-value.excellent {
      color: #4caf50;
    }

    .grade-value.good {
      color: #2196f3;
    }

    .grade-value.average {
      color: #ff9800;
    }

    .grade-value.poor {
      color: #f44336;
    }

    .appreciation {
      font-style: italic;
      color: #666;
    }

    .schedule-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .schedule-section h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 20px 0;
    }

    .schedule-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .schedule-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px;
      border-radius: 8px;
      background: #f8f9fa;
      transition: background 0.3s ease;
    }

    .schedule-item:hover {
      background: #e9ecef;
    }

    .schedule-time {
      text-align: center;
      min-width: 80px;
    }

    .time {
      font-weight: 600;
      color: #333;
      font-size: 14px;
    }

    .duration {
      font-size: 12px;
      color: #666;
    }

    .schedule-content {
      flex: 1;
    }

    .course-name {
      font-weight: 500;
      color: #333;
      margin-bottom: 4px;
    }

    .course-details {
      display: flex;
      gap: 16px;
      font-size: 12px;
      color: #666;
    }

    .schedule-status {
      min-width: 100px;
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
      color: #43e97b;
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

      .performance-grid {
        grid-template-columns: 1fr;
      }

      .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }

      .student-profile {
        flex-direction: column;
        text-align: center;
      }
    }
  `]
})
export class EleveDashboardComponent implements OnInit {
  currentUser: any;
  newGrades = 0;
  newBulletins = 0;
  averageGrade = 0;
  rank = 0;
  totalStudents = 0;

  studentInfo = {
    nom: 'Dupont',
    prenom: 'Marie',
    classe: '6ème A',
    anneeScolaire: '2024-2025',
    photo: null
  };

  recentGrades = [
    {
      matiere: 'Mathématiques',
      icon: 'functions',
      note: 16,
      coefficient: 4,
      date: new Date('2024-01-15'),
      appreciation: 'Très bon travail, continuez ainsi !'
    },
    {
      matiere: 'Français',
      icon: 'book',
      note: 14,
      coefficient: 3,
      date: new Date('2024-01-14'),
      appreciation: 'Bon travail, attention à l\'orthographe'
    },
    {
      matiere: 'Histoire',
      icon: 'history',
      note: 18,
      coefficient: 2,
      date: new Date('2024-01-13'),
      appreciation: 'Excellent ! Très bonne compréhension'
    },
    {
      matiere: 'Sciences',
      icon: 'science',
      note: 12,
      coefficient: 3,
      date: new Date('2024-01-12'),
      appreciation: 'Peut mieux faire, révisez les bases'
    }
  ];

  gradeColumns = ['matiere', 'note', 'coefficient', 'date', 'appreciation'];

  nextClasses = [
    {
      time: '14:00',
      duration: '2h',
      matiere: 'Mathématiques',
      teacher: 'M. Martin',
      room: 'Salle 101',
      status: 'now'
    },
    {
      time: '16:00',
      duration: '1h',
      matiere: 'Français',
      teacher: 'Mme Dubois',
      room: 'Salle 102',
      status: 'next'
    },
    {
      time: '17:00',
      duration: '1h',
      matiere: 'Histoire',
      teacher: 'M. Durand',
      room: 'Salle 103',
      status: 'later'
    }
  ];

  notifications = [
    {
      icon: 'grade',
      title: 'Nouvelle note disponible',
      message: 'Vous avez reçu une note en Mathématiques'
    },
    {
      icon: 'assessment',
      title: 'Bulletin disponible',
      message: 'Votre bulletin du 1er trimestre est prêt'
    }
  ];

  constructor(
    private authService: AuthService,
    public router: Router
  ) {}

  ngOnInit(): void {
    this.currentUser = this.authService.getCurrentUser();
    this.loadStudentStats();
  }

  loadStudentStats(): void {
    // Calculate stats from grades data
    this.newGrades = 4;
    this.newBulletins = 1;
    this.averageGrade = 15;
    this.rank = 8;
    this.totalStudents = 25;
  }

  getGradeClass(note: number): string {
    if (note >= 16) return 'excellent';
    if (note >= 14) return 'good';
    if (note >= 12) return 'average';
    return 'poor';
  }

  logout(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}