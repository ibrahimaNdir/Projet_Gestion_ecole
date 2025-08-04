import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-matieres',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="matieres-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Gestion des Matières</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de gestion des matières en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .matieres-container {
      padding: 24px;
    }
  `]
})
export class MatieresComponent {}