import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-parents',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="parents-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Gestion des Parents</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de gestion des parents en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .parents-container {
      padding: 24px;
    }
  `]
})
export class ParentsComponent {}