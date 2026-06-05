// components/DrawerMenu.tsx
import React, { useEffect } from 'react';
import { View, Text, TouchableOpacity, Pressable } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import Animated, {
  useSharedValue,
  useAnimatedStyle,
  withTiming,
  Easing,
} from 'react-native-reanimated';

// ------------------------------------------------------------------
// Menu items configuration (you can move this to a separate file if needed)
// ------------------------------------------------------------------
const MENU_ITEMS = [
  { label: 'Signalements', icon: 'description', route: '/signalements' },
  { label: 'Programmations', icon: 'event-note', route: '/programmations' },
  { label: 'Actualités', icon: 'article', route: '/actualites' },
  { label: 'Profil', icon: 'person', route: '/profil' },
  { label: 'Paramètres', icon: 'settings', route: '/parametres' },
  { label: 'Messages', icon: 'message', route: '/messages' },
  { label: 'Notifications', icon: 'notifications', route: '/notifications' },
  { label: 'Utilisateurs', icon: 'group', route: '/utilisateurs' },
];

// ------------------------------------------------------------------
// Props
// ------------------------------------------------------------------
interface DrawerProps {
  isOpen: boolean;
  onClose: () => void;
  onNavigate: (route: string) => void;
}

// ------------------------------------------------------------------
// Component
// ------------------------------------------------------------------
export default function DrawerMenu({ isOpen, onClose, onNavigate }: DrawerProps) {
  const insets = useSafeAreaInsets();
  const translateX = useSharedValue(-300);
  const opacity = useSharedValue(0);

  useEffect(() => {
    if (isOpen) {
      translateX.value = withTiming(0, { duration: 250, easing: Easing.out(Easing.ease) });
      opacity.value = withTiming(0.5, { duration: 250 });
    } else {
      translateX.value = withTiming(-300, { duration: 200 });
      opacity.value = withTiming(0, { duration: 200 });
    }
  }, [isOpen]);

  const drawerStyle = useAnimatedStyle(() => ({
    transform: [{ translateX: translateX.value }],
  }));

  const backdropStyle = useAnimatedStyle(() => ({
    opacity: opacity.value,
  }));

  return (
    <View className="absolute inset-0 z-50" pointerEvents={isOpen ? 'auto' : 'none'}>
      {/* Backdrop */}
      <Animated.View style={[backdropStyle, { backgroundColor: 'black', flex: 1 }]}>
        <Pressable className="flex-1" onPress={onClose} />
      </Animated.View>

      {/* Drawer panel */}
      <Animated.View
        style={[
          {
            position: 'absolute',
            left: 0,
            top: 0,
            bottom: 0,
            width: 280,
            backgroundColor: 'white',
            paddingTop: insets.top,
          },
          drawerStyle,
        ]}
      >
        <View className="flex-1 bg-white pt-6 px-5">
          {/* Header */}
          <View className="flex-row items-center justify-between mb-8">
            <Text className="text-2xl font-bold text-gray-800">Menu</Text>
            <TouchableOpacity onPress={onClose}>
              <MaterialIcons name="close" size={28} color="#666" />
            </TouchableOpacity>
          </View>

          {/* Menu items */}
          {MENU_ITEMS.map((item) => (
            <TouchableOpacity
              key={item.label}
              className="flex-row items-center py-4 border-b border-gray-100"
              onPress={() => {
                onClose();
                setTimeout(() => onNavigate(item.route), 50);
              }}
            >
              <MaterialIcons name={item.icon as any} size={22} color="#444" />
              <Text className="ml-4 text-base font-medium text-gray-700">{item.label}</Text>
            </TouchableOpacity>
          ))}
        </View>
      </Animated.View>
    </View>
  );
}