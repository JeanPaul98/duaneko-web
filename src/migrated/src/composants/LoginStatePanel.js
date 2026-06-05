import React, { useEffect, useState, useRef, useContext } from 'react'
import {
    View, Image, StyleSheet, 
    Dimensions, TouchableOpacity,
    LayoutAnimation, Text,
    useWindowDimensions
  } from 'react-native';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { AuthContext } from './AuthContext';

export default function LoginStatePanel(props) {
    var { loginState } = useContext(AuthContext);
    const { height, width } = useWindowDimensions();

    return (
        <View style={{ 
            // borderWidth:1, borderColor:'red', 
            position: 'absolute',
            width: 250,
            height: 100,
            zIndex: 5,
            top: 100,
            left: 20,
            backgroundColor: '#f4f4f4ef',
            borderRadius: 5,
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'flex-start',
            paddingHorizontal: 15,
            }}>
            <Text style={{marginBottom:10}}>Bienvenue {loginState.user}!</Text>
            <Text>Voici vos coordonnées gps : </Text> 
            <Text  style={{width:'100%', textAlign:'left'}}>
                {/* {JSON.stringify(global.location)} */}
                {(loginState.location != null) ? loginState.location.coords.latitude.toFixed(9) : ""}
                {", "} 
                {(loginState.location != null) ? loginState.location.coords.longitude.toFixed(9) : ""}
            </Text>          
            <Text style={{width:'100%', textAlign:'right'}}>[Précision: {loginState.locationAccuracy}]</Text>
        </View>
    );
}