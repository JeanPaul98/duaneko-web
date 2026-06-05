# Duaneko90 → Expo Router Migration Guide

## What changed and why

| Old (Expo 48 / App.js) | New (Expo Router) |
|---|---|
| Single `App.js` entry point | `src/app/_layout.tsx` root layout |
| `createNativeStackNavigator` + `createDrawerNavigator` | File-based routing via `expo-router` |
| `NavigationContainer` | Built into Expo Router |
| Auth redirect via React state in `App.js` | `<Redirect>` in root layout driven by `AuthContext` |
| `App.js` state reducer (12 actions) | Split into `AuthContext` + `AppContext` |
| `composants/AuthContext.js` re-exports only | Full typed `AuthProvider` in `src/context/` |
| `composants/SocketContext.js` | `src/context/socket-context.tsx` |

---

## Step-by-step setup

### 1. Install new dependencies

```bash
# Use expo install for these (gets the right SDK-pinned version):
npx expo install \
  expo-secure-store \
  expo-location \
  expo-camera \
  expo-blur \
  expo-image-manipulator \
  expo-web-browser \
  @react-navigation/drawer \
  react-native-gesture-handler \
  react-native-reanimated \
  react-native-safe-area-context \
  react-native-screens \
  react-native-maps \
  @react-native-picker/picker

# Use npm for packages with no SDK pinning needed:
npm install \
  react-native-paper@^5 \
  @expo/vector-icons \
  react-native-map-clustering \
  react-native-webview \
  "socket.io-client@^4" \
  react-native-status-bar-height \
  figlet
```

### 2. Replace app.json

Copy the provided `app.json` over your existing one. Key additions:
- `"scheme": "duaneko90"` — required by Expo Router for deep linking
- `"plugins": ["expo-router", ...]` — registers the router and native modules
- `"experiments": { "typedRoutes": true }` — enables TypeScript route autocomplete

### 3. Replace babel.config.js

Copy the provided `babel.config.js`. It's essentially identical to the old one —
just confirming `react-native-reanimated/plugin` stays as the last plugin.

### 4. Copy the src/ folder

Merge the provided `src/` into your new project's `src/`. The layout is:

```
src/
  app/
    _layout.tsx          ← Root: providers + auth redirect (replaces App.js)
    (auth)/
      _layout.tsx        ← Stack for login/signup screens
      ConnexionAcceuil.tsx, SignIn.tsx, …  ← thin wrappers over old pages/
    (app)/
      _layout.tsx        ← Drawer (replaces DrawerNav.js)
      Carte.tsx, Signalements.tsx, …  ← thin wrappers over old pages/
  context/
    auth-context.tsx     ← AuthProvider + useAuth hook (replaces App.js reducer)
    socket-context.tsx   ← socket + serverAddress
    app-context.tsx      ← location + socket data (signalements, actus, …)
  components/
    drawer-contents.tsx  ← Rewritten DrawerContents using useRouter
    animated-icon.tsx    ← From new scaffold (splash animation)
    themed-text.tsx      ← From new scaffold
    themed-view.tsx      ← From new scaffold
    …
  composants/            ← Your original composants, unchanged
    AuthContext.js       ← Shim: re-exports from context/auth-context
    SocketContext.js     ← Shim: re-exports from context/socket-context
    Filtres.js, LoginStatePanel.js, …  ← Untouched
  pages/                 ← Your original pages, untouched
    ClusteredMapView.js, Signalements.js, …
  constants/, hooks/     ← From new scaffold (theme, color scheme)
```

### 5. Update the server address

Open `src/context/socket-context.tsx` and replace the ngrok URL:

```ts
export const serverAddress = 'https://YOUR-ACTUAL-SERVER.com/';
```

---

## The one icon swap you need to do

The old code uses `react-native-vector-icons/MaterialIcons`. Replace all occurrences with
the Expo-bundled equivalent — **same API, just a different import**:

```diff
- import Icon from 'react-native-vector-icons/MaterialIcons';
+ import { MaterialIcons as Icon } from '@expo/vector-icons';
```

Affected files: `composants/DrawerContents` (now replaced), `composants/TopBarNav.js`,
`composants/TopBarNavRetour.js`, `composants/Filtres.js`, `composants/LoginStatePanel.js`,
`pages/ClusteredMapView.js`, and several pages under `pages/`.

---

## Navigation: what still works, what doesn't

### ✅ Works as-is
- `useNavigation()` from `@react-navigation/native` inside any screen
- `navigation.navigate('Carte')` — screen names match file names in `(app)/`
- `navigation.navigate('Signalement', { signalement: item })` — params pass through
- `route.params` inside receiving screens
- `navigation.openDrawer()` / `navigation.closeDrawer()`
- `useDrawerStatus()` from `@react-navigation/drawer`

### ⚠️ Needs updating
- `navigation.navigate('ConnexionAcceuil')` etc. from inside auth screens — these now
  live in the `(auth)` group. Use `navigation.navigate('ConnexionAcceuil')` (same name,
  just make sure the Stack screen name in `(auth)/_layout.tsx` matches).
- Any hardcoded `main.js` or `AppEntry.js` references in `package.json` — remove them.
  Expo Router sets its own entry point automatically.

---

## react-native-paper upgrade (v4 → v5)

The old project used `react-native-paper@^4`. Version 5 is required for React Native 0.71+.
Most of the API is the same. The main changes that affect this project:

```diff
// Theming
- import { useTheme } from 'react-native-paper';  // v4: returns MD2 theme
+ import { useTheme } from 'react-native-paper';  // v5: returns MD3 theme — colors moved

// The Title and Caption components were removed in v5:
- import { Title, Caption } from 'react-native-paper';
+ import { Text } from 'react-native-paper';  // use variant="titleMedium" / "bodySmall"
```

`DrawerContents.tsx` has already been rewritten to avoid these components. Check the
remaining pages that import from `react-native-paper` directly.

---

## Known deprecations to address later

| Package | Status | Replacement |
|---|---|---|
| `react-native-map-clustering` | Deprecated | Use `react-native-maps` built-in `<MapView.Animated>` with `clusteringEnabled` prop (v1.7+) |
| `react-native-status-bar-height` | Unmaintained | Use `useSafeAreaInsets().top` from `react-native-safe-area-context` |
| `figlet` in App startup | Non-critical | Remove or move to dev-only |
