# Guide de contribution — Convention de branches

Ce document définit la convention de nommage des branches et le flux de
développement à suivre pour uniformiser le travail de l'équipe, faciliter
le suivi des contributions et améliorer la collaboration.

## 1. Convention de nommage des branches

Chaque développeur travaille sur sa propre branche nommée selon le format :

```
dev_<nom_du_développeur>
```

**Exemples :** `dev_didier`, `dev_jp`, `dev_andee777`

## 2. Rôle des branches

| Branche        | Rôle                                                                 |
|----------------|----------------------------------------------------------------------|
| `dev_<nom>`    | Travail individuel de chaque développeur.                            |
| `dev_test`     | Branche d'**intégration** : tests et validation avant fusion.       |
| `main`         | Branche **principale / production** : uniquement du code validé.    |

## 3. Flux de travail

```
dev_<nom>  ─────►  dev_test  ─────►  main
 (je code)     (tests & validation)  (validé)
```

### Étape 1 — Travailler sur sa branche personnelle

```bash
git checkout main
git pull origin main               # partir d'une base à jour
git checkout -b dev_didier         # 1re fois ; ensuite : git checkout dev_didier

# ... modifications ...
git add .
git commit -m "feat: description claire"
git push origin dev_didier
```

### Étape 2 — Envoyer vers la branche d'intégration

```bash
git checkout dev_test
git pull origin dev_test
git merge dev_didier
git push origin dev_test           # ➜ lancer les tests / la validation ici
```

### Étape 3 — Fusion vers `main` (après validation)

> Réalisée par le responsable / l'intégrateur, une fois les tests validés.

```bash
git checkout main
git pull origin main
git merge dev_test
git push origin main
```

## 4. Règles d'or

1. **Jamais de `git push` direct sur `main`** — tout passe d'abord par `dev_test`.
2. **Chaque développeur reste sur SA branche `dev_<nom>`.**
3. **Toujours `git pull` avant de merger** pour éviter les conflits.


