import { Component, OnInit, ViewChild } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { MatTableModule, MatTableDataSource } from '@angular/material/table';
import { MatPaginatorModule, MatPaginator } from '@angular/material/paginator';
import { MatSortModule, MatSort } from '@angular/material/sort';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatDialogModule, MatDialog } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar';
import { MatChipsModule } from '@angular/material/chips';
import { MatMenuModule } from '@angular/material/menu';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatDividerModule } from '@angular/material/divider';
import { Eleve, CreateEleveRequest, UpdateEleveRequest } from '../../../models/eleve.model';

@Component({
  selector: 'app-eleves',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    MatTableModule,
    MatPaginatorModule,
    MatSortModule,
    MatCardModule,
    MatButtonModule,
    MatIconModule,
    MatDialogModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatSnackBarModule,
    MatChipsModule,
    MatMenuModule,
    MatTooltipModule,
    MatProgressSpinnerModule,
    MatDividerModule
  ],
  template: `
    <div class="eleves-container">
      <!-- Header -->
      <div class="page-header">
        <div class="header-content">
          <h1>Gestion des Élèves</h1>
          <p>Gérez les inscriptions et les informations des élèves</p>
        </div>
        <button mat-raised-button color="primary" (click)="openAddDialog()" class="add-btn">
          <mat-icon>person_add</mat-icon>
          Ajouter un élève
        </button>
      </div>

      <!-- Filters and search -->
      <mat-card class="filters-card">
        <div class="filters-content">
          <mat-form-field appearance="outline" class="search-field">
            <mat-label>Rechercher un élève</mat-label>
            <input matInput [(ngModel)]="searchTerm" (input)="applyFilter()" placeholder="Nom, prénom, email...">
            <mat-icon matSuffix>search</mat-icon>
          </mat-form-field>

          <mat-form-field appearance="outline" class="filter-field">
            <mat-label>Statut</mat-label>
            <mat-select [(ngModel)]="statusFilter" (selectionChange)="applyFilter()">
              <mat-option value="">Tous</mat-option>
              <mat-option value="actif">Actif</mat-option>
              <mat-option value="inactif">Inactif</mat-option>
            </mat-select>
          </mat-form-field>

          <mat-form-field appearance="outline" class="filter-field">
            <mat-label>Sexe</mat-label>
            <mat-select [(ngModel)]="genderFilter" (selectionChange)="applyFilter()">
              <mat-option value="">Tous</mat-option>
              <mat-option value="M">Masculin</mat-option>
              <mat-option value="F">Féminin</mat-option>
            </mat-select>
          </mat-form-field>
        </div>
      </mat-card>

      <!-- Students table -->
      <mat-card class="table-card">
        <div class="table-container">
          <table mat-table [dataSource]="dataSource" matSort class="students-table">
            <!-- Photo Column -->
            <ng-container matColumnDef="photo">
              <th mat-header-cell *matHeaderCellDef>Photo</th>
              <td mat-cell *matCellDef="let eleve">
                <div class="student-avatar">
                  <img *ngIf="eleve.photo" [src]="eleve.photo" [alt]="eleve.nom">
                  <mat-icon *ngIf="!eleve.photo">account_circle</mat-icon>
                </div>
              </td>
            </ng-container>

            <!-- Name Column -->
            <ng-container matColumnDef="nom">
              <th mat-header-cell *matHeaderCellDef mat-sort-header>Nom</th>
              <td mat-cell *matCellDef="let eleve">
                <div class="student-name">
                  <div class="full-name">{{eleve.nom}} {{eleve.prenom}}</div>
                  <div class="student-email">{{eleve.email}}</div>
                </div>
              </td>
            </ng-container>

            <!-- Birth Date Column -->
            <ng-container matColumnDef="date_naissance">
              <th mat-header-cell *matHeaderCellDef mat-sort-header>Date de naissance</th>
              <td mat-cell *matCellDef="let eleve">
                {{eleve.date_naissance | date:'dd/MM/yyyy'}}
              </td>
            </ng-container>

            <!-- Gender Column -->
            <ng-container matColumnDef="sexe">
              <th mat-header-cell *matHeaderCellDef>Sexe</th>
              <td mat-cell *matCellDef="let eleve">
                <mat-chip [color]="eleve.sexe === 'M' ? 'primary' : 'accent'" selected>
                  {{eleve.sexe === 'M' ? 'Masculin' : 'Féminin'}}
                </mat-chip>
              </td>
            </ng-container>

            <!-- Phone Column -->
            <ng-container matColumnDef="telephone">
              <th mat-header-cell *matHeaderCellDef>Téléphone</th>
              <td mat-cell *matCellDef="let eleve">
                {{eleve.telephone}}
              </td>
            </ng-container>

            <!-- Status Column -->
            <ng-container matColumnDef="statut">
              <th mat-header-cell *matHeaderCellDef>Statut</th>
              <td mat-cell *matCellDef="let eleve">
                <mat-chip [color]="eleve.statut === 'actif' ? 'primary' : 'warn'" selected>
                  {{eleve.statut === 'actif' ? 'Actif' : 'Inactif'}}
                </mat-chip>
              </td>
            </ng-container>

            <!-- Actions Column -->
            <ng-container matColumnDef="actions">
              <th mat-header-cell *matHeaderCellDef>Actions</th>
              <td mat-cell *matCellDef="let eleve">
                <button mat-icon-button [matMenuTriggerFor]="menu" class="action-btn">
                  <mat-icon>more_vert</mat-icon>
                </button>
                <mat-menu #menu="matMenu">
                  <button mat-menu-item (click)="viewStudent(eleve)">
                    <mat-icon>visibility</mat-icon>
                    <span>Voir détails</span>
                  </button>
                  <button mat-menu-item (click)="editStudent(eleve)">
                    <mat-icon>edit</mat-icon>
                    <span>Modifier</span>
                  </button>
                  <button mat-menu-item (click)="toggleStatus(eleve)">
                    <mat-icon>{{eleve.statut === 'actif' ? 'block' : 'check_circle'}}</mat-icon>
                    <span>{{eleve.statut === 'actif' ? 'Désactiver' : 'Activer'}}</span>
                  </button>
                  <mat-divider></mat-divider>
                  <button mat-menu-item (click)="deleteStudent(eleve)" class="delete-action">
                    <mat-icon>delete</mat-icon>
                    <span>Supprimer</span>
                  </button>
                </mat-menu>
              </td>
            </ng-container>

            <tr mat-header-row *matHeaderRowDef="displayedColumns"></tr>
            <tr mat-row *matRowDef="let row; columns: displayedColumns;" class="student-row"></tr>
          </table>

          <mat-paginator [pageSizeOptions]="[10, 25, 50, 100]" showFirstLastButtons></mat-paginator>
        </div>
      </mat-card>

      <!-- Loading spinner -->
      <div *ngIf="isLoading" class="loading-overlay">
        <mat-spinner diameter="50"></mat-spinner>
        <p>Chargement des élèves...</p>
      </div>
    </div>

    <!-- Add/Edit Student Dialog -->
    <div class="dialog-container" *ngIf="showDialog">
      <div class="dialog-overlay" (click)="closeDialog()"></div>
      <div class="dialog-content">
        <div class="dialog-header">
          <h2>{{isEditing ? 'Modifier l\'élève' : 'Ajouter un élève'}}</h2>
          <button mat-icon-button (click)="closeDialog()">
            <mat-icon>close</mat-icon>
          </button>
        </div>

        <form [formGroup]="studentForm" (ngSubmit)="onSubmit()" class="dialog-form">
          <div class="form-row">
            <mat-form-field appearance="outline">
              <mat-label>Nom</mat-label>
              <input matInput formControlName="nom" placeholder="Nom de famille">
              <mat-error *ngIf="studentForm.get('nom')?.hasError('required')">
                Le nom est requis
              </mat-error>
            </mat-form-field>

            <mat-form-field appearance="outline">
              <mat-label>Prénom</mat-label>
              <input matInput formControlName="prenom" placeholder="Prénom">
              <mat-error *ngIf="studentForm.get('prenom')?.hasError('required')">
                Le prénom est requis
              </mat-error>
            </mat-form-field>
          </div>

          <div class="form-row">
            <mat-form-field appearance="outline">
              <mat-label>Date de naissance</mat-label>
              <input matInput [matDatepicker]="picker" formControlName="date_naissance">
              <mat-datepicker-toggle matSuffix [for]="picker"></mat-datepicker-toggle>
              <mat-datepicker #picker></mat-datepicker>
              <mat-error *ngIf="studentForm.get('date_naissance')?.hasError('required')">
                La date de naissance est requise
              </mat-error>
            </mat-form-field>

            <mat-form-field appearance="outline">
              <mat-label>Lieu de naissance</mat-label>
              <input matInput formControlName="lieu_naissance" placeholder="Ville, Pays">
              <mat-error *ngIf="studentForm.get('lieu_naissance')?.hasError('required')">
                Le lieu de naissance est requis
              </mat-error>
            </mat-form-field>
          </div>

          <div class="form-row">
            <mat-form-field appearance="outline">
              <mat-label>Sexe</mat-label>
              <mat-select formControlName="sexe">
                <mat-option value="M">Masculin</mat-option>
                <mat-option value="F">Féminin</mat-option>
              </mat-select>
              <mat-error *ngIf="studentForm.get('sexe')?.hasError('required')">
                Le sexe est requis
              </mat-error>
            </mat-form-field>

            <mat-form-field appearance="outline">
              <mat-label>Téléphone</mat-label>
              <input matInput formControlName="telephone" placeholder="+33 6 12 34 56 78">
              <mat-error *ngIf="studentForm.get('telephone')?.hasError('required')">
                Le téléphone est requis
              </mat-error>
            </mat-form-field>
          </div>

          <mat-form-field appearance="outline" class="full-width">
            <mat-label>Adresse</mat-label>
            <textarea matInput formControlName="adresse" placeholder="Adresse complète" rows="3"></textarea>
            <mat-error *ngIf="studentForm.get('adresse')?.hasError('required')">
              L'adresse est requise
            </mat-error>
          </mat-form-field>

          <mat-form-field appearance="outline" class="full-width">
            <mat-label>Email</mat-label>
            <input matInput formControlName="email" type="email" placeholder="eleve@email.com">
            <mat-error *ngIf="studentForm.get('email')?.hasError('required')">
              L'email est requis
            </mat-error>
            <mat-error *ngIf="studentForm.get('email')?.hasError('email')">
              Veuillez entrer un email valide
            </mat-error>
          </mat-form-field>

          <div class="dialog-actions">
            <button mat-button type="button" (click)="closeDialog()">Annuler</button>
            <button mat-raised-button color="primary" type="submit" [disabled]="studentForm.invalid || isSubmitting">
              <mat-spinner diameter="20" *ngIf="isSubmitting"></mat-spinner>
              <span *ngIf="!isSubmitting">{{isEditing ? 'Modifier' : 'Ajouter'}}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  `,
  styles: [`
    .eleves-container {
      padding: 24px;
      position: relative;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .header-content h1 {
      font-size: 28px;
      font-weight: 600;
      color: #333;
      margin: 0 0 4px 0;
    }

    .header-content p {
      font-size: 14px;
      color: #666;
      margin: 0;
    }

    .add-btn {
      height: 48px;
      border-radius: 8px;
    }

    .add-btn mat-icon {
      margin-right: 8px;
    }

    .filters-card {
      margin-bottom: 24px;
    }

    .filters-content {
      display: flex;
      gap: 16px;
      align-items: center;
      flex-wrap: wrap;
    }

    .search-field {
      flex: 1;
      min-width: 250px;
    }

    .filter-field {
      min-width: 150px;
    }

    .table-card {
      margin-bottom: 24px;
    }

    .table-container {
      overflow-x: auto;
    }

    .students-table {
      width: 100%;
    }

    .student-avatar {
      width: 40px;
      height: 40px;
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
      font-size: 24px;
      color: #999;
    }

    .student-name {
      display: flex;
      flex-direction: column;
    }

    .full-name {
      font-weight: 500;
      color: #333;
    }

    .student-email {
      font-size: 12px;
      color: #666;
    }

    .student-row:hover {
      background: #f8f9fa;
    }

    .action-btn {
      color: #666;
    }

    .delete-action {
      color: #f44336;
    }

    .loading-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .loading-overlay p {
      margin-top: 16px;
      color: #666;
    }

    /* Dialog styles */
    .dialog-container {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .dialog-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
    }

    .dialog-content {
      position: relative;
      background: white;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .dialog-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px 24px 0 24px;
      border-bottom: 1px solid #f0f0f0;
      margin-bottom: 24px;
    }

    .dialog-header h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      color: #333;
    }

    .dialog-form {
      padding: 0 24px 24px 24px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 16px;
    }

    .full-width {
      width: 100%;
      margin-bottom: 16px;
    }

    .dialog-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 24px;
    }

    @media (max-width: 768px) {
      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
      }

      .filters-content {
        flex-direction: column;
        align-items: stretch;
      }

      .search-field,
      .filter-field {
        min-width: auto;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .dialog-content {
        width: 95%;
        margin: 20px;
      }
    }
  `]
})
export class ElevesComponent implements OnInit {
  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  displayedColumns: string[] = ['photo', 'nom', 'date_naissance', 'sexe', 'telephone', 'statut', 'actions'];
  dataSource = new MatTableDataSource<Eleve>([]);

