import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-classes',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="classes-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Gestion des Classes</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de gestion des classes en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .classes-container {
      padding: 24px;
    }
  `]
})
export class ClassesComponent {}