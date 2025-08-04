import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-enfants-bulletins',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="enfants-bulletins-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Bulletins de mon enfant</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de consultation des bulletins de l'enfant en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .enfants-bulletins-container {
      padding: 24px;
    }
  `]
})
export class EnfantsBulletinsComponent {}