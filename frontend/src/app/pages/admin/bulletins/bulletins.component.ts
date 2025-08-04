import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-bulletins',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="bulletins-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Gestion des Bulletins</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de gestion des bulletins en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .bulletins-container {
      padding: 24px;
    }
  `]
})
export class BulletinsComponent {}