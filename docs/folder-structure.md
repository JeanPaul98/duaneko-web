# Folder Structure

```
duaneko-web/
├── docs
│   ├── feuilles-de-temps
│   │   ├── andee777
│   │   │   └── 2026-06.md
│   │   └── modele.md
│   └── folder-structure.md
├── infra
│   └── docker
│       ├── .env-example
│       ├── compose.override.yaml
│       ├── compose.prod.yaml
│       └── compose.yaml
├── src
│   ├── backend-fastapi
│   │   ├── app
│   │   │   ├── routers
│   │   │   │   ├── auth.py
│   │   │   │   └── signalements.py
│   │   │   ├── auth.py
│   │   │   ├── config.py
│   │   │   ├── database.py
│   │   │   ├── dependencies.py
│   │   │   ├── main.py
│   │   │   ├── models.py
│   │   │   └── schemas.py
│   │   └── requirements.txt
│   ├── frontend-expo
│   │   ├── .claude
│   │   │   └── settings.json
│   │   ├── .expo
│   │   │   ├── types
│   │   │   │   └── router.d.ts
│   │   │   ├── devices.json
│   │   │   ├── README.md
│   │   │   └── settings.json
│   │   ├── .vscode
│   │   │   ├── extensions.json
│   │   │   └── settings.json
│   │   ├── app
│   │   │   ├── _layout.tsx
│   │   │   ├── actualites.tsx
│   │   │   ├── ajouter-signalement.tsx
│   │   │   ├── index.tsx
│   │   │   ├── messages.tsx
│   │   │   ├── modal.tsx
│   │   │   ├── notifications.tsx
│   │   │   ├── parametres.tsx
│   │   │   ├── profil.tsx
│   │   │   ├── programmations.tsx
│   │   │   ├── signalement.tsx
│   │   │   ├── signalements.tsx
│   │   │   └── utilisateurs.tsx
│   │   ├── assets
│   │   │   └── images
│   │   │       ├── android-icon-background.png
│   │   │       ├── android-icon-foreground.png
│   │   │       ├── android-icon-monochrome.png
│   │   │       ├── favicon.png
│   │   │       ├── icon.png
│   │   │       ├── partial-react-logo.png
│   │   │       ├── react-logo.png
│   │   │       ├── react-logo@2x.png
│   │   │       ├── react-logo@3x.png
│   │   │       └── splash-icon.png
│   │   ├── components
│   │   │   ├── ui
│   │   │   │   ├── button.tsx
│   │   │   │   ├── card.tsx
│   │   │   │   ├── collapsible.tsx
│   │   │   │   ├── icon-symbol.ios.tsx
│   │   │   │   ├── icon-symbol.tsx
│   │   │   │   ├── input.tsx
│   │   │   │   ├── label.tsx
│   │   │   │   ├── separator.tsx
│   │   │   │   └── text.tsx
│   │   │   ├── DrawerMenu.tsx
│   │   │   ├── external-link.tsx
│   │   │   ├── haptic-tab.tsx
│   │   │   ├── hello-wave.tsx
│   │   │   ├── parallax-scroll-view.tsx
│   │   │   ├── Signalement.tsx
│   │   │   ├── themed-text.tsx
│   │   │   ├── themed-view.tsx
│   │   │   └── WelcomeBanner.tsx
│   │   ├── constants
│   │   │   └── theme.ts
│   │   ├── hooks
│   │   │   ├── use-color-scheme.ts
│   │   │   ├── use-color-scheme.web.ts
│   │   │   └── use-theme-color.ts
│   │   ├── lib
│   │   │   ├── theme.ts
│   │   │   └── utils.ts
│   │   ├── scripts
│   │   │   └── reset-project.js
│   │   ├── .gitignore
│   │   ├── AGENTS.md
│   │   ├── app.json
│   │   ├── babel.config.js
│   │   ├── CLAUDE.md
│   │   ├── components.json
│   │   ├── eslint.config.js
│   │   ├── expo-env.d.ts
│   │   ├── global.css
│   │   ├── metro.config.js
│   │   ├── nativewind-env.d.ts
│   │   ├── package.json
│   │   ├── pnpm-lock.yaml
│   │   ├── pnpm-workspace.yaml
│   │   ├── README.md
│   │   ├── tailwind.config.js
│   │   └── tsconfig.json
│   ├── frontend-nextjs
│   │   ├── app
│   │   │   ├── favicon.ico
│   │   │   ├── globals.css
│   │   │   ├── layout.tsx
│   │   │   └── page.tsx
│   │   ├── components
│   │   │   └── ui
│   │   │       └── button.tsx
│   │   ├── lib
│   │   │   └── utils.ts
│   │   ├── public
│   │   │   ├── file.svg
│   │   │   ├── globe.svg
│   │   │   ├── next.svg
│   │   │   ├── vercel.svg
│   │   │   └── window.svg
│   │   ├── .gitignore
│   │   ├── components.json
│   │   ├── eslint.config.mjs
│   │   ├── next-env.d.ts
│   │   ├── next.config.ts
│   │   ├── package.json
│   │   ├── pnpm-lock.yaml
│   │   ├── pnpm-workspace.yaml
│   │   ├── postcss.config.mjs
│   │   ├── README.md
│   │   └── tsconfig.json
│   └── migrated
│       ├── src
│       │   ├── app
│       │   │   ├── (app)
│       │   │   │   ├── _layout.tsx
│       │   │   │   ├── Actu.tsx
│       │   │   │   ├── Actus.tsx
│       │   │   │   ├── AjouterActu.tsx
│       │   │   │   ├── AjouterProgrammation.tsx
│       │   │   │   ├── AjouterSignalement.tsx
│       │   │   │   ├── Camera.tsx
│       │   │   │   ├── Carte.tsx
│       │   │   │   ├── GestionUtilisateurs.tsx
│       │   │   │   ├── Messages.tsx
│       │   │   │   ├── Notifications.tsx
│       │   │   │   ├── Parametres.tsx
│       │   │   │   ├── Programmation.tsx
│       │   │   │   ├── Programmations.tsx
│       │   │   │   ├── Signalement.tsx
│       │   │   │   ├── SignalementPicsScreen.tsx
│       │   │   │   ├── Signalements.tsx
│       │   │   │   └── Support.tsx
│       │   │   ├── (auth)
│       │   │   │   ├── _layout.tsx
│       │   │   │   ├── ConnexionAcceuil.tsx
│       │   │   │   ├── PolitiqueConfidentialite.tsx
│       │   │   │   ├── SignIn.tsx
│       │   │   │   ├── SignUpUsernameScreen.tsx
│       │   │   │   ├── SignUpUserPasswordDetails.tsx
│       │   │   │   ├── SignUpUserPersonalDetails.tsx
│       │   │   │   ├── SignUpUserTelephoneDetails.tsx
│       │   │   │   └── SignUpUserVerificationCodeScreen.tsx
│       │   │   └── _layout.tsx
│       │   ├── components
│       │   │   ├── ui
│       │   │   │   └── collapsible.tsx
│       │   │   ├── animated-icon.module.css
│       │   │   ├── animated-icon.tsx
│       │   │   ├── animated-icon.web.tsx
│       │   │   ├── drawer-contents.tsx
│       │   │   ├── external-link.tsx
│       │   │   ├── themed-text.tsx
│       │   │   └── themed-view.tsx
│       │   ├── composants
│       │   │   ├── AuthContext.js
│       │   │   ├── Filtres.js
│       │   │   ├── LoginStatePanel.js
│       │   │   ├── Measurements.js
│       │   │   ├── MyLocationIcon.js
│       │   │   ├── Signalement.js
│       │   │   ├── SocketContext.js
│       │   │   └── util.js
│       │   ├── constants
│       │   │   └── theme.ts
│       │   ├── context
│       │   │   ├── app-context.tsx
│       │   │   ├── auth-context.tsx
│       │   │   └── socket-context.tsx
│       │   ├── hooks
│       │   │   ├── use-color-scheme.ts
│       │   │   ├── use-color-scheme.web.ts
│       │   │   └── use-theme.ts
│       │   ├── pages
│       │   │   ├── actus
│       │   │   │   ├── Actus.js
│       │   │   │   ├── ActuScreen.js
│       │   │   │   └── AjouterActu.js
│       │   │   ├── connexion
│       │   │   │   ├── Connexion.js
│       │   │   │   └── ConnexionAcceuilScreen.js
│       │   │   ├── inscription
│       │   │   │   ├── PolitiqueConfidentialiteScreen.js
│       │   │   │   ├── SignUpUsernameScreen.js
│       │   │   │   ├── SignUpUserPasswordDetailsScreen.js
│       │   │   │   ├── SignUpUserPersonalDetailsScreen.js
│       │   │   │   ├── SignUpUserTelephoneDetailsScreen.js
│       │   │   │   └── SignUpUserVerificationCodeScreen.js
│       │   │   ├── programmations
│       │   │   │   ├── AjouterProgrammation.js
│       │   │   │   ├── Programmations.js
│       │   │   │   └── ProgrammationScreen.js
│       │   │   ├── AjouterSignalement.js
│       │   │   ├── CameraScreen.js
│       │   │   ├── ClusteredMapView.js
│       │   │   ├── GestionUtilisateurs.js
│       │   │   ├── Messages.js
│       │   │   ├── Notifications.js
│       │   │   ├── Parametres.js
│       │   │   ├── SignalementPicsScreen.js
│       │   │   ├── SignalementScreen.js
│       │   │   └── SupportScreen.js
│       │   ├── util
│       │   │   └── togo_dataset.js
│       │   └── global.css
│       ├── app.json
│       ├── babel.config.js
│       ├── MIGRATION.md
│       └── packages-to-add.json
├── utils
│   └── generate_folder_tree.py
├── .gitattributes
├── .gitignore
├── package.json
├── pnpm-lock.yaml
└── README.md
```