  searchTerm = '';
  statusFilter = '';
  genderFilter = '';

  isLoading = false;
  showDialog = false;
  isEditing = false;
  isSubmitting = false;
  selectedStudent: Eleve | null = null;

  studentForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private snackBar: MatSnackBar
  ) {
    this.studentForm = this.fb.group({
      nom: ['', Validators.required],
      prenom: ['', Validators.required],
      date_naissance: ['', Validators.required],
      lieu_naissance: ['', Validators.required],
      sexe: ['', Validators.required],
      adresse: ['', Validators.required],
      telephone: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]]
    });
  }

  ngOnInit(): void {
    this.loadStudents();
  }

  ngAfterViewInit() {
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  loadStudents(): void {
    this.isLoading = true;

    // Simulate API call - replace with actual service
    setTimeout(() => {
      const mockStudents: Eleve[] = [
        {
          id: 1,
          nom: 'Dupont',
          prenom: 'Marie',
          date_naissance: '2008-05-15',
          lieu_naissance: 'Paris, France',
          sexe: 'F',
          adresse: '123 Rue de la Paix, 75001 Paris',
          telephone: '+33 6 12 34 56 78',
          email: 'marie.dupont@email.com',
          statut: 'actif',
          date_inscription: '2023-09-01',
          created_at: '2023-09-01T00:00:00Z',
          updated_at: '2023-09-01T00:00:00Z'
        },
        {
          id: 2,
          nom: 'Martin',
          prenom: 'Pierre',
          date_naissance: '2007-12-03',
          lieu_naissance: 'Lyon, France',
          sexe: 'M',
          adresse: '456 Avenue des Champs, 69001 Lyon',
          telephone: '+33 6 98 76 54 32',
          email: 'pierre.martin@email.com',
          statut: 'actif',
          date_inscription: '2023-09-01',
          created_at: '2023-09-01T00:00:00Z',
          updated_at: '2023-09-01T00:00:00Z'
        }
      ];

      this.dataSource.data = mockStudents;
      this.isLoading = false;
    }, 1000);
  }

  applyFilter(): void {
    let filteredData = this.dataSource.data;

    if (this.searchTerm) {
      const search = this.searchTerm.toLowerCase();
      filteredData = filteredData.filter(eleve =>
        eleve.nom.toLowerCase().includes(search) ||
        eleve.prenom.toLowerCase().includes(search) ||
        eleve.email.toLowerCase().includes(search)
      );
    }

    if (this.statusFilter) {
      filteredData = filteredData.filter(eleve => eleve.statut === this.statusFilter);
    }

    if (this.genderFilter) {
      filteredData = filteredData.filter(eleve => eleve.sexe === this.genderFilter);
    }

    this.dataSource.data = filteredData;
  }

  openAddDialog(): void {
    this.isEditing = false;
    this.selectedStudent = null;
    this.studentForm.reset();
    this.showDialog = true;
  }

  editStudent(eleve: Eleve): void {
    this.isEditing = true;
    this.selectedStudent = eleve;
    this.studentForm.patchValue({
      nom: eleve.nom,
      prenom: eleve.prenom,
      date_naissance: new Date(eleve.date_naissance),
      lieu_naissance: eleve.lieu_naissance,
      sexe: eleve.sexe,
      adresse: eleve.adresse,
      telephone: eleve.telephone,
      email: eleve.email
    });
    this.showDialog = true;
  }

  closeDialog(): void {
    this.showDialog = false;
    this.studentForm.reset();
  }

  onSubmit(): void {
    if (this.studentForm.valid) {
      this.isSubmitting = true;

      const formData = this.studentForm.value;

      // Simulate API call
      setTimeout(() => {
        if (this.isEditing && this.selectedStudent) {
          // Update existing student
          const index = this.dataSource.data.findIndex(s => s.id === this.selectedStudent!.id);
          if (index !== -1) {
            this.dataSource.data[index] = {
              ...this.selectedStudent,
              ...formData,
              date_naissance: formData.date_naissance.toISOString().split('T')[0]
            };
            this.dataSource._updateChangeSubscription();
            this.snackBar.open('Élève modifié avec succès', 'Fermer', { duration: 3000 });
          }
        } else {
          // Add new student
          const newStudent: Eleve = {
            id: this.dataSource.data.length + 1,
            ...formData,
            date_naissance: formData.date_naissance.toISOString().split('T')[0],
            statut: 'actif',
            date_inscription: new Date().toISOString().split('T')[0],
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString()
          };
          this.dataSource.data = [newStudent, ...this.dataSource.data];
          this.snackBar.open('Élève ajouté avec succès', 'Fermer', { duration: 3000 });
        }

        this.isSubmitting = false;
        this.closeDialog();
      }, 1000);
    }
  }

  viewStudent(eleve: Eleve): void {
    // Implement view details
    this.snackBar.open(`Voir les détails de ${eleve.nom} ${eleve.prenom}`, 'Fermer', { duration: 2000 });
  }

  toggleStatus(eleve: Eleve): void {
    const newStatus = eleve.statut === 'actif' ? 'inactif' : 'actif';
    const index = this.dataSource.data.findIndex(s => s.id === eleve.id);

    if (index !== -1) {
      this.dataSource.data[index].statut = newStatus;
      this.dataSource._updateChangeSubscription();
      this.snackBar.open(`Statut de ${eleve.nom} ${eleve.prenom} modifié`, 'Fermer', { duration: 3000 });
    }
  }

  deleteStudent(eleve: Eleve): void {
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${eleve.nom} ${eleve.prenom} ?`)) {
      const index = this.dataSource.data.findIndex(s => s.id === eleve.id);

      if (index !== -1) {
        this.dataSource.data.splice(index, 1);
        this.dataSource._updateChangeSubscription();
        this.snackBar.open('Élève supprimé avec succès', 'Fermer', { duration: 3000 });
      }
    }
  }
}