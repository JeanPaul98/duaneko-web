import { Drawer } from 'expo-router/drawer';

import DrawerContents from '@/components/drawer-contents';

/**
 * Drawer navigator – direct replacement for the old DrawerNav.js.
 *
 * Screen names match the old navigator's screen names so that any existing
 * navigation.navigate('Carte') calls still resolve correctly via
 * React Navigation's compatibility layer built into Expo Router.
 *
 * Screens NOT shown in the drawer sidebar (Signalement, SignalementPicsScreen,
 * etc.) are still registered here so they can be navigated to programmatically.
 */
export default function AppLayout() {
  return (
    <Drawer
      initialRouteName="Carte"
      defaultStatus="closed"
      drawerContent={(props) => <DrawerContents {...props} />}
      screenOptions={{ swipeEnabled: false, headerShown: false }}
    >
      {/* ── Drawer-visible screens ── */}
      <Drawer.Screen name="Carte" />
      <Drawer.Screen name="AjouterSignalement" />
      <Drawer.Screen name="Signalements" />
      <Drawer.Screen name="Programmations" />
      <Drawer.Screen name="Actus" />
      <Drawer.Screen name="Messages" />
      <Drawer.Screen name="Notifications" />
      <Drawer.Screen name="Parametres" />
      <Drawer.Screen name="GestionUtilisateurs" />

      {/* ── Detail / modal screens (navigated to, not in sidebar) ── */}
      <Drawer.Screen name="Signalement" />
      <Drawer.Screen name="SignalementPicsScreen" />
      <Drawer.Screen name="Programmation" />
      <Drawer.Screen name="AjouterProgrammation" />
      <Drawer.Screen name="Actu" />
      <Drawer.Screen name="AjouterActu" />
      <Drawer.Screen name="Camera" />
      <Drawer.Screen name="Support" />
    </Drawer>
  );
}
