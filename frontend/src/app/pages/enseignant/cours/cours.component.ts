import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-cours',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="cours-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Mes Cours</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de gestion des cours en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .cours-container {
      padding: 24px;
    }
  `]
})
export class CoursComponent {}