// app/signalement.tsx
import React from 'react';
import { View, Text, Image, ScrollView, TouchableOpacity } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { MaterialIcons } from '@expo/vector-icons';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';

// ----------------------------------------------------------------
// Helper – category color
// ----------------------------------------------------------------
const getCategoryColor = (categorie: string) => {
  switch (categorie) {
    case 'nettoye':
      return '#22c55e';
    case 'programme':
      return '#f59e0b';
    default:
      return '#ef4444';
  }
};

// ----------------------------------------------------------------
// Component
// ----------------------------------------------------------------
export default function SignalementDetailScreen() {
  const params = useLocalSearchParams<{ sig: string }>();
  const router = useRouter();

  // Parse signalement data
  let sig: any = null;
  try {
    sig = JSON.parse(params.sig || '{}');
  } catch (e) {
    return (
      <View className="flex-1 items-center justify-center bg-gray-50">
        <Text className="text-red-500">Données invalides</Text>
      </View>
    );
  }

  const categoryColor = getCategoryColor(sig.categorie);
  const formattedDate = new Date(sig.date).toLocaleString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });

  // Generate placeholder photos if numPics > 0 (replace with real URIs later)
  const photos = sig.numPics
    ? Array.from({ length: Math.min(sig.numPics, 5) }, (_, i) => ({
        uri: `https://images.unsplash.com/photo-1675621968831-10d5117f0ad3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80`,
        id: i,
      }))
    : [];

  return (
    <View className="flex-1 bg-gray-50">
      {/* Header */}
      <View
        className="flex-row items-center px-5 bg-gray-50"
        style={{ paddingTop: 56 }} // assuming insets already handled by layout; adjust if needed
      >
        <TouchableOpacity onPress={() => router.back()} className="pr-3">
          <MaterialIcons name="arrow-back" size={24} color="#111827" />
        </TouchableOpacity>
        <Text className="text-xl font-semibold text-gray-900">
          Détails du signalement
        </Text>
      </View>

      <ScrollView
        className="flex-1 px-5 pt-6"
        contentContainerStyle={{ paddingBottom: 40 }}
        showsVerticalScrollIndicator={false}
      >
        {/* ========== AUTHOR & DATE ========== */}
        <View className="bg-white rounded-2xl p-5 shadow-sm mb-4">
          <View className="flex-row items-center mb-3">
            <View className="w-10 h-10 rounded-full bg-gray-200 items-center justify-center mr-3">
              <MaterialIcons name="person" size={20} color="#6b7280" />
            </View>
            <View className="flex-1">
              <Text className="text-lg font-semibold text-gray-900">
                @{sig.util}
              </Text>
              <Text className="text-sm text-gray-500">{formattedDate}</Text>
            </View>
          </View>
        </View>

        {/* ========== TYPE & CATEGORY ========== */}
        <View className="bg-white rounded-2xl p-5 shadow-sm mb-4">
          <Text className="text-base font-semibold text-gray-900 mb-2">
            Type de problème
          </Text>
          <View className="flex-row items-center">
            <View
              style={{
                width: 12,
                height: 12,
                borderRadius: 6,
                backgroundColor: categoryColor,
              }}
              className="mr-2"
            />
            <Text className="text-gray-800 text-base">{sig.type}</Text>
          </View>
          <View className="mt-3">
            <Text className="text-xs text-gray-400 uppercase tracking-wide mb-1">
              Catégorie
            </Text>
            <Text className="text-gray-800 capitalize">{sig.categorie}</Text>
          </View>
        </View>

        {/* ========== LOCATION ========== */}
        <View className="bg-white rounded-2xl p-5 shadow-sm mb-4">
          <Text className="text-base font-semibold text-gray-900 mb-2">
            Lieu
          </Text>
          <View className="flex-row items-center">
            <MaterialIcons name="location-on" size={16} color="#6b7280" />
            <Text className="ml-1 text-gray-800 text-base">{sig.cite}</Text>
          </View>
        </View>

        {/* ========== PHOTOS (if any) ========== */}
        {photos.length > 0 && (
          <View className="bg-white rounded-2xl p-5 shadow-sm mb-4">
            <Text className="text-base font-semibold text-gray-900 mb-3">
              Photos ({photos.length})
            </Text>
            <View className="flex-row flex-wrap">
              {photos.map((pic) => (
                <View key={pic.id} className="mr-3 mb-3">
                  <Image
                    source={{ uri: pic.uri }}
                    className="w-20 h-20 rounded-xl bg-gray-200"
                  />
                </View>
              ))}
            </View>
          </View>
        )}

        {/* ========== DELETE BUTTON (if needed) ========== */}
        {/* Uncomment and adjust if you want a delete action */}
        {/*
        <TouchableOpacity
          className="mt-4 items-center py-3 bg-red-50 rounded-2xl border border-red-200"
          onPress={() => alert('Supprimer')}
        >
          <Text className="text-red-500 font-medium">Supprimer le signalement</Text>
        </TouchableOpacity>
        */}
      </ScrollView>
    </View>
  );
}