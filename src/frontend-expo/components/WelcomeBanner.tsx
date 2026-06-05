// components/WelcomeBanner.tsx
import React, { useEffect } from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import Animated, {
  useSharedValue,
  useAnimatedStyle,
  withTiming,
  Easing,
} from 'react-native-reanimated';

interface Props {
  visible: boolean;
  onDismiss: () => void;
  onAdd: () => void;   // NEW: handler for the "Signaler" button
}

export default function WelcomeBanner({ visible, onDismiss, onAdd }: Props) {
  const opacity = useSharedValue(0);
  const translateY = useSharedValue(50);

  useEffect(() => {
    if (visible) {
      opacity.value = withTiming(1, { duration: 300, easing: Easing.out(Easing.ease) });
      translateY.value = withTiming(0, { duration: 300, easing: Easing.out(Easing.ease) });
    } else {
      opacity.value = withTiming(0, { duration: 200 });
      translateY.value = withTiming(50, { duration: 200 });
    }
  }, [visible]);

  const animatedStyle = useAnimatedStyle(() => ({
    opacity: opacity.value,
    transform: [{ translateY: translateY.value }],
  }));

  return (
    <Animated.View
      style={animatedStyle}
      className="absolute bottom-12 left-4 right-4 bg-white/90 rounded-2xl px-4 py-6 shadow-lg border border-gray-200"
    >
      <View className="flex-row justify-between items-start mb-3">
        <View className="flex-1 mr-3">
          <Text className="text-lg font-bold text-gray-800">Bienvenue sur Duaneko !</Text>
          <Text className="text-sm text-gray-500 mt-1">
            Signalez les dépôts sauvages autour de vous et aidez à garder votre quartier propre.
          </Text>
        </View>
        <TouchableOpacity onPress={onDismiss}>
          <MaterialIcons name="close" size={22} color="#999" />
        </TouchableOpacity>
      </View>

      {/* "Signaler" button inside the banner */}
      <TouchableOpacity
        onPress={onAdd}
        className="bg-green-500 rounded-xl py-3 items-center justify-center mt-5"
      >
        <Text className="text-white font-semibold text-base">Signaler</Text>
      </TouchableOpacity>
    </Animated.View>
  );
}