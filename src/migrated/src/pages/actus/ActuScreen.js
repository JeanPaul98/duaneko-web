import React, { useState, useEffect, useCallback, useRef } from 'react';
import { Text, StyleSheet, View, TouchableOpacity, Image, Linking, ScrollView, Dimensions  } from 'react-native';
import { StatusBar } from 'expo-status-bar';
import Constants from 'expo-constants';
import MapView, { Marker } from 'react-native-maps';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { Button, Card, Title } from 'react-native-paper';
import dayjs from 'dayjs';
import localefr from 'dayjs/locale/fr';
import relativeTime from 'dayjs/plugin/relativeTime';
import TopBarNavRetour from '../../composants/TopBarNavRetour';
import { WebView } from 'react-native-webview';

export default function ActuScreen({ navigation, route  }) {
    const url1 = "https://www.google.com/maps/dir//" + 
      route.params.sig.latitude + "," + 
      route.params.sig.longitude;

    const handlePress = useCallback(async () => {
      // Checking if the link is supported for links with custom URL scheme.
      const supported = await Linking.canOpenURL(url1);
        if (supported) {
          // Opening the link with some app, if the URL scheme is "http" the web link should be opened
          // by some browser in the mobile
          await Linking.openURL(url1);
        } else {
          Alert.alert(`Don't know how to open this URL: ${url1}`);
        }
    }, [url1]);

    const mapRef = useRef();

    dayjs.extend(relativeTime);
    dayjs.locale('fr');

    return (
      <View style={{ flex:1, backgroundColor:"white"}}>
        <StatusBar style="dark" />
        <TopBarNavRetour fromRoute={'Actus'}/>
        <View style={{  height: 80, backgroundColor: 'white'}}></View>
        <WebView 
          style={styles.container}
          source={{ uri: route.params.sig.source }}
        />
      </View>
    )
  };

  const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'white',
    },
});