# Plateforme de Gestion Scolaire - Frontend

Une application Angular moderne pour la gestion scolaire complète avec interface utilisateur en français.

## 🚀 Fonctionnalités

### 👨‍💼 Administration
- **Tableau de bord** avec statistiques en temps réel
- **Gestion des élèves** (CRUD complet)
- **Gestion des enseignants** (profils et affectations)
- **Gestion des classes** et matières
- **Gestion des bulletins** et périodes d'évaluation
- **Gestion des parents** et documents

### 👨‍🏫 Enseignants
- **Tableau de bord** personnalisé
- **Saisie des notes** par matière et classe
- **Gestion des cours** et emploi du temps
- **Suivi des performances** des élèves

### 👨‍🎓 Élèves
- **Tableau de bord** avec notes et moyennes
- **Consultation des notes** par matière
- **Téléchargement des bulletins** en PDF
- **Emploi du temps** personnel

### 👨‍👩‍👧‍👦 Parents
- **Tableau de bord** multi-enfants
- **Suivi des performances** de chaque enfant
- **Consultation des bulletins** et notes
- **Communications** avec l'établissement

## 🛠️ Technologies utilisées

- **Angular 20** - Framework principal
- **Angular Material** - Composants UI
- **TypeScript** - Langage de programmation
- **RxJS** - Programmation réactive
- **Angular Router** - Navigation
- **Angular Forms** - Gestion des formulaires

## 📦 Installation

### Prérequis
- Node.js (version 18 ou supérieure)
- npm ou yarn
- Angular CLI

### Installation des dépendances
```bash
npm install
```

### Démarrage du serveur de développement
```bash
npm start
```

L'application sera accessible à l'adresse `http://localhost:4200`

### Compilation pour la production
```bash
npm run build
```

## 🏗️ Structure du projet

```
src/
├── app/
│   ├── models/           # Interfaces TypeScript
│   ├── services/         # Services Angular
│   ├── guards/           # Guards d'authentification
│   ├── interceptors/     # Intercepteurs HTTP
│   └── pages/           # Composants de pages
│       ├── login/       # Page de connexion
│       ├── admin/       # Pages d'administration
│       ├── enseignant/  # Pages enseignants
│       ├── eleve/       # Pages élèves
│       └── parent/      # Pages parents
├── assets/              # Ressources statiques
└── styles.css          # Styles globaux
```

## 🔐 Authentification

L'application utilise un système d'authentification basé sur les rôles :

- **admin** : Accès complet à toutes les fonctionnalités
- **enseignant** : Gestion des notes et cours
- **eleve** : Consultation des notes et bulletins
- **parent** : Suivi des enfants

## 🎨 Design System

### Couleurs principales
- **Primaire** : #667eea (Bleu)
- **Accent** : #4facfe (Bleu clair)
- **Warn** : #f093fb (Rose)

### Composants Material Design
- Cards avec effets de survol
- Tables avec tri et pagination
- Formulaires avec validation
- Navigation latérale responsive
- Notifications et snackbars

## 📱 Responsive Design

L'application est entièrement responsive et s'adapte à :
- **Desktop** : Interface complète avec sidebar
- **Tablet** : Interface adaptée avec navigation optimisée
- **Mobile** : Interface mobile-first avec navigation hamburger

## 🔧 Configuration

### Variables d'environnement
Créer un fichier `environment.ts` dans `src/environments/` :

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api/v1'
};
```

### Configuration API
L'application se connecte au backend Laravel via l'URL configurée dans `environment.ts`.

## 🧪 Tests

### Tests unitaires
```bash
npm test
```

### Tests e2e
```bash
npm run e2e
```

## 📦 Scripts disponibles

- `npm start` - Démarre le serveur de développement
- `npm run build` - Compile pour la production
- `npm test` - Lance les tests unitaires
- `npm run e2e` - Lance les tests e2e
- `npm run lint` - Vérifie le code avec ESLint

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou problème :
- Créer une issue sur GitHub
- Contacter l'équipe de développement

---

**Développé avec ❤️ pour l'éducation**
