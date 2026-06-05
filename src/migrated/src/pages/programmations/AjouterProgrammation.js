import React, { useState, useEffect, useContext, useRef } from 'react';
import { Text, ActivityIndicator, View, TouchableOpacity, Button, Image, Linking, ScrollView } from 'react-native';
import { StatusBar } from 'expo-status-bar';
import * as ImageManipulator from 'expo-image-manipulator';
import * as Location from 'expo-location';
import { serverAddress } from '../../composants/SocketContext';
import { AuthContext } from '../../composants/AuthContext';
import CameraScreen from '../CameraScreen';
import TopBarNav from '../../composants/TopBarNav';
import { Title } from 'react-native-paper';
import { getStatusBarHeight } from 'react-native-status-bar-height';
import Icon from 'react-native-vector-icons/MaterialIcons';
import { useNavigation } from '@react-navigation/native';

export default function AjouterProgrammation({ route  }) {
    const { loginState } = useContext(AuthContext);
    const socket1 = loginState.socket;
    const navigation = useNavigation();

    const [progressUpload, setprogressUpload] = useState("");
    const [pics, setPics] = useState([]);
    // const imageReceived = route.params?.pic;
    useEffect(() => {
        // console.log("current route:\n", route, '\n\nparent route:\n', navigation.getParent());
        // console.log("ajouter signalements params : " + JSON.stringify(route.params));
        if(route.params) { 
            setPics(route.params);
        }
        
    }, [route.params]);

    const handleAjouter = async () => {
        const timestamp = new Date().toISOString().replaceAll('T', ' ').replaceAll('Z', '');
  
        // add signalement details to db
        socket1.emit("ajouterProgrammation", {
            id: 'P' + timestamp + loginState.user, 
            util: loginState.user,
            latitude: loginState.location.coords.latitude,
            longitude: loginState.location.coords.longitude,
            dateNettoyage: '2025-07-02 04:41:57.035',
            dateCreation: timestamp,
        });
        navigation.navigate('Programmations');
    }

    const obtenirAdresse = async () => {
        setprogressUpload('Attente position...');
        Location.getCurrentPositionAsync({accuracy: loginState.locationAccuracy})
        .then((location1) => {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${location1.coords.latitude}&lon=${location1.coords.longitude}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then((osmObj) => {
                setprogressUpload(JSON.stringify(osmObj.address).replaceAll(',', '\n').replaceAll('{', '').replaceAll('}', ''));
            })
            .catch(() => console.log('fetch error obtenirAdresseProgrammation'));
        });
    };
    
    return (
        <View style={{ flex: 1 }}>
            <StatusBar style="dark" />
            {/* TOP BAR */}
            <View style={{ 
                marginTop: getStatusBarHeight(),
                height: 60, width: "100%",
                borderWidth:1, borderColor:'white', 
                backgroundColor: '#ffffff99',
                // borderBottomRightRadius: 15,
                // borderBottomLeftRadius: 15,
                // borderTopRightRadius: statusbarHeight-5,
                borderRadius: 15,
                position: 'absolute',
                left: 0,
                zIndex: 5,
                top: 0,
                display: 'flex',
                flexDirection:'row',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingHorizontal: 15,
                }}>
                <TouchableOpacity title="Menu" mode='contained' 
                    onPress={() => navigation.navigate('Programmations')}
                    style={{ 
                        // width: 40,
                        height: 40, 
                        // backgroundColor: 'rgba(0,0,0, 0)',
                        // borderColor:'rgba(100,100,100, 0)',  
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        // marginLeft: 15, 
                        // borderWidth:1, borderColor:'red', 
                    }} >
                    <Text style={{ 
                        fontSize: 17,
                        // padding: 10,
                        color: 'crimson',
                    }}>Annuler</Text>
                </TouchableOpacity>
                <TouchableOpacity title="carte" mode='contained'
                    onPress={() => handleAjouter()}
                    style={{ 
                        // width: 40,
                        height: 40, 
                        backgroundColor: '#3CAA4A',
                        // borderColor:'rgba(100,100,100, 0)',  
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        // marginRight: 15,
                        // borderWidth:1, borderColor:'red', 
                        borderRadius: 5,
                        paddingHorizontal: 10,
                    }} >
                    <Text style={{ 
                        fontSize: 17,
                        // padding: 10,
                        color: '#f6f6f6',
                    }}>Ajouter</Text>
                </TouchableOpacity>
            </View>
            {/* TITLE */}
            <View style={{ 
                marginTop: 80,
                // width: "100%",
                display: 'flex',
                alignItems: 'flex-end',
                justifyContent: 'flex-start',
                flexDirection:'row',
                // borderColor:'rgba(100,100,100, 1)', 
                // borderWidth:2, 
                padding: 20,
                backgroundColor: '#f4f4f400',
            }}>
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Nouvelle programmation</Title>
            </View>
            <ScrollView>
                {/* ADDRESS */}
                <Button onPress={() => obtenirAdresse()} title="Obtenir mon adresse" />
                <Text style={{marginTop: 10, marginLeft: 15}}>{progressUpload}</Text>
            </ScrollView>

        </View>
    );
  }