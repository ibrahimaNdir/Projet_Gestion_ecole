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
import { MatTabsModule } from '@angular/material/tabs';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-parent-dashboard',
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
    MatTableModule,
    MatTabsModule
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
            <span class="logo-text">Espace Parent</span>
          </div>
        </div>

        <mat-nav-list class="sidebar-nav">
          <a mat-list-item routerLink="/parent/dashboard" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>dashboard</mat-icon>
            <span matListItemTitle>Tableau de bord</span>
          </a>

          <a mat-list-item routerLink="/parent/enfants" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>family_restroom</mat-icon>
            <span matListItemTitle>Mes enfants</span>
            <mat-chip matListItemMeta color="primary" class="count-chip">{{children.length}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/parent/bulletins" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>assessment</mat-icon>
            <span matListItemTitle>Bulletins</span>
            <mat-chip matListItemMeta color="accent" class="count-chip">{{newBulletins}}</mat-chip>
          </a>

          <a mat-list-item routerLink="/parent/communications" routerLinkActive="active" class="nav-item">
            <mat-icon matListItemIcon>message</mat-icon>
            <span matListItemTitle>Communications</span>
            <mat-chip matListItemMeta color="warn" class="count-chip">{{newMessages}}</mat-chip>
          </a>
        </mat-nav-list>

        <mat-divider></mat-divider>

        <div class="sidebar-footer">
          <div class="user-info">
            <mat-icon class="user-avatar">account_circle</mat-icon>
            <div class="user-details">
              <div class="user-name">{{currentUser?.name}}</div>
              <div class="user-role">Parent</div>
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

          <span class="toolbar-title">Espace Parent</span>

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
        <div class="dashboard-content" *ngIf="router.url === '/parent/dashboard'">
          <div class="dashboard-header">
            <h1>Tableau de bord</h1>
            <p>Suivez la progression académique de vos enfants</p>
          </div>

          <!-- Parent info card -->
          <mat-card class="parent-info-card">
            <div class="parent-profile">
              <div class="parent-avatar">
                <mat-icon>account_circle</mat-icon>
              </div>
              <div class="parent-details">
                <h2>{{currentUser?.name}}</h2>
                <p class="parent-role">Parent de {{children.length}} enfant(s)</p>
                <p class="parent-email">{{currentUser?.email}}</p>
              </div>
            </div>
          </mat-card>

          <!-- Children overview -->
          <div class="children-section">
            <h2>Mes enfants</h2>
            <div class="children-grid">
              <mat-card *ngFor="let child of children" class="child-card" (click)="selectChild(child)">
                <div class="child-header">
                  <div class="child-avatar">
                    <img *ngIf="child.photo" [src]="child.photo" [alt]="child.nom">
                    <mat-icon *ngIf="!child.photo">account_circle</mat-icon>
                  </div>
                  <div class="child-info">
                    <h3>{{child.nom}} {{child.prenom}}</h3>
                    <p class="child-class">{{child.classe}}</p>
                    <p class="child-age">{{child.age}} ans</p>
                  </div>
                  <div class="child-status">
                    <mat-chip [color]="child.statut === 'actif' ? 'primary' : 'warn'" selected>
                      {{child.statut === 'actif' ? 'Actif' : 'Inactif'}}
                    </mat-chip>
                  </div>
                </div>

                <mat-divider></mat-divider>

                <div class="child-stats">
                  <div class="stat-item">
                    <div class="stat-label">Moyenne générale</div>
                    <div class="stat-value">{{child.moyenne}}/20</div>
                    <mat-progress-bar mode="determinate" [value]="(child.moyenne/20)*100" class="stat-progress"></mat-progress-bar>
                  </div>

                  <div class="stat-item">
                    <div class="stat-label">Rang</div>
                    <div class="stat-value">{{child.rang}}/{{child.totalEleves}}</div>
                  </div>

                  <div class="stat-item">
                    <div class="stat-label">Nouvelles notes</div>
                    <div class="stat-value">{{child.nouvellesNotes}}</div>
                  </div>
                </div>

                <mat-card-actions>
                  <button mat-button color="primary" (click)="viewChildDetails(child)">
                    Voir détails
                  </button>
                  <button mat-button color="accent" (click)="viewChildGrades(child)">
                    Notes
                  </button>
                  <button mat-button color="warn" (click)="viewChildBulletins(child)">
                    Bulletins
                  </button>
                </mat-card-actions>
              </mat-card>
            </div>
          </div>

          <!-- Selected child details -->
          <div *ngIf="selectedChild" class="child-details-section">
            <mat-card>
              <mat-card-header>
                <mat-card-title>Détails de {{selectedChild.nom}} {{selectedChild.prenom}}</mat-card-title>
                <mat-card-subtitle>{{selectedChild.classe}} - Année {{selectedChild.anneeScolaire}}</mat-card-subtitle>
              </mat-card-header>

              <mat-card-content>
                <mat-tab-group>
                  <mat-tab label="Notes récentes">
                    <div class="grades-table">
                      <table mat-table [dataSource]="selectedChild.notesRecentes" class="grades-table">
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
                  </mat-tab>

                  <mat-tab label="Bulletins disponibles">
                    <div class="bulletins-list">
                      <div *ngFor="let bulletin of selectedChild.bulletins" class="bulletin-item">
                        <div class="bulletin-info">
                          <div class="bulletin-title">{{bulletin.periode}}</div>
                          <div class="bulletin-details">
                            <span>Moyenne: {{bulletin.moyenne}}/20</span>
                            <span>Rang: {{bulletin.rang}}/{{bulletin.totalEleves}}</span>
                            <span>Date: {{bulletin.date | date:'dd/MM/yyyy'}}</span>
                          </div>
                        </div>
                        <div class="bulletin-actions">
                          <button mat-raised-button color="primary" (click)="downloadBulletin(bulletin)">
                            <mat-icon>download</mat-icon>
                            Télécharger
                          </button>
                          <button mat-button (click)="viewBulletin(bulletin)">
                            <mat-icon>visibility</mat-icon>
                            Voir
                          </button>
                        </div>
                      </div>
                    </div>
                  </mat-tab>

                  <mat-tab label="Emploi du temps">
                    <div class="schedule-list">
                      <div *ngFor="let course of selectedChild.emploiTemps" class="schedule-item">
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
                        <div class="schedule-day">
                          <mat-chip color="accent" selected>{{course.jour}}</mat-chip>
                        </div>
                      </div>
                    </div>
                  </mat-tab>
                </mat-tab-group>
              </mat-card-content>
            </mat-card>
          </div>

          <!-- Recent communications -->
          <div class="communications-section">
            <h2>Communications récentes</h2>
            <div class="communications-list">
              <div *ngFor="let comm of communications" class="communication-item">
                <div class="comm-icon">
                  <mat-icon>{{comm.icon}}</mat-icon>
                </div>
                <div class="comm-content">
                  <div class="comm-title">{{comm.title}}</div>
                  <div class="comm-message">{{comm.message}}</div>
                  <div class="comm-meta">
                    <span class="comm-date">{{comm.date | date:'dd/MM/yyyy'}}</span>
                    <span class="comm-sender">{{comm.sender}}</span>
                  </div>
                </div>
                <div class="comm-actions">
                  <button mat-icon-button (click)="markAsRead(comm)">
                    <mat-icon>mark_email_read</mat-icon>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Router outlet for other parent pages -->
        <router-outlet *ngIf="router.url !== '/parent/dashboard'"></router-outlet>
      </mat-sidenav-content>
    </mat-sidenav-container>
  `,
  styles: [`
    .sidenav-container {
      height: 100vh;
    }

    .sidenav {
      width: 280px;
      background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%);
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

    .parent-info-card {
      margin-bottom: 24px;
      border-radius: 12px;
    }

    .parent-profile {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .parent-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .parent-avatar mat-icon {
      font-size: 48px;
      color: #999;
    }

    .parent-details h2 {
      font-size: 24px;
      font-weight: 600;
      color: #333;
      margin: 0 0 8px 0;
    }

    .parent-role {
      font-size: 16px;
      color: #666;
      margin: 0 0 4px 0;
    }

    .parent-email {
      font-size: 14px;
      color: #999;
      margin: 0;
    }

    .children-section {
      margin-bottom: 24px;
    }

    .children-section h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 16px 0;
    }

    .children-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 20px;
    }

    .child-card {
      border-radius: 12px;
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .child-card:hover {
      transform: translateY(-4px);
    }

    .child-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 16px;
    }

    .child-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f0f0;
    }

    .child-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .child-avatar mat-icon {
      font-size: 32px;
      color: #999;
    }

    .child-info {
      flex: 1;
    }

    .child-info h3 {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin: 0 0 4px 0;
    }

    .child-class {
      font-size: 14px;
      color: #666;
      margin: 0 0 2px 0;
    }

    .child-age {
      font-size: 12px;
      color: #999;
      margin: 0;
    }

    .child-stats {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
      margin: 16px 0;
    }

    .stat-item {
      text-align: center;
    }

    .stat-label {
      font-size: 12px;
      color: #666;
      margin-bottom: 4px;
    }

    .stat-value {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    .stat-progress {
      height: 6px;
      border-radius: 3px;
    }

    .child-details-section {
      margin-bottom: 24px;
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
      color: #f093fb;
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

    .bulletins-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .bulletin-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
      border-radius: 8px;
      background: #f8f9fa;
      transition: background 0.3s ease;
    }

    .bulletin-item:hover {
      background: #e9ecef;
    }

    .bulletin-info {
      flex: 1;
    }

    .bulletin-title {
      font-weight: 600;
      color: #333;
      margin-bottom: 4px;
    }

    .bulletin-details {
      display: flex;
      gap: 16px;
      font-size: 12px;
      color: #666;
    }

    .bulletin-actions {
      display: flex;
      gap: 8px;
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

    .schedule-day {
      min-width: 100px;
    }

    .communications-section {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .communications-section h2 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
      margin: 0 0 20px 0;
    }

    .communications-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .communication-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px;
      border-radius: 8px;
      background: #f8f9fa;
      transition: background 0.3s ease;
    }

    .communication-item:hover {
      background: #e9ecef;
    }

    .comm-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #f093fb;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .comm-icon mat-icon {
      color: white;
      font-size: 20px;
      width: 20px;
      height: 20px;
    }

    .comm-content {
      flex: 1;
    }

    .comm-title {
      font-weight: 500;
      color: #333;
      margin-bottom: 4px;
    }

    .comm-message {
      font-size: 14px;
      color: #666;
      margin-bottom: 8px;
    }

    .comm-meta {
      display: flex;
      gap: 16px;
      font-size: 12px;
      color: #999;
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
      color: #f093fb;
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

      .children-grid {
        grid-template-columns: 1fr;
      }

      .child-stats {
        grid-template-columns: 1fr;
      }

      .parent-profile {
        flex-direction: column;
        text-align: center;
      }
    }
  `]
})
export class ParentDashboardComponent implements OnInit {
  currentUser: any;
  newBulletins = 0;
  newMessages = 0;
  selectedChild: any = null;

  children = [
    {
      id: 1,
      nom: 'Dupont',
      prenom: 'Marie',
      classe: '6ème A',
      age: 12,
      anneeScolaire: '2024-2025',
      statut: 'actif',
      photo: null,
      moyenne: 15.5,
      rang: 8,
      totalEleves: 25,
      nouvellesNotes: 3,
      notesRecentes: [
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
        }
      ],
      bulletins: [
        {
          periode: '1er Trimestre',
          moyenne: 15.5,
          rang: 8,
          totalEleves: 25,
          date: new Date('2024-01-10')
        },
        {
          periode: '2ème Trimestre',
          moyenne: 16.2,
          rang: 6,
          totalEleves: 25,
          date: new Date('2024-02-15')
        }
      ],
      emploiTemps: [
        {
          jour: 'Lundi',
          time: '08:00',
          duration: '2h',
          matiere: 'Mathématiques',
          teacher: 'M. Martin',
          room: 'Salle 101'
        },
        {
          jour: 'Mardi',
          time: '14:00',
          duration: '1h',
          matiere: 'Français',
          teacher: 'Mme Dubois',
          room: 'Salle 102'
        }
      ]
    },
    {
      id: 2,
      nom: 'Dupont',
      prenom: 'Pierre',
      classe: '4ème B',
      age: 14,
      anneeScolaire: '2024-2025',
      statut: 'actif',
      photo: null,
      moyenne: 13.8,
      rang: 15,
      totalEleves: 28,
      nouvellesNotes: 1,
      notesRecentes: [
        {
          matiere: 'Histoire',
          icon: 'history',
          note: 12,
          coefficient: 2,
          date: new Date('2024-01-13'),
          appreciation: 'Peut mieux faire, révisez davantage'
        }
      ],
      bulletins: [
        {
          periode: '1er Trimestre',
          moyenne: 13.8,
          rang: 15,
          totalEleves: 28,
          date: new Date('2024-01-10')
        }
      ],
      emploiTemps: []
    }
  ];

  gradeColumns = ['matiere', 'note', 'coefficient', 'date', 'appreciation'];

  communications = [
    {
      icon: 'message',
      title: 'Réunion parents-professeurs',
      message: 'La réunion parents-professeurs est prévue le vendredi 20 janvier à 18h',
      date: new Date('2024-01-12'),
      sender: 'Direction'
    },
    {
      icon: 'notifications',
      title: 'Nouveau bulletin disponible',
      message: 'Le bulletin du 1er trimestre de Marie est disponible',
      date: new Date('2024-01-10'),
      sender: 'Système'
    }
  ];

  notifications = [
    {
      icon: 'assessment',
      title: 'Nouveaux bulletins',
      message: '2 nouveaux bulletins disponibles'
    },
    {
      icon: 'grade',
      title: 'Nouvelles notes',
      message: '4 nouvelles notes ont été ajoutées'
    }
  ];

  constructor(
    private authService: AuthService,
    public router: Router
  ) {}

  ngOnInit(): void {
    this.currentUser = this.authService.getCurrentUser();
    this.loadParentStats();
  }

  loadParentStats(): void {
    this.newBulletins = this.children.reduce((total, child) => total + child.bulletins.length, 0);
    this.newMessages = this.communications.length;
  }

  selectChild(child: any): void {
    this.selectedChild = child;
  }

  viewChildDetails(child: any): void {
    this.selectChild(child);
  }

  viewChildGrades(child: any): void {
    this.router.navigate(['/parent/enfants', child.id, 'notes']);
  }

  viewChildBulletins(child: any): void {
    this.router.navigate(['/parent/enfants', child.id, 'bulletins']);
  }

  downloadBulletin(bulletin: any): void {
    // Implement download functionality
    console.log('Downloading bulletin:', bulletin);
  }

  viewBulletin(bulletin: any): void {
    // Implement view functionality
    console.log('Viewing bulletin:', bulletin);
  }

  markAsRead(comm: any): void {
    // Implement mark as read functionality
    console.log('Marking as read:', comm);
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