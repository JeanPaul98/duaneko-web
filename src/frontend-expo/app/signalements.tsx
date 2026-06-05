// app/signalements.tsx
import React from 'react';
import { View, Text, FlatList, Image, TouchableOpacity } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useRouter } from 'expo-router';
import { MaterialIcons } from '@expo/vector-icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/fr';

// ----------------------------------------------------------------
// Setup dayjs
// ----------------------------------------------------------------
dayjs.extend(relativeTime);
dayjs.locale('fr');

// ----------------------------------------------------------------
// Types
// ----------------------------------------------------------------
interface SignalementData {
  id: string;
  latitude: number;
  longitude: number;
  cite: string;
  util: string;
  date: string;
  type: string;
  categorie: 'signale' | 'programme' | 'nettoye';
  numPics: number;
}

// ----------------------------------------------------------------
// Dummy data (replace with real data later)
// ----------------------------------------------------------------
const DUMMY_SIGNALEMENTS: SignalementData[] = [
  {
    id: '1',
    latitude: 48.8566,
    longitude: 2.3522,
    cite: 'Paris',
    util: 'testuser',
    date: new Date().toISOString(),
    type: 'Dépotoirs sauvages',
    categorie: 'signale',
    numPics: 0,
  },
  {
    id: '2',
    latitude: 48.858,
    longitude: 2.359,
    cite: 'Lyon',
    util: 'andee777bot',
    date: '2025-01-01T12:00:00Z',
    type: 'Caniveaux bouchés',
    categorie: 'programme',
    numPics: 2,
  },
  {
    id: '3',
    latitude: 45.5,
    longitude: -73.6,
    cite: 'Montréal',
    util: 'cleaner123',
    date: '2025-06-01T08:30:00Z',
    type: 'Dépotoirs sauvages',
    categorie: 'nettoye',
    numPics: 1,
  },
  {
    id: '4',
    latitude: 44.0,
    longitude: 4.0,
    cite: 'Avignon',
    util: 'marie',
    date: new Date(Date.now() - 3600000).toISOString(),
    type: 'Caniveaux bouchés',
    categorie: 'signale',
    numPics: 3,
  },
  {
    id: '5',
    latitude: 46.0,
    longitude: 2.0,
    cite: 'Limoges',
    util: 'agent007',
    date: new Date(Date.now() - 86400000).toISOString(),
    type: 'Dépotoirs sauvages',
    categorie: 'programme',
    numPics: 0,
  },
];

// ----------------------------------------------------------------
// Helpers
// ----------------------------------------------------------------
const getCategoryColor = (categorie: string) => {
  switch (categorie) {
    case 'nettoye':
      return '#22c55e'; // green-500
    case 'programme':
      return '#f59e0b'; // amber-500
    default:
      return '#ef4444'; // red-500
  }
};

const FALLBACK_IMAGE =
  'https://images.unsplash.com/photo-1631197344782-e66f0c665ba9?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';

// ----------------------------------------------------------------
// Component
// ----------------------------------------------------------------
export default function SignalementsScreen() {
  const router = useRouter();
  const insets = useSafeAreaInsets();

  const renderItem = ({ item }: { item: SignalementData }) => {
    // Generate a mock distance (replace with real calculation later)
    const distance = (Math.random() * 10).toFixed(1);

    return (
      <TouchableOpacity
        onPress={() =>
          router.push({
            pathname: '/signalement',
            params: { sig: JSON.stringify(item) },
          })
        }
        className="mx-4 mb-3 bg-white rounded-2xl shadow-sm overflow-hidden"
        activeOpacity={0.9}
      >
        <View className="flex-row p-4">
          {/* Thumbnail */}
          <Image
            source={{
              uri:
                item.numPics !== 0
                  ? FALLBACK_IMAGE // replace with real picsUri when server is ready
                  : FALLBACK_IMAGE,
            }}
            className="w-16 h-16 rounded-xl bg-gray-200"
          />

          {/* Details */}
          <View className="flex-1 ml-3 justify-center">
            {/* Distance & type */}
            <View className="flex-row justify-between items-baseline">
              <Text className="text-base font-bold text-gray-900">
                {distance} km
              </Text>
              <Text
                className="text-xs text-gray-400 max-w-[60%]"
                numberOfLines={1}
              >
                {item.type}
              </Text>
            </View>

            {/* Location with category dot */}
            <View className="flex-row items-center mt-1.5">
              <View
                style={{
                  width: 8,
                  height: 8,
                  borderRadius: 4,
                  backgroundColor: getCategoryColor(item.categorie),
                }}
                className="mr-1.5"
              />
              <Text className="text-sm text-gray-500">{item.cite}</Text>
            </View>

            {/* User & relative time */}
            <View className="flex-row justify-between items-center mt-1">
              <Text className="text-xs text-gray-400">@{item.util}</Text>
              <Text className="text-xs text-gray-400">
                {dayjs(item.date).fromNow()}
              </Text>
            </View>
          </View>
        </View>
      </TouchableOpacity>
    );
  };

  return (
    <View className="flex-1 bg-gray-50">
      {/* Header */}
      <View
        className="flex-row items-center justify-between px-5 bg-gray-50"
        style={{ paddingTop: insets.top, height: 56 + insets.top }}
      >
        <View className="flex-row items-center flex-1">
          <TouchableOpacity onPress={() => router.back()} className="pr-3">
            <MaterialIcons name="arrow-back" size={24} color="#111827" />
          </TouchableOpacity>
          <Text className="text-xl font-semibold text-gray-900">
            Signalements
          </Text>
        </View>
        <TouchableOpacity
          onPress={() => router.push('/ajouter-signalement')}
          className="bg-green-500 rounded-xl px-3 py-2 active:bg-green-700"
        >
          <MaterialIcons name="add" size={20} color="white" />
        </TouchableOpacity>
      </View>

      {/* List */}
      <FlatList
        data={DUMMY_SIGNALEMENTS}
        keyExtractor={(item) => item.id}
        renderItem={renderItem}
        contentContainerStyle={{ paddingTop: 12, paddingBottom: 40 }}
        showsVerticalScrollIndicator={false}
        ItemSeparatorComponent={() => <View className="h-0" />} // no extra gap, card margin handles it
      />
    </View>
  );
}