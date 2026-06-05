// components/Signalement.tsx
import React from 'react';
import { View, Text, Image, TouchableOpacity } from 'react-native';
import { useRouter } from 'expo-router';
import relativeTime from 'dayjs/plugin/relativeTime';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
import { MaterialIcons } from '@expo/vector-icons';
// import { serverAddress } from '@/composants/SocketContext'; // adjust path

// ----------------------------------------------------------------
// Setup dayjs once (you could move this to _layout.tsx for global use)
// ----------------------------------------------------------------
dayjs.extend(relativeTime);
dayjs.locale('fr');

// ----------------------------------------------------------------
// Types
// ----------------------------------------------------------------
export interface SignalementData {
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

interface Props {
  sig: SignalementData;
  distance: number;   // in km
  emplacement: string;
}

// ----------------------------------------------------------------
// Component
// ----------------------------------------------------------------
export default function Signalement({ sig, distance, emplacement }: Props) {
  const router = useRouter();

  // Image URI logic (same as before)
//   const picsUri =
//     sig.util !== 'andee777bot'
//       ? serverAddress + 'photo/' + sig.date + sig.util + '_0'
//       : serverAddress + 'photo/image';

  // Helper to pick pin icon colour based on category
  const getPinColor = () => {
    switch (sig.categorie) {
      case 'programme':
        return '#FFD700';
      case 'nettoye':
        return 'rgb(66, 182, 64)';
      default:
        return 'red';
    }
  };

  return (
    <TouchableOpacity
      onPress={() =>
        router.push({
          pathname: '/signalement',
          params: { sig: JSON.stringify(sig) },
        })
      }
      className="h-28 w-full bg-gray-100 flex-row items-center justify-between border-b border-gray-200 px-3"
    >
      {/* Photo */}
      <View className="h-[75px] w-[75px] rounded-full items-center justify-center">
        {/* <Image
          className="h-full w-full rounded-full bg-gray-400"
          source={{
            uri: 'https://images.unsplash.com/photo-1675621968831-10d5117f0ad3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80',
          }}
        /> */}
        <View
          className="h-full w-full rounded-full bg-gray-300"
        />
      </View>

      {/* Details */}
      <View className="flex-1 h-full justify-between ml-5 py-3">
        {/* Line 1: Distance & type */}
        <View className="w-full flex-row justify-between items-center">
          <Text className="text-xl font-semibold text-black">{distance} km</Text>
          <Text
            numberOfLines={1}
            className="text-xs font-semibold text-gray-400 max-w-[60%] pl-5"
          >
            {sig.type}
          </Text>
        </View>

        {/* Line 2: Location pin & emplacement */}
        <View className="w-full flex-row items-center">
          <MaterialIcons name="place" color={getPinColor()} size={20} />
          <Text className="text-xs text-gray-400 ml-1">{emplacement}</Text>
        </View>

        {/* Line 3: User & date */}
        <View className="w-full flex-row justify-between items-center">
          <View className="flex-row items-center">
            <MaterialIcons name="person" color="rgb(191, 191, 191)" size={15} />
            <Text className="text-xs text-gray-400 ml-1">@{sig.util}</Text>
          </View>
          <Text className="text-xs text-gray-400">{dayjs(sig.date).fromNow()}</Text>
        </View>
      </View>
    </TouchableOpacity>
  );
}