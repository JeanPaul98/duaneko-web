import React, { useEffect, useState, useRef, useContext } from 'react'
import {
  View, Image, StyleSheet, 
  Dimensions, TouchableOpacity,
  LayoutAnimation,Text, Platform,
  useWindowDimensions
} from 'react-native';
import 
  // MapView, 
{ Marker, Heatmap, } from 'react-native-maps';
import MapView from "react-native-map-clustering";
import GeoViewport from '@mapbox/geo-viewport'
// import useSupercluster from "use-supercluster";
import Icon from 'react-native-vector-icons/MaterialIcons';
import Icon22 from 'react-native-vector-icons/MaterialCommunityIcons';

import AntD from 'react-native-vector-icons/AntDesign';
import * as Location from 'expo-location';
import { Button1, Subheading , Title } from 'react-native-paper';
import { ActivityIndicator } from 'react-native-paper';
import MyLocationIcon from '../composants/MyLocationIcon';
import { regionToBoundingBox } from '../composants/util'
import { AuthContext } from '../composants/AuthContext';
// import * as customData from '../Signalements.json';
import { StatusBar } from 'expo-status-bar';
import CameraScreen from './CameraScreen';
import Measurements from '../composants/Measurements';
import { getStatusBarHeight } from 'react-native-status-bar-height';
import LoginStatePanel from '../composants/LoginStatePanel';
import { BlurView } from 'expo-blur';
import { socket, serverAddress } from '../composants/SocketContext';

