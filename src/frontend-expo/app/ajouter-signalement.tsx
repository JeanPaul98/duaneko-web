// app/ajouter-signalement.tsx
import React, { useState } from 'react';
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  Image,
  Alert,
  TextInput,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useRouter } from 'expo-router';
import { MaterialIcons } from '@expo/vector-icons';
import * as ImagePicker from 'expo-image-picker';

// ----------------------------------------------------------------
// Constants
// ----------------------------------------------------------------
const REPORT_TYPES = ['Dépotoirs sauvages', 'Caniveaux bouchés'] as const;
type ReportType = (typeof REPORT_TYPES)[number];

// Urgency palette – yellowish‑orange → red
const URGENCY_COLORS = [
  '',                // 0 unused
  'bg-amber-300',    // 1 – yellowish‑orange
  'bg-amber-500',    // 2 – amber
  'bg-orange-500',   // 3 – orange
  'bg-red-400',      // 4 – light red
  'bg-red-600',      // 5 – deep red
];

// ----------------------------------------------------------------
// Component
// ----------------------------------------------------------------
export default function AjouterSignalementScreen() {
  const router = useRouter();
  const insets = useSafeAreaInsets();

  const [reportType, setReportType] = useState<ReportType>('Dépotoirs sauvages');
  const [urgency, setUrgency] = useState(1);
  const [comment, setComment] = useState('');
  const [images, setImages] = useState<string[]>([]);

  // Image pickers
  const pickImage = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Permission refusée', "Accès aux photos refusé.");
      return;
    }
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'],
      allowsMultipleSelection: true,
      quality: 0.7,
      selectionLimit: 5 - images.length,
    });
    if (!result.canceled) {
      const uris = result.assets.map((a) => a.uri);
      setImages((prev) => [...prev, ...uris].slice(0, 5));
    }
  };

  const takePhoto = async () => {
    const { status } = await ImagePicker.requestCameraPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Permission refusée', 'Accès à la caméra refusé.');
      return;
    }
    const result = await ImagePicker.launchCameraAsync({ quality: 0.7 });
    if (!result.canceled) {
      setImages((prev) => [...prev, result.assets[0].uri].slice(0, 5));
    }
  };

  const removeImage = (index: number) => {
    setImages((prev) => prev.filter((_, i) => i !== index));
  };

  const handleSubmit = () => {
    console.log('Signalement ajouté :', {
      type: reportType,
      urgence: urgency,
      commentaire: comment,
      photos: images.length,
    });
    Alert.alert('Succès', 'Signalement ajouté.');
    router.back();
  };

  return (
    <View className="flex-1 bg-gray-50">
      {/* Header – back arrow + title + Signal button on the right */}
      <View
        className="flex-row items-center justify-between px-5 bg-gray-50"
        style={{ paddingTop: insets.top, height: 56 + insets.top }}
      >
        {/* Left side – back button + title */}
        <View className="flex-row items-center flex-1">
          <TouchableOpacity onPress={() => router.back()} className="pr-3">
            <MaterialIcons name="arrow-back" size={24} color="#111827" />
          </TouchableOpacity>
          <Text className="text-xl font-semibold text-gray-900" numberOfLines={1}>
            Nouveau signalement
          </Text>
        </View>

        {/* Right side – Signal button */}
        <TouchableOpacity
          onPress={handleSubmit}
          className="bg-green-500 rounded-xl px-4 py-2 active:bg-green-700"
        >
          <Text className="text-white text-sm font-semibold">Signaler</Text>
        </TouchableOpacity>
      </View>

      <ScrollView
        className="flex-1 px-5"
        keyboardShouldPersistTaps="handled"
        showsVerticalScrollIndicator={false}
        contentContainerStyle={{ paddingBottom: 60 }}
      >
        {/* ========== TYPE DE PROBLÈME ========== */}
        <View className="mt-6 bg-white rounded-2xl p-5 shadow-sm">
          <Text className="text-base font-semibold text-gray-900 mb-4">
            Type de problème
          </Text>
          <View className="flex-row space-x-3">
            {REPORT_TYPES.map((type) => {
              const active = reportType === type;
              return (
                <TouchableOpacity
                  key={type}
                  onPress={() => setReportType(type)}
                  className={`flex-1 py-3 rounded-xl items-center justify-center border ${
                    active
                      ? 'border-green-500 bg-green-500'
                      : 'border-gray-200 bg-white'
                  }`}
                >
                  <Text
                    className={`text-sm font-medium ${
                      active ? 'text-white' : 'text-gray-600'
                    }`}
                  >
                    {type}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </View>
        </View>

        {/* ========== NIVEAU D'URGENCE ========== */}
        <View className="mt-4 bg-white rounded-2xl p-5 shadow-sm">
          <Text className="text-base font-semibold text-gray-900 mb-3">
            Niveau d'urgence
          </Text>
          <View className="flex-row justify-between items-center">
            {[1, 2, 3, 4, 5].map((level) => {
              const active = urgency >= level;
              return (
                <TouchableOpacity
                  key={level}
                  onPress={() => setUrgency(level)}
                  className="items-center"
                >
                  <View
                    className={`w-10 h-10 rounded-full items-center justify-center ${
                      active ? URGENCY_COLORS[level] : 'bg-gray-200'
                    }`}
                  >
                    <Text
                      className={`text-sm font-semibold ${
                        active ? 'text-white' : 'text-gray-500'
                      }`}
                    >
                      {level}
                    </Text>
                  </View>
                  <Text className="text-xs text-gray-400 mt-1">
                    {level === 1 ? 'Faible' : level === 5 ? 'Urgent' : ''}
                  </Text>
                </TouchableOpacity>
              );
            })}
          </View>
        </View>

        {/* ========== COMMENTAIRE ========== */}
        <View className="mt-4 bg-white rounded-2xl p-5 shadow-sm">
          <Text className="text-base font-semibold text-gray-900 mb-3">
            Commentaire
          </Text>
          <View className="bg-gray-50 rounded-xl border border-gray-200 px-4 py-3">
            <TextInput
              className="text-gray-800 text-base h-28"
              style={{ textAlignVertical: 'top' }}
              multiline
              numberOfLines={5}
              placeholder="Décrivez ce que vous avez observé…"
              placeholderTextColor="#9ca3af"
              value={comment}
              onChangeText={setComment}
            />
          </View>
        </View>

        {/* ========== PHOTOS ========== */}
        <View className="mt-4 bg-white rounded-2xl p-5 shadow-sm">
          <Text className="text-base font-semibold text-gray-900 mb-3">
            Photos ({images.length}/5)
          </Text>
          <View className="flex-row flex-wrap">
            {images.map((uri, index) => (
              <View key={index} className="relative mr-3 mb-3">
                <Image
                  source={{ uri }}
                  className="w-20 h-20 rounded-xl bg-gray-200"
                />
                <TouchableOpacity
                  onPress={() => removeImage(index)}
                  className="absolute -top-2 -right-2 w-5 h-5 bg-gray-200 rounded-full items-center justify-center"
                >
                  <MaterialIcons name="close" size={14} color="#4b5563" />
                </TouchableOpacity>
              </View>
            ))}
            {images.length < 5 && (
              <>
                <TouchableOpacity
                  onPress={pickImage}
                  className="w-20 h-20 mr-3 mb-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 items-center justify-center"
                >
                  <MaterialIcons name="photo-library" size={24} color="#22c55e" />
                </TouchableOpacity>
                <TouchableOpacity
                  onPress={takePhoto}
                  className="w-20 h-20 mr-3 mb-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 items-center justify-center"
                >
                  <MaterialIcons name="camera-alt" size={24} color="#22c55e" />
                </TouchableOpacity>
              </>
            )}
          </View>
        </View>
      </ScrollView>
    </View>
  );
}