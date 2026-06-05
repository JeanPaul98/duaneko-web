import * as React from 'react';
import * as SecureStore from 'expo-secure-store';

import { serverAddress } from './socket-context';

// ─── Types ────────────────────────────────────────────────────────────────────

type AuthState = {
  user: string | null;
  isLoading: boolean;
  isSignout: boolean;
  userToken: string | null;
};

type AuthAction =
  | { type: 'RESTORE_TOKEN'; token: string | null; user: string | null }
  | { type: 'SIGN_IN'; token: string; user: string }
  | { type: 'SIGN_OUT' };

type SignInData = { username: string; password: string; saveToken: boolean };
type SignUpData = { username: string; tel: string; password: string; dateJoined: string };

export type AuthContextValue = {
  loginState: AuthState;
  signIn: (data: SignInData) => Promise<void>;
  signOut: () => Promise<void>;
  signUp: (data: SignUpData) => Promise<void>;
};

// ─── Context ──────────────────────────────────────────────────────────────────

export const AuthContext = React.createContext<AuthContextValue | null>(null);

export function useAuth(): AuthContextValue {
  const ctx = React.useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');
  return ctx;
}

// ─── Reducer (ported directly from App.js) ────────────────────────────────────

function authReducer(prevState: AuthState, action: AuthAction): AuthState {
  switch (action.type) {
    case 'RESTORE_TOKEN':
      return { ...prevState, userToken: action.token, user: action.user, isLoading: false };
    case 'SIGN_IN':
      return { ...prevState, isSignout: false, user: action.user, userToken: action.token };
    case 'SIGN_OUT':
      return { ...prevState, isSignout: true, userToken: null };
  }
}

// ─── Provider ─────────────────────────────────────────────────────────────────

export function AuthProvider({ children }: React.PropsWithChildren) {
  const [state, dispatch] = React.useReducer(authReducer, {
    user: null,
    isLoading: true,
    isSignout: false,
    userToken: null,
  });

  // Restore persisted token on startup
  React.useEffect(() => {
    (async () => {
      try {
        const token = await SecureStore.getItemAsync('userToken');
        const user = await SecureStore.getItemAsync('user');
        dispatch({ type: 'RESTORE_TOKEN', token, user });
      } catch {
        dispatch({ type: 'RESTORE_TOKEN', token: null, user: null });
      }
    })();
  }, []);

  const authContext = React.useMemo<AuthContextValue>(
    () => ({
      loginState: state,

      signIn: async (data) => {
        if (data.saveToken) {
          await SecureStore.setItemAsync('userToken', 'saved-auth-token');
          await SecureStore.setItemAsync('user', data.username);
          dispatch({ type: 'SIGN_IN', token: 'saved-auth-token', user: data.username });
        } else {
          dispatch({ type: 'SIGN_IN', token: 'unsavedToken123', user: data.username });
        }
      },

      signOut: async () => {
        await SecureStore.deleteItemAsync('user');
        await SecureStore.deleteItemAsync('userToken');
        dispatch({ type: 'SIGN_OUT' });
      },

      signUp: async (data) => {
        try {
          const res = await fetch(serverAddress + 'inscription', {
            method: 'POST',
            headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({
              username: data.username,
              tel: data.tel,
              password: data.password,
              token: 'token',
              privilege: 'civil',
              dateJoined: data.dateJoined,
            }),
          });
          const text = await res.text();
          console.log(text);
          dispatch({ type: 'SIGN_IN', token: 'dummy-auth-token', user: data.tel });
        } catch {
          console.log('server error');
        }
      },
    }),
    [state]
  );

  return <AuthContext.Provider value={authContext}>{children}</AuthContext.Provider>;
}
