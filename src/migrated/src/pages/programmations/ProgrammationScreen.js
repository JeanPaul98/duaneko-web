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
import { socket, serverAddress } from '../../composants/SocketContext';

export default function ProgrammationScreen({ navigation, route  }) {
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
        <TopBarNavRetour fromRoute={'Programmations'}/>
        <ScrollView style={styles.container}>
            <View style={{ 
                height: 150,
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
                <Title style={{fontSize: 30, color:'#222222', fontWeight:'bold'}}>Programmation</Title>
            </View>

          {/* Date */}
          <View style={{ 
            paddingHorizontal: 10, 
            display:'flex', 
            flexDirection:'row', 
            // borderWidth:1,
            alignItems:'center' 
          }}>
            <Icon name="map-marker" color={'gold'} size={40} />
            <View style={{ marginLeft: 10, width:'80%', display:'flex', 
              // borderWidth:1,
              flexDirection:'column', 
              justifyContent:'center' }}>
              <View style={{ display:'flex',
                // borderWidth:1,
                flexDirection:'row', 
                justifyContent:'space-between' }}>
                <Text style={{ fontSize: 14, marginBottom: 2}}>
                  {dayjs(route.params.sig.dateNettoyage).format('dddd, D MMMM YYYY')}
                </Text>              
                <Text style={{ fontSize: 14, marginBottom: 2}}>
                  {dayjs(route.params.sig.dateNettoyage).format('HH:mm')}
                </Text>
              </View>
              <Text style={{ fontSize: 12, color:'grey'}}>
                {route.params.sig.util}
              </Text>
              <Text style={{ fontSize: 12, color:'grey'}} >
                {dayjs(route.params.sig.dateCreation).fromNow()}
              </Text>
             
            </View>
          </View>



          {/* CARTE */}
          <Text style={{ fontSize: 18, marginTop: 20, marginBottom: 10, marginHorizontal: 20, fontWeight:'bold' }}>
            Adresse
          </Text>
          <View style={{ 
            height: 200,
            width:'100%',
            // alignItems: 'center',
            // justifyContent: 'center',
            // borderWidth:1,
            display: 'flex',
          }}>
            <Card style={{ marginHorizontal: 20,
               marginBottom: 20, }}>
              <MapView width={'100%'} height={200} 
                  ref={mapRef}
                  scrollEnabled={false}
                  // showsUserLocation
                  region={{
                    latitude: parseFloat(route.params.sig.latitude),
                    longitude: parseFloat(route.params.sig.longitude),
                    longitudeDelta: 0.05,
                    latitudeDelta: 0.05
                  }}
                  pitchEnabled={false}
                  // onRegionChangeComplete={onRegionChangeComplete} 
              >
                <Marker
                  key={'ddss22'}
                  coordinate={{
                    latitude: parseFloat(route.params.sig.latitude) || 50,
                    longitude: parseFloat(route.params.sig.longitude) || 10
                  }}>
                    <Icon name="map-marker" color={'gold'} size={40} />
                </Marker>
              </MapView>
            </Card>
            
            <View style={{ 
            // height: 200,
            width:'100%',
            alignItems: 'center',
            justifyContent: 'center',
            // borderWidth:1,
            display: 'flex',
          }}>
            <Card style={{ 
              // height: 80,
              // width:'20%',
              zIndex: 2, height: 50,
              position: 'absolute', bottom: 0,
            }}>
              <View style={{ 
                height: '100%',
                // width:'50%',
                // alignItems: 'center',
                // justifyContent: 'center',
                // borderWidth:1,
                display: 'flex', flexDirection:'row', justifyContent:'space-around', 
                alignItems: 'center',
              }}>
                <View style={{ 
                  position: 'absolute', top:10, left: 0, 
                }}>
                  <Icon name="map-marker" color={'gold'} size={20} />
                </View>
                <View style={{ 
                    display: 'flex', flexDirection:'column', 
                    // justifyContent:'space-around', 
                    width: 200,
                    flexWrap:'wrap',
                  }}>
                  <Text style={{ 
                    fontSize: 13, 
                    // color:'blue',
                    marginHorizontal: 20,
                    // borderWidth:1,
                    fontWeight: '500',
                    flexWrap:'wrap',
                    textAlign:'center',
                  }}>
                    {route.params.sig.cite}
                  </Text>
                  
                  <Text style={{ 
                    fontSize: 13, 
                    color:'#4274ff',
                    marginHorizontal: 20,
                    // borderWidth:1,
                  }}>
                    {parseFloat(route.params.sig.latitude).toFixed(2)}, 
                    {parseFloat(route.params.sig.longitude).toFixed(2)}
                  </Text>
                </View>
              </View>
            </Card>
            </View>

            <View style={{ 
              // height: 80,
              // width:'20%',
              // alignItems: 'center',
              // justifyContent: 'center',
              // borderWidth: 1,
              zIndex: 2, 
              position: 'absolute', top: 10, right: 30
            }}>
                <Button title="Itinéraire" onPress={handlePress} uppercase={false}
                // width={20}
                  style={{backgroundColor:'rgba(66, 182, 64, 1)', color: 'white', }}
                  mode="contained">Itinéraire</Button>
            </View>
          </View>


          {/* COMMENTAIRE */}
          <Text style={{ fontSize: 18, marginTop: 40, marginBottom: 10, marginHorizontal: 20, fontWeight:'bold' }}>
            Commentaires
          </Text>
          <Card style={{ marginHorizontal: 20, backgroundColor:"#f4f4f4", marginBottom: 20}}>
            <Text style={{ fontSize: 13, marginVertical: 10, marginHorizontal: 20 }}>
              {route.params.sig.type}
            </Text>
          </Card>


          {/* ACTIONS */}
          <Text style={{ fontSize: 18, marginTop: 20, marginBottom: 10, marginHorizontal: 20, fontWeight:'bold' }}>
            Actions
          </Text>
          <View style={{ 
            width:'100%',
            alignItems: 'center',
            justifyContent: 'center',
            marginBottom: 20,
            paddingHorizontal: 20,
          }}>
              {(route.params.sig.categorie == "programme") ? 
                <Button title="Marquer comme nettoyé" onPress={handlePress}>
                  Marquer comme nettoye
                </Button> :
                ((route.params.sig.categorie == "nettoye") ?
                <Text style={{ fontSize: 18, marginBottom: 10 }}>Propre</Text> : 
                <Button title="Programmer un nettoyage"
                  style={{borderColor:'rgba(66, 182, 64, 1)'}}
                  mode="outlined"
                  width="100%"
                  color="rgba(66, 182, 64, 1)"
                  onPress={handlePress}>
                  Marquer comme nettoyé
                </Button>)
              }
          </View>


          {/* AUTEUR */}
          <Text style={{ fontSize: 18, marginTop: 20, marginBottom: 10, marginHorizontal: 20, fontWeight:'bold' }}>
            Auteur
          </Text>
          <Text style={{ fontSize: 18, marginBottom: 10, marginHorizontal: 20 }}>
            @{route.params.sig.util}
          </Text>

        </ScrollView>
      </View>
    )
  };

  const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: 'white',
    },
});