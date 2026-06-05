import React, { useEffect, useState, useRef, useContext } from 'react'
import { StyleSheet, TouchableOpacity } from 'react-native';
import * as Location from 'expo-location';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { AuthContext } from './AuthContext';

export default function MyLocationIcon(props) {
    var { loginState, setLocation, setLocationAccuracy } = useContext(AuthContext);
    
    const handlePress = () => {
        console.log('----- Location accuracy: '+ loginState.locationAccuracy);
        setLocationAccuracy(props.LocationAccuracy);
        Location.getCurrentPositionAsync({accuracy: props.LocationAccuracy})
        .then((location) => {
            setLocation(location);
            console.log(location);
        }).catch(() => {
            console.log('location error');
        });
    };

   return (
    <TouchableOpacity title="my location" mode='contained'
        onPress={() => handlePress()} style={styles.fab} >
        <Icon name='crosshairs-gps' size={25}color={props.color}/>
    </TouchableOpacity>
  );
}

const styles = StyleSheet.create({
    fab: { 
        width: 45,
        height: 45, 
        backgroundColor: 'rgba(0,0,0, 0)',
        borderRadius: 50,
        height:'100%', width: '100%',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        // borderWidth:1, borderColor:'red', 
    },
});