// app/index.tsx
import React, { useRef, useCallback, useState, useEffect } from 'react';
import { View, TouchableOpacity, Platform, ActivityIndicator } from 'react-native';
import { WebView } from 'react-native-webview';
import { MaterialIcons } from '@expo/vector-icons';
import { BlurView } from 'expo-blur';
import { useRouter, useFocusEffect } from 'expo-router';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import Animated, {
  useSharedValue,
  useAnimatedStyle,
  withTiming,
  withDelay,
  Easing,
} from 'react-native-reanimated';
import DrawerMenu from '@/components/DrawerMenu';
import WelcomeBanner from '@/components/WelcomeBanner';

// ================================================================
// Map HTML (Leaflet + OpenStreetMap)
// ================================================================
const MAP_HTML = `
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>body,html,#map{margin:0;padding:0;width:100%;height:100%}</style>
</head>
<body>
  <div id="map"></div>
  <script>
    var map = L.map('map', { zoomControl: false }).setView([48.8566, 2.3522], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([48.8566, 2.3522]).addTo(map).bindPopup('Signalement 1');
    L.marker([48.858, 2.359]).addTo(map).bindPopup('Signalement 2');
  </script>
</body>
</html>
`;

// ================================================================
// Main Map Screen
// ================================================================
export default function MapHomeScreen() {
  const router = useRouter();
  const insets = useSafeAreaInsets();
  const webViewRef = useRef<WebView>(null);
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [showWelcome, setShowWelcome] = useState(true);

  // Show banner every time the screen regains focus
  useFocusEffect(
    useCallback(() => {
      setShowWelcome(true);
      return () => {};
    }, [])
  );

  // ----- Animate location button on mount -----
  const locOpacity = useSharedValue(0);
  const locTranslateY = useSharedValue(30);

  useEffect(() => {
    locOpacity.value = withDelay(400, withTiming(1, { duration: 500, easing: Easing.out(Easing.ease) }));
    locTranslateY.value = withDelay(400, withTiming(0, { duration: 500, easing: Easing.out(Easing.ease) }));
  }, []);

  const locAnimatedStyle = useAnimatedStyle(() => ({
    opacity: locOpacity.value,
    transform: [{ translateY: locTranslateY.value }],
  }));

  // ----- Navigation handlers -----
  const handleNavigate = useCallback(
    (route: string) => router.push(route as any),
    [router]
  );

  const handleBannerAdd = useCallback(() => {
    setShowWelcome(false);
    router.push('/ajouter-signalement');
  }, [router]);

  return (
    <View className="flex-1">
      {/* Map WebView */}
      <WebView
        ref={webViewRef}
        source={{ html: MAP_HTML }}
        style={{ flex: 1 }}
        startInLoadingState
        renderLoading={() => (
          <View className="absolute inset-0 items-center justify-center bg-white">
            <ActivityIndicator size="large" color="#22c55e" />
          </View>
        )}
        javaScriptEnabled
        domStorageEnabled
        scrollEnabled={false}
      />

      {/* Top bar */}
      <View
        className="absolute top-0 left-0 w-full flex-row items-center justify-between px-4"
        style={{ paddingTop: insets.top, height: 60 + insets.top }}
      >
        <BlurView
          tint="light"
          intensity={Platform.OS === 'android' ? 100 : 20}
          className="absolute inset-0 rounded-b-2xl border-b border-gray-200"
          style={{ backgroundColor: 'rgba(255,255,255,0.8)' }}
        />
        <TouchableOpacity onPress={() => setDrawerOpen(true)} className="z-10">
          <MaterialIcons name="menu" size={40} color="#22c55e" />
        </TouchableOpacity>
        <TouchableOpacity
          onPress={() => router.push('/signalements')}
          className="z-10"
        >
          <MaterialIcons name="description" size={35} color="#22c55e" />
        </TouchableOpacity>
      </View>

      {/* Welcome banner – matches the unified color scheme */}
      {showWelcome && (
        <WelcomeBanner
          visible={showWelcome}
          onDismiss={() => setShowWelcome(false)}
          onAdd={handleBannerAdd}
        />
      )}

      {/* Animated My Location button – green accent */}
      <Animated.View
        style={[
          {
            position: 'absolute',
            right: 16,
            bottom: showWelcome ? 280 : 110,
          },
          locAnimatedStyle,
        ]}
      >
        <TouchableOpacity
          onPress={() => {
            webViewRef.current?.injectJavaScript(
              `map.setView([48.8566, 2.3522], 15, { animate: true });`
            );
          }}
          className="w-14 h-14 bg-white rounded-full items-center justify-center shadow-lg border border-gray-200"
        >
          <MaterialIcons name="my-location" size={26} color="#22c55e" />
        </TouchableOpacity>
      </Animated.View>

      {/* Add signalement button – only visible when banner is closed */}
      {!showWelcome && (
        <View className="absolute bottom-8 right-4">
          <TouchableOpacity
            onPress={() => router.push('/ajouter-signalement')}
            className="w-14 h-14 bg-green-500 rounded-full items-center justify-center shadow-lg"
          >
            <MaterialIcons name="add" size={28} color="white" />
          </TouchableOpacity>
        </View>
      )}

      {/* Side drawer menu */}
      <DrawerMenu
        isOpen={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        onNavigate={handleNavigate}
      />
    </View>
  );
}