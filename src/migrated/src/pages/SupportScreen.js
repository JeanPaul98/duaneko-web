import React, { useState, useEffect, } from 'react';
import { Text, View, Button, ActivityIndicator, } from 'react-native';
import { StatusBar } from 'expo-status-bar';

export default function SupportScreen({ navigation }) {
    return (
      <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
              <StatusBar style="dark" />
              <Text style={{ fontSize: 18, marginBottom: 10 }}> Support</Text>
        </View>
      );
  }