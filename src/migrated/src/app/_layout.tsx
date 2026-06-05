import { DarkTheme, DefaultTheme, ThemeProvider } from 'expo-router';
import { Redirect, Slot, useSegments } from 'expo-router';
import { useColorScheme } from 'react-native';

import { AnimatedSplashOverlay } from '@/components/animated-icon';
import { AppProvider } from '@/context/app-context';
import { AuthProvider, useAuth } from '@/context/auth-context';

/**
 * Inner component so it can call useAuth() (which needs AuthProvider above it).
 * Handles the three navigation states:
 *   1. isLoading  → show splash overlay, render nothing underneath
 *   2. no token   → redirect to (auth) group
 *   3. has token  → redirect to (app) group
 */
function RootGuard() {
  const { loginState } = useAuth();
  const segments = useSegments();

  if (loginState.isLoading) {
    // AnimatedSplashOverlay is already rendered; return null here so no
    // flash of any screen occurs while the token is being read from storage.
    return null;
  }

  const inAuth = segments[0] === '(auth)';
  const inApp  = segments[0] === '(app)';

  if (loginState.userToken == null && !inAuth) {
    return <Redirect href="/(auth)/ConnexionAcceuil" />;
  }

  if (loginState.userToken != null && !inApp) {
    return <Redirect href="/(app)/Carte" />;
  }

  return <Slot />;
}

export default function RootLayout() {
  const colorScheme = useColorScheme();

  return (
    <AuthProvider>
      <AppProvider>
        <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
          {/* Splash plays once on first load */}
          <AnimatedSplashOverlay />
          <RootGuard />
        </ThemeProvider>
      </AppProvider>
    </AuthProvider>
  );
}
