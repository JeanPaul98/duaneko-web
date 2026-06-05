// app/programmations.tsx
import React from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useRouter } from 'expo-router';
import { MaterialIcons } from '@expo/vector-icons';

export default function ProgrammationsScreen() {
  const router = useRouter();
  const insets = useSafeAreaInsets();

  return (
    <View className="flex-1 bg-white">
      <View
        className="flex-row items-center px-4 bg-gray-100 border-b border-gray-200"
        style={{ paddingTop: insets.top, height: 60 + insets.top }}
      >
        <TouchableOpacity onPress={() => router.back()}>
          <MaterialIcons name="arrow-back" size={24} color="black" />
        </TouchableOpacity>
        <Text className="ml-4 text-xl font-bold">Programmations</Text>
      </View>
      <View className="flex-1 items-center justify-center">
        <Text className="text-gray-500">Programmations à venir</Text>
      </View>
    </View>
  );
}