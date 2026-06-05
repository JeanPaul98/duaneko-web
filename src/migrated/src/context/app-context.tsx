import * as React from 'react';
import * as Location from 'expo-location';

import { socket } from './socket-context';

// ─── Types ────────────────────────────────────────────────────────────────────

type LocationData = {
  coords: {
    latitude: number;
    longitude: number;
    latitudeDelta?: number;
    longitudeDelta?: number;
  };
};

type AppState = {
  location: LocationData;
  locationAccuracy: number;
  signalements: any[] | null;
  users: any[] | null;
  chatMsg: any[] | null;
  programmations: any[] | null;
  actus: any[] | null;
  count: any | null;
};

type AppAction =
  | { type: 'SET_LOCATION'; loc: LocationData }
  | { type: 'SET_LOCATION_ACCURACY'; accuracy: number }
  | { type: 'SET_USERS'; users: any[] }
  | { type: 'SET_CHAT_MESSAGES'; chatMsg: any[] }
  | { type: 'SET_SIGNALEMENTS'; signalements: any[] }
  | { type: 'SET_ACTUS'; actus: any[] }
  | { type: 'SET_PROGRAMMATIONS'; programmations: any[] }
  | { type: 'SET_COUNT'; count: any };

export type AppContextValue = {
  appState: AppState;
  setLocation: (loc: LocationData) => void;
  setLocationAccuracy: (accuracy: number) => void;
};

// ─── Context ──────────────────────────────────────────────────────────────────

export const AppContext = React.createContext<AppContextValue | null>(null);

export function useAppContext(): AppContextValue {
  const ctx = React.useContext(AppContext);
  if (!ctx) throw new Error('useAppContext must be used within AppProvider');
  return ctx;
}

// ─── Reducer ──────────────────────────────────────────────────────────────────

const DEFAULT_LOCATION: LocationData = {
  coords: {
    latitude: 45.4616589284805,
    longitude: -75.76868011127924,
    latitudeDelta: 0.2,
    longitudeDelta: 0.2,
  },
};

function appReducer(prevState: AppState, action: AppAction): AppState {
  switch (action.type) {
    case 'SET_LOCATION':            return { ...prevState, location: action.loc };
    case 'SET_LOCATION_ACCURACY':   return { ...prevState, locationAccuracy: action.accuracy };
    case 'SET_USERS':               return { ...prevState, users: action.users };
    case 'SET_CHAT_MESSAGES':       return { ...prevState, chatMsg: action.chatMsg };
    case 'SET_SIGNALEMENTS':        return { ...prevState, signalements: action.signalements };
    case 'SET_ACTUS':               return { ...prevState, actus: action.actus };
    case 'SET_PROGRAMMATIONS':      return { ...prevState, programmations: action.programmations };
    case 'SET_COUNT':               return { ...prevState, count: action.count };
  }
}

// ─── Provider ─────────────────────────────────────────────────────────────────

export function AppProvider({ children }: React.PropsWithChildren) {
  const [state, dispatch] = React.useReducer(appReducer, {
    location: DEFAULT_LOCATION,
    locationAccuracy: 5,
    signalements: null,
    users: null,
    chatMsg: null,
    programmations: null,
    actus: null,
    count: null,
  });

  // ── Socket listeners ──────────────────────────────────────────────────────
  React.useEffect(() => {
    socket.on('signalements',  (msg) => dispatch({ type: 'SET_SIGNALEMENTS', signalements: msg }));
    socket.on('programmations',(msg) => dispatch({ type: 'SET_PROGRAMMATIONS', programmations: msg }));
    socket.on('actus',         (msg) => dispatch({ type: 'SET_ACTUS', actus: msg }));
    socket.on('utils',         (msg) => dispatch({ type: 'SET_USERS', users: msg }));
    socket.on('count',         (msg) => dispatch({ type: 'SET_COUNT', count: msg }));
    socket.on('chat message',  (msg) => dispatch({ type: 'SET_CHAT_MESSAGES', chatMsg: msg }));
    socket.on('picUploaded',   (msg) => console.log('-> socket.on(picUploaded)\n', msg));

    return () => {
      socket.off('signalements');
      socket.off('programmations');
      socket.off('actus');
      socket.off('utils');
      socket.off('count');
      socket.off('chat message');
      socket.off('picUploaded');
    };
  }, []);

  // ── Location ──────────────────────────────────────────────────────────────
  React.useEffect(() => {
    (async () => {
      const { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        console.log('Location permission denied');
        return;
      }
      // Set a quick approximate location first
      dispatch({
        type: 'SET_LOCATION',
        loc: { coords: { latitude: 45.46174942895201, longitude: -75.76859545498432, latitudeDelta: 0.2, longitudeDelta: 0.2 } },
      });
      // Then get the precise fix
      Location.getCurrentPositionAsync({ accuracy: state.locationAccuracy })
        .then((loc) => dispatch({ type: 'SET_LOCATION', loc }))
        .catch(() => console.log('*** Location error, keeping default'));
    })();
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const value = React.useMemo<AppContextValue>(
    () => ({
      appState: state,
      setLocation: (loc) => dispatch({ type: 'SET_LOCATION', loc }),
      setLocationAccuracy: (accuracy) => dispatch({ type: 'SET_LOCATION_ACCURACY', accuracy }),
    }),
    [state]
  );

  return <AppContext.Provider value={value}>{children}</AppContext.Provider>;
}
