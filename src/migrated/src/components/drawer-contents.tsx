import React, { useContext } from 'react';
import { Text, View, StyleSheet } from 'react-native';
import {
  DrawerContentScrollView,
  DrawerItem,
  type DrawerContentComponentProps,
} from '@react-navigation/drawer';
import { useRouter } from 'expo-router';

import { AuthContext } from '@/context/auth-context';
import { projectVersion } from '@/composants/util';

export default function DrawerContents(props: DrawerContentComponentProps) {
  const router = useRouter();
  const auth = useContext(AuthContext);

  // Helper: navigate to a named screen within the (app) group
  const go = (screen: string) => router.navigate(`/(app)/${screen}` as any);

  return (
    <View style={{ flex: 1 }}>
      <DrawerContentScrollView {...props}>
        <View style={styles.drawerContent}>
          {/* ── Brand header ── */}
          <View style={styles.userInfoSection}>
            <View style={{ flexDirection: 'row', marginTop: 15 }}>
              <View style={styles.brandRow}>
                <Text style={styles.title}>DUANEKO</Text>
                <Text style={styles.caption}>v{projectVersion}</Text>
              </View>
            </View>
          </View>

          {/* ── Navigation items ── */}
          <View style={styles.drawerSection}>
            <DrawerItem
              label="Ajouter un signalement"
              onPress={() =>
                go('AjouterSignalement')
              }
            />
            <DrawerItem label="Carte"            onPress={() => go('Carte')} />
            <DrawerItem label="Signalements"     onPress={() => go('Signalements')} />
            <DrawerItem label="Programmations"   onPress={() => go('Programmations')} />
            <DrawerItem label="Actualités"       onPress={() => go('Actus')} />
            <DrawerItem label="Messages"         onPress={() => go('Messages')} />
            <DrawerItem label="Notifications"    onPress={() => go('Notifications')} />
            <DrawerItem label="Paramètres"       onPress={() => go('Parametres')} />
          </View>
        </View>
      </DrawerContentScrollView>

      {/* ── Sign-out button at the bottom ── */}
      <View style={styles.bottomSection}>
        <DrawerItem
          label="Se déconnecter"
          onPress={() => auth?.signOut()}
          labelStyle={{ color: '#e05555' }}
        />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  drawerContent: { flex: 1 },
  userInfoSection: { paddingLeft: 20 },
  brandRow: {
    marginLeft: 15,
    flexDirection: 'row',
    alignItems: 'flex-start',
    justifyContent: 'center',
  },
  title: { fontSize: 16, marginTop: 3, fontWeight: 'bold' },
  caption: { marginLeft: 5, marginTop: 12, fontSize: 12, lineHeight: 14 },
  drawerSection: { marginTop: 15 },
  bottomSection: { marginBottom: 15, borderTopColor: '#f4f4f4', borderTopWidth: 1 },
});
