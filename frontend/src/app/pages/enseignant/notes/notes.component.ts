import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-notes',
  standalone: true,
  imports: [CommonModule, MatCardModule],
  template: `
    <div class="notes-container">
      <mat-card>
        <mat-card-header>
          <mat-card-title>Saisie des Notes</mat-card-title>
        </mat-card-header>
        <mat-card-content>
          <p>Interface de saisie des notes en cours de développement...</p>
        </mat-card-content>
      </mat-card>
    </div>
  `,
  styles: [`
    .notes-container {
      padding: 24px;
    }
  `]
})
export class NotesComponent {}