export default function ClusteredMapView({ navigation }) {
  var { loginState, setLocation } = useContext(AuthContext);
  const mapRef = useRef();
  const { height, width } = useWindowDimensions();
  const dimensions = [width, height];
  const [checked, setChecked] = useState(false);
  const handleCheck = () => (checked === "checked") ? setChecked('unchecked') : setChecked('checked');
  const [open, setOpen] = useState(false);
  const handleOpen = () => setOpen(!open);
  const handleClose = () => setOpen(false);
  const [openSearch, setOpenSearch] = useState(false);
  const handleOpenSearch = () => setOpenSearch(!openSearch);
  const handleCloseSearch = () => setOpenSearch(false);
  const [myLocation, setMyLocation] = useState(loginState.location);
  const [myFilters, setMyFilters] = useState({
    category: [],
    type: [],
    region: [],
    cite: [],
  });

  const handleAppliquerFiltres = () => {
    socket.emit('getSignalementsByType', {type: 'Dépotoirs sauvages'});
    handleClose();
  }

  const handleResetFilter = () => {
    socket.emit('getSignalements', '');
    handleClose();
  }

  const animateToRegion = () => {
    let region = {
      // latitude: 9.461631058732182,
      latitude: loginState.location.coords.latitude,
      // longitude: -0.232885951208177,
      longitude: loginState.location.coords.longitude,
      latitudeDelta: 0.01,
      longitudeDelta: 0.01,
    };
    mapRef.current.animateToRegion(region, 2000);
  };

  React.useEffect(() => {
    socket.emit('getCount', '');
    socket.emit('getProgrammations', '');
    socket.emit('getSignalements', '');
  }, []);

  return <View style={styles.container}>     
      <StatusBar style="dark" />
      {/* <Measurements/> */}

      {/* TOP BAR ICONS */}
      <BlurView tint="light" intensity={ Platform.OS === 'android' ? 100 : 20 } 
        style={{ 
          // width: 80,
          // height: 200,
          marginTop: getStatusBarHeight(),
          height: 60,
          width: "100%",
          borderWidth:1, borderColor:'#ffffff99', 
          backgroundColor: '#ffffffcf',
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
          justifyContent: 'space-between'
        }}
      >
        <TouchableOpacity mode='contained' title="Menu" 
          style={styles.menuButton}
          onPress={() => navigation.openDrawer()}
        >
          <Icon style={{ 
            // padding:0,
            // margin:0,
            color: '#3CAA4A'
          }} name='menu' size={40}/>
        </TouchableOpacity>

        <TouchableOpacity title="Signalements" mode='contained'
          onPress={() => navigation.navigate("Signalements")}
          style={{ 
            width: 40,
            height: 40, 
            backgroundColor: 'rgba(0,0,0, 0)',
            borderColor:'rgba(100,100,100, 0)',  
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            marginRight: 15,
            // borderWidth:1, borderColor:'red', 
          }} >
          <Icon name='description' size={35} color={'#3CAA4A'}/>
        </TouchableOpacity>
      </BlurView >

      {/* LOCATION DISPLAY PANEL */}
      {/* <LoginStatePanel/> */}


      {/* FILTERS */}
      {
        open ? 
          <View style={{ 
            // borderWidth:1, borderColor:'red', 
            position: 'absolute',
            width: '100%', height: '100%',
            zIndex: 15,
            top: 0, left: 0,
            backgroundColor: '#222222ef',
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'flex-start',
            paddingHorizontal: 25,
            paddingVertical: 30,
          }}>
          <View >
            <Title style={{fontSize: 30, color:'white', fontWeight:'bold', marginBottom: 20}}>Filtres</Title>
            <Subheading  style={{color:'white', fontWeight: '600'}}>Catégorie</Subheading >
            <TouchableOpacity
                style={{ 
                  height: 35, width: 200,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'flex-start',
                  borderRadius: 5,
                  backgroundColor: '#f4f4f400',
                  // marginLeft: 10,
                }}
                onPress={() => handleSetType()}>
                <Text style={{color: 'white', marginRight: 10}}>Caniveaux bouchés</Text>
                <Icon name='check-circle' size={20} color={'white'}/>
            </TouchableOpacity>
            <TouchableOpacity
                style={{ 
                  height: 35, width: 200,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'flex-start',
                  borderRadius: 5,
                  // marginLeft: 10,
                  backgroundColor: '#f4f4f400'
                }}
                onPress={() => {}}>
                <Text style={{color: 'white', marginRight: 10}}>Dépotoirs sauvages</Text>
                <Icon name='circle' size={20} color={'white'}/>
            </TouchableOpacity>
            <Subheading  style={{ color:'white', fontWeight: '600', marginTop: 20 }}>Type</Subheading >
            <TouchableOpacity
                style={{ 
                  height: 35, width: 200,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'flex-start',
                  borderRadius: 5,
                  // marginLeft: 10,
                  backgroundColor: '#f4f4f400'
                }}
                onPress={() => {}}>
                <Text style={{color: 'white', width: 100, marginRight: 10}}>Signalés</Text>
                <Icon name='circle' size={20} color={'white'}/>
            </TouchableOpacity>
            <TouchableOpacity
                style={{ 
                  height: 35, width: 200,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'flex-start',
                  borderRadius: 5,
                  // marginLeft: 10,
                  backgroundColor: '#f4f4f400'
                }}
                onPress={() => {}}>
                <Text style={{color: 'white', width: 100, marginRight: 10}}>Programmés</Text>
                <Icon name='circle' size={20} color={'white'}/>
            </TouchableOpacity>
            <TouchableOpacity
                style={{ 
                  height: 35, width: 200,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'flex-start',
                  borderRadius: 5,
                  // marginLeft: 10,
                  backgroundColor: '#f4f4f400'
                }}
                onPress={() => {}}>
                <Text style={{color: 'white', width: 100, marginRight: 10}}>Nettoyés</Text>
                <Icon name='circle' size={20} color={'white'}/>
            </TouchableOpacity>
            <Subheading  style={{ color:'white', fontWeight: '600', marginTop: 20 }}>Région</Subheading >
            <Text style={{color:'white'}} id="modal-modal-description">
              
            </Text>
          </View>
          <TouchableOpacity
              mode='contained'
              style={{ 
                height: 40, width: '100%',
                display: 'flex',
                flexDirection:'row',
                alignItems: 'center',
                justifyContent: 'center',
                borderRadius: 5,
                backgroundColor: '#f4f4f400'
              }}
              onPress={() => handleResetFilter()}>
              <Icon name='undo' size={25} color={'white'}/>
              <Text style={{color: 'white', marginLeft: 10}}>Reinitialiser</Text>
          </TouchableOpacity>
          <View style={{ 
              width: '100%',
              display: 'flex',
              flexDirection:'row-reverse',
              alignItems: 'center',
              justifyContent: 'space-between',
            }}>
            <TouchableOpacity
                mode='contained'
                style={{ 
                  height: 40, width: 120,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'center',
                  borderRadius: 5,
                  backgroundColor: '#3CAA4A'
                }}
                onPress={() => handleAppliquerFiltres()}>
                <Icon name='filter-alt' size={25} color={'#f4f4f4'}/>
                <Text style={{color: '#f4f4f4'}}>Appliquer</Text>
            </TouchableOpacity>     
            <TouchableOpacity
                mode='contained'
                style={{ 
                  height: 40, width: 120,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'center',
                  borderRadius: 5,
                  backgroundColor: '#f4f4f4'
                }}
                onPress={() => handleClose()}>
                <Icon name='close' size={25} color={'#f45555'}/>
                <Text style={{color: '#f45555'}}>Annuler</Text>
            </TouchableOpacity> 
          </View>
          </View> : null 
      }

      {/* SEARCH */}
      {
        openSearch ? <View style={{ 
            // borderWidth:1, borderColor:'red', 
            position: 'absolute',
            width: '100%',
            height: '100%',
            zIndex: 15,
            top: 0,
            // top: '30%',
            left: 0,
            backgroundColor: '#222222ef',
            // borderRadius: 5,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'flex-start',
            paddingHorizontal: 25,
            paddingVertical: 30,
          }}>
          <View >
            <Title style={{fontSize: 30, color:'white', fontWeight:'bold'}}>Recherche</Title>
          </View>
          <View style={{ 
              width: '100%',
              display: 'flex',
              flexDirection:'row-reverse',
              alignItems: 'center',
              justifyContent: 'space-between',
            }}>
            <TouchableOpacity
                mode='contained'
                style={{ 
                  height: 40, width: 120,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'center',
                  borderRadius: 5,
                  backgroundColor: '#3CAA4A'
                }}
                onPress={() => handleCloseSearch()}>
                <Icon name='search' size={25} color={'#f4f4f4'}/>
                <Text style={{color: '#f4f4f4'}}>Chercher</Text>
            </TouchableOpacity>     
            <TouchableOpacity
                mode='contained'
                style={{ 
                  height: 40, width: 120,
                  display: 'flex',
                  flexDirection:'row',
                  alignItems: 'center',
                  justifyContent: 'center',
                  borderRadius: 5,
                  backgroundColor: '#f4f4f4'
                }}
                onPress={() => handleCloseSearch()}>
                <Icon name='close' size={25} color={'#f45555'}/>
                <Text style={{color: '#f45555'}}>Annuler</Text>
            </TouchableOpacity> 

        </View>
        </View> : null 
      }

      {/* BOTTOM ACTIONS BUTTONS */}
      <View intensity={20} style={{ 
          height: 50,
          width: 50,
          // borderWidth:1, borderColor:'red', 
          backgroundColor: '#f4f4f4',
          position: 'absolute',
          right: 15,
          bottom: 190,
          zIndex: 5,
          display: 'flex',
          flexDirection:'row',
          alignItems: 'center',
          justifyContent: 'center',
          borderRadius: 50
        }}>
          <TouchableOpacity title="filter" mode='contained'
            style={{ 
              height:'100%', width: '100%',
              display: 'flex',
              flexDirection:'row',
              alignItems: 'center',
              justifyContent: 'center',
              // borderRadius: '50%',
            }}
            onPress={() =>{
              socket.emit('getCount', '');
              socket.emit('getProgrammations', '');
              socket.emit('getSignalements', '');
            }}>
            <Icon22 name='reload' size={25} color={'#306733'}/>
        </TouchableOpacity>
      </View>
      {/* My location */}
      <View intensity={20} style={{ 
          height: 50,
          width: 50,
          // borderWidth:1, borderColor:'red', 
          backgroundColor: '#f4f4f4',
          position: 'absolute',
          right: 15,
          bottom: 130,
          zIndex: 5,
          display: 'flex',
          flexDirection:'row',
          alignItems: 'center',
          justifyContent: 'center',
          borderRadius: 50
        }}>
          <TouchableOpacity title="filter" mode='contained'
            style={{ 
              height:'100%', width: '100%',
              display: 'flex',
              flexDirection:'row',
              alignItems: 'center',
              justifyContent: 'center',
              // borderRadius: '50%',
            }}
            onPress={() => animateToRegion()}
          >
            <Icon22 name='crosshairs-gps' size={25} color={'#306733'}/>
        </TouchableOpacity>
      </View>
      {/* Filter button */}
      <View intensity={20} style={{ 
          height: 50,
          width: 50,
          // borderWidth:1, borderColor:'red', 
          backgroundColor: '#3d8941',
          position: 'absolute',
          right: 15,
          bottom: 65,
          zIndex: 5,
          display: 'flex',
          flexDirection:'row',
          alignItems: 'center',
          justifyContent: 'center',
          borderRadius: 50
        }}>
        <TouchableOpacity title="filter" mode='contained'
            style={{ 
              height:'100%', width: '100%',
              display: 'flex',
              flexDirection:'row',
              alignItems: 'center',
              justifyContent: 'center',
              // borderRadius: '50%',
            }}
            onPress={() => handleOpen()}>
            <Icon name='filter-alt' size={25} color={'#f4f4f4'}/>
        </TouchableOpacity>        
        
      </View>
      {/* Bottom bar */}
      <BlurView tint="light" intensity={ Platform.OS === 'android' ? 100 : 20 } 
        style={{ 
          // width: 80,
          // height: 200,
          height: 50,
          width: "100%",
          // borderWidth:1, borderColor:'red', 
          backgroundColor: '#ffffffcf',
          position: 'absolute',
          left: 0,
          zIndex: 5,
          bottom: 0,
          display: 'flex',
          flexDirection:'row',
          alignItems: 'center',
          justifyContent: 'space-between'
        }}
      >
        <TouchableOpacity title="Ajouter un signalement" 
            style={{ 
              height: 60,
              alignItems: 'center',
              justifyContent: 'space-between',
              flexDirection:'row',
              marginLeft: 15,
            }}
            onPress={() => navigation.navigate({
              name: 'Ajouter un signalement',
              params: { post: navigation.getState().routeNames[navigation.getState().index] },
            })} >
            <Icon name='add-circle' size={30} color={'#3CAA4A'}/>
            <Text style={{ 
                color: '#3CAA4A',
                fontSize: 16.5,
                fontWeight: '700', marginLeft: 8,
            }}>Signalement</Text>
        </TouchableOpacity>
        <TouchableOpacity title="search" mode='contained'
          style={{ 
            height: 60,
            alignItems: 'center',
            justifyContent: 'space-between',
            flexDirection:'row',
            marginRight: 15,

          }}
          onPress={() => handleOpenSearch()}>            
          <Icon name='search' size={30} color={'#3CAA4A'}/>
        </TouchableOpacity>
      </BlurView>

      {/* MAPVIEW */}
      <MapView // style={{ flex: 1 }}
        edgePadding={{
          top: 120, bottom: 120, left: 15, right: 15
        }}
      //   initialRegion={{
      //     latitude: 37.78825,
      //     longitude: -122.4324,
      //     latitudeDelta: 0.0922,
      //     longitudeDelta: 0.0421,
      //  }}
        // provider={'google'}
        // animationEnabled={false}
        // clusterColor={`rgba(255,0,0, ${loginState.count ? loginState.count.count_sig/loginState.count.total/3 : 1})`}
        // clusterTextColor={`rgba(255,255,255, 0.8)`}
        // clusterTextColor={`rgba(255,255,255, ${loginState.count ? loginState.count.count_sig/loginState.count.total*1.5 : 1})`}
        // ref={mapRef}
          width={width} height={height+25} 
          initialRegion={{
            latitude: parseFloat(8.5),
            longitude: parseFloat(1.2),
            latitudeDelta: parseFloat(3.5),
            longitudeDelta: parseFloat(4.85),
          }}
          minPoints={5}
          // provider="google"
          // styles={{display: 'flex', flex:1, borderWidth:1, borderColor:'red',}} 
          ref={mapRef}
          // showsUserLocation
          // region={displayedLocation}
          pitchEnabled={false}
          // onRegionChangeComplete={onRegionChangeComplete} 
      >
      
        {/* MY LOCATION */}
        {/* <Marker key={'ddss22'}
          coordinate={{
            latitude: parseFloat(loginState.location.coords.latitude),
            longitude: parseFloat(loginState.location.coords.longitude)
          }}
        >
            <Icon name="place" color={'blue'} size={25} />
        </Marker> */}

        { loginState.signalements !== null ?
            loginState.signalements.map((point, i) => {
              return <Marker key={i}
              pinColor={"red"}

                coordinate={{
                  latitude: parseFloat(point.latitude),
                  longitude: parseFloat(point.longitude)
                }}
              />
            }) 
            : 
            null
        } 
        { loginState.programmations !== null ?
            loginState.programmations.map((point, i) => {
              return <Marker key={i}
              pinColor={"gold"}
              // pinColor={"#3CAA4A"}
                coordinate={{
                  latitude: parseFloat(point.latitude),
                  longitude: parseFloat(point.longitude)
                }}
              />
            }) 
            : 
            null
        } 
      </MapView>
    </View>
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
  menuButton: { 
    width: 45,
    height: 45, 
    backgroundColor: 'rgba(0,0,0, 0)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    // borderWidth:1, borderColor:'red', 
    // borderRadius: 1,
    marginLeft: 15,
  },
  map: {
    width: Dimensions.get('window').width,
    height: Dimensions.get('window').height,
  },
  myClusterStyle: {
    width: 20,
    height: 20,
    borderRadius: 10,
    backgroundColor: "blue",
    justifyContent: 'center',
    alignItems: 'center'
  },
  myClusterTextStyle: {
    color: 'white',
  },
  marker: {
    width: 24,
    height: 24,
  },

});