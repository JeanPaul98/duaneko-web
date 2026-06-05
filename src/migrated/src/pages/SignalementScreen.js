import React, { useState, useEffect, useCallback, useRef, useContext } from 'react';
import { Text, StyleSheet, View, TouchableOpacity, Image, Linking, ScrollView, Dimensions, FlatList  } from 'react-native';
import { StatusBar } from 'expo-status-bar';
import Constants from 'expo-constants';
// import Slider from '@react-native-community/slider';
import MapView, { Marker } from 'react-native-maps';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import { Button, Card, Title } from 'react-native-paper';
import dayjs from 'dayjs';
import localefr from 'dayjs/locale/fr';
import relativeTime from 'dayjs/plugin/relativeTime';
import TopBarNavRetour from '../composants/TopBarNavRetour';
import { socket, serverAddress } from '../composants/SocketContext';
import { AuthContext } from '../composants/AuthContext';

export default function SignalementScreen({ navigation, route }) {
    const { loginState } = useContext(AuthContext);
    const socket1 = loginState.socket;
    const [loading, setLoading] = useState(false);
    const ref = useRef();
    const url1 = "https://www.google.com/maps/dir//" + 
      route.params.sig.latitude + "," + 
      route.params.sig.longitude;

    const handleSupprimer = async () => {
        setLoading(true);
        const timestamp = new Date().toISOString().split("T").join(" ").split("Z").join("");
            // 4. add to db
            socket.emit("supprimerSignalement", {
                id: route.params.sig.id, 
            });
            navigation.navigate('Signalements');
    };
    
    const handlePress = useCallback(async () => {
      const supported = await Linking.canOpenURL(url1);
        if (supported) await Linking.openURL(url1);
        else Alert.alert(`Don't know how to open this URL: ${url1}`);
    }, [url1]);

    const renderImage = ({ item, i }) =>  
      <TouchableOpacity onPress={() => 
        navigation.navigate("SignalementPicsScreen", {pic: item.picsUri, sig: route.params.sig})}
        style={{ 
        // width: Dimensions.get('window').width, 
        display: 'flex',
        alignItems: 'center',
        marginBottom: 10,
        justifyContent: 'center',
        // borderColor: 'red', borderWidth: 1,
      
      }}>
        <Image style={{
        // borderColor: 'red', borderWidth: 1,
        margin: 10,
          width: Dimensions.get('window').width/2, 
          height: Dimensions.get('window').height/2 }}
          source={{ uri: item.picsUri }}
        />
      </TouchableOpacity>
    ;

    const mapRef = useRef();
    // console.log(route.params.sig)
    var numPics = parseInt(route.params.sig.numPics);
    var picsUriArray = [];
    for(let i=0; i< numPics; i++) {
      var picsUri = (route.params.sig.util !== 'andee777bot') ? serverAddress + 'photo/' +  route.params.sig.date + route.params.sig.util + '_' + i : serverAddress + 'photo/image';
      picsUriArray.push({ "picsUri" : picsUri, "id": i });
    }
    dayjs.extend(relativeTime);
    dayjs.locale('fr');

    ref.current?.scrollTo({ x: 0, y: 0, animated: false });

    return (
      <View style={{ flex:1, backgroundColor:"white"}}>
        <StatusBar style="dark" />
        <TopBarNavRetour fromRoute={'Signalements'}/>
        <ScrollView style={styles.container} ref={ref}>
          {/* Title */}
          <View style={{ 
              // height: 100,
              // width: "100%",
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'flex-start',
              // borderColor: "#000000", borderWidth: 1,
              flexDirection:'row',
              paddingBottom: 10,
              paddingHorizontal: 10, 
              marginTop: 80, 
              backgroundColor: '#f4f4f400',
          }}>

            { route.params.sig.urgence === 1 ?
              <TouchableOpacity title="camera" mode='contained' activeOpacity={1}
                // onPress={() => setNiveauUrgence(1)}
                style={{ 
                    marginRight: 10,
                    // borderWidth:3, borderColor: parseInt(route.params.sig.urgence) === 1 ? 'rgba(66, 182, 64, 1)' : '#cccccc', 
                    borderRadius: 10,
                    backgroundColor: 'tomato',
                    height: 40, width: 40,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                }} >
                <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                    !
                </Text>
              </TouchableOpacity> :
              null 
            }
            { route.params.sig.urgence === 2 ?
              <TouchableOpacity title="camera" mode='contained' activeOpacity={1}
                // onPress={() => setNiveauUrgence(2)}
                style={{ 
                    marginRight: 10,
                    // borderWidth:3, borderColor: parseInt(route.params.sig.urgence) === 2 ? 'rgba(66, 182, 64, 1)' : '#cccccc', 
                    backgroundColor: 'orangered',
                    borderRadius: 10,
                    height: 40, width: 40,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                }} >
                <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                    !!
                </Text>
              </TouchableOpacity> :
              null 
            }
            { route.params.sig.urgence === 3 ?
              <TouchableOpacity title="camera" mode='contained' activeOpacity={1}
                // onPress={() => setNiveauUrgence(3)}
                style={{ 
                    marginRight: 10,
                    // borderWidth:3, borderColor: parseInt(route.params.sig.urgence) === 3 ? 'rgba(66, 182, 64, 1)' : '#cccccc', 
                    backgroundColor: 'red',
                    borderRadius: 10,
                    height: 40, width: 40,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                }} >
                <Text style={{ fontSize: 18, color: 'white', fontWeight:'bold' }}>
                    !!!
                </Text>
              </TouchableOpacity> :
              null 
            }
            <Title style={{
                fontSize: 25, 
                color:'#222222', 
                // borderColor: "#000000", borderWidth: 1,
                fontWeight:'bold',
                marginBottom: 0,
              }}>
              { (route.params.sig.type === 'Dépotoirs sauvages') ? 
                  'Dépotoir sauvage' : 
                  'Caniveau Bouché'
              }
            </Title>
          </View>

          {/* Photos */}
          {
            numPics === 0 ? 
              null :
              <FlatList horizontal renderItem={renderImage}
                  style={{ 
                    height: '40%',
                    marginBottom: 20,
                  }}
                  data={picsUriArray}
                  keyExtractor={({ id }, index) => id}
                  removeClippedSubviews={true}
              />
          }


          {/* Date */}
          <View style={{ 
            paddingHorizontal: 10, 
            display:'flex', 
            flexDirection:'row', 
            // borderWidth:1,
            alignItems:'center' 
          }}>
            <Icon name="map-marker" color={'red'} size={40} />
            <View style={{ marginLeft: 10, width:'80%', display:'flex', 
              // borderWidth:1,
              flexDirection:'column', 
              justifyContent:'center' }}>
              <Text style={{ fontSize: 14, marginBottom: 2}}>
                {dayjs(route.params.sig.date).fromNow()}
              </Text>
              <View style={{ 
                  display:'flex',
                  flexDirection:'row', 
                  justifyContent:'space-between' 
                }}>
                <Text style={{ fontSize: 12, color:'grey'}}>
                  {dayjs(route.params.sig.date).format('dddd, D MMMM YYYY')}
                </Text>              
                <Text style={{ fontSize: 12, color:'grey'}}>
                  {dayjs(route.params.sig.date).format('HH:mm')}
                </Text>
              </View>
            </View>
          </View>

          {/* Type Icon */}
          {/* <Text style={{ fontSize: 18, marginTop: 20, marginBottom: 30, marginHorizontal: 20, fontWeight:'bold' }}>
            Type de signalement
          </Text> */}

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
                    <Icon name="map-marker" color={'red'} size={40} />
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
                  <Icon name="map-marker" color={'red'} size={20} />
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
              {route.params.sig.commentaires}
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
                  style={{marginBottom: 20, borderColor:'rgba(66, 182, 64, 1)'}}
                  mode="outlined"
                  width="100%"
                  color="rgba(66, 182, 64, 1)"
                  onPress={handlePress}>
                  Programmer un nettoyage
                </Button>)
              }
              <TouchableOpacity onPress={() => {}}
                style={{ 
                  borderColor:'#1E90FF55',
                  borderWidth: 1,
                  borderRadius: 5,
                  width: '100%', 
                  height: 40,
                  alignItems: 'center',
                  justifyContent: 'center',
                  marginBottom: 20,
              }}
              >
                <Text style={{color: 'dodgerblue', fontSize: 16}}>MODIFIER</Text>
              </TouchableOpacity>
              <TouchableOpacity onPress={() => handleSupprimer()}
                style={{ 
                  borderColor:'#DC143C55',
                  borderWidth: 1,
                  borderRadius: 5,
                  width: '100%', 
                  height: 40,
                  alignItems: 'center',
                  justifyContent: 'center',
                  marginBottom: 20,
              }}
              >
                <Text style={{ color: '#DC143CAA', fontSize: 16 }}>SUPPRIMER</Text>
              </TouchableOpacity>